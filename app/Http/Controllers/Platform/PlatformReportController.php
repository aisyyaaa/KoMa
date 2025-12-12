<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Review;
use App\Models\User; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlatformReportController extends Controller
{
    // KONSTRUKTOR AUTH TELAH DIHAPUS

    /**
     * Laporan Penjual Aktif (SRS-MartPlace-09)
     */
    public function activeSellers()
    {
        $active = Seller::where('status', 'ACTIVE')->count();
        $inactive = Seller::whereNotIn('status', ['ACTIVE'])->count();
        $total = $active + $inactive;

        $sellers = Seller::select('id', 'store_name', 'pic_name', 'email', 'phone_number', 'status', 'updated_at', 'registration_date')
            ->withCount('products')
            // FIX KRITIS: Menggunakan CASE untuk kompatibilitas SQLite/Urutan status (ACTIVE, REJECTED, PENDING)
            ->orderByRaw("CASE 
                            WHEN status = 'ACTIVE' THEN 1 
                            WHEN status = 'REJECTED' THEN 2 
                            WHEN status = 'PENDING' THEN 3 
                            ELSE 4 
                          END") 
            ->orderBy('store_name')
            ->get()
            ->map(function ($s) {
                if (strtoupper($s->status) === 'ACTIVE') {
                    $s->is_active_status = 'Aktif';
                    $s->last_active = $s->updated_at;
                } else if (strtoupper($s->status) === 'REJECTED') {
                    $s->is_active_status = 'Tidak Aktif (Ditolak)';
                    $s->last_active = null;
                } else {
                    $s->is_active_status = 'Menunggu Verifikasi';
                    $s->last_active = $s->registration_date;
                }
                $s->pic_phone = $s->phone_number; 
                $s->pic_email = $s->email;
                return $s;
            });

        return view('platform.reports.active_sellers', compact('active', 'inactive', 'total', 'sellers'));
    }

    /**
     * Laporan Penjual Berdasarkan Provinsi (SRS-MartPlace-10)
     * Mengambil daftar rinci SEMUA penjual dan mengelompokkannya berdasarkan provinsi.
     */
    public function sellersByProvince()
    {
        // Query Sub-Select untuk menghitung total seller per provinsi
        $subQuery = Seller::select('province', DB::raw('count(*) as count_province'))
                         ->groupBy('province');

        // 1. Ambil data rinci SEMUA penjual (Aktif, Pending, Reject)
        $allSellers = Seller::select('sellers.store_name', 'sellers.pic_name', 'sellers.province', 'sellers.status')
            // Join dengan sub-query untuk mendapatkan jumlah seller per provinsi
            ->leftJoin(DB::raw('(' . $subQuery->toSql() . ') as sub'), 'sellers.province', '=', 'sub.province')
            ->mergeBindings($subQuery->getQuery()) // Penting untuk binding SQLITE
            
            // REVISI KRITIS: Urutkan berdasarkan Jumlah Seller (DESC)
            ->orderBy('sub.count_province', 'desc') 
            ->orderBy('sellers.province') // Urutan kedua: Abjad Provinsi (A-Z)
            ->orderBy('sellers.store_name')
            ->get();
            
        // 2. Kelompokkan data untuk View HTML (Tabular) dan Ringkasan
        $groupedSellers = $allSellers->groupBy('province');

        // Untuk tampilan ringkas (ringkasan total di View HTML)
        $byProvince = $allSellers->groupBy('province')->map(function ($group) {
            $province = $group->first()->province ?? 'Tidak Diketahui';
            return (object)['total' => $group->count(), 'province' => $province];
        })->sortByDesc('total');

        // Mengirim daftar datar ($allSellers) untuk tabel rincian di View HTML, 
        // dan $byProvince untuk ringkasan. $groupedSellers dikirim untuk kompatibilitas.
        return view('platform.reports.sellers_by_province', compact('allSellers', 'byProvince', 'groupedSellers'));
    }

    /**
     * Laporan Produk Berdasarkan Rating (SRS-MartPlace-11)
     */
    public function productsByRating()
    {
        $products = Product::with(['seller', 'category']) 
            ->withAvg('reviews', 'rating') 
            ->orderByDesc('reviews_avg_rating') 
            ->paginate(15);
        
        return view('platform.reports.products_by_rating', compact('products'));
    }

    /**
     * Export platform reports to PDF (SRS-MartPlace-09, 10, 11)
     */
    public function exportPdf($type)
    {
        $data = [];
        $view = '';
        $filename = '';

        $pemroses = Auth::user() ?? User::where('role', 'platform')->first(); 
        $data['pemroses'] = $pemroses;
        $data['date'] = now()->format('d-m-Y');

        // Mendefinisikan Query Order By Count untuk digunakan di Export PDF
        $subQuery = Seller::select('province', DB::raw('count(*) as count_province'))
                         ->groupBy('province');

        try {
            if ($type === 'seller-accounts') {
                $data['active'] = Seller::where('status', 'ACTIVE')->count();
                $data['inactive'] = Seller::whereNotIn('status', ['ACTIVE'])->count();

                $data['sellers'] = Seller::select('store_name', 'pic_name', 'email', 'phone_number', 'status', 'updated_at', 'registration_date')
                    ->withCount('products')
                    // FIX KRITIS: Terapkan CASE expression di exportPdf untuk sorting
                    ->orderByRaw("CASE 
                                    WHEN status = 'ACTIVE' THEN 1 
                                    WHEN status = 'REJECTED' THEN 2 
                                    WHEN status = 'PENDING' THEN 3 
                                    ELSE 4 
                                  END") 
                    ->orderBy('store_name')
                    ->get()
                    ->map(function ($s) {
                        $s->is_active_status = (strtoupper($s->status) === 'ACTIVE') ? 'Aktif' : 'Tidak Aktif';
                        $s->last_active = (strtoupper($s->status) === 'ACTIVE') ? $s->updated_at : $s->registration_date;
                        $s->pic_phone = $s->phone_number; 
                        $s->pic_email = $s->email;
                        return $s;
                    });

                $view = 'platform.reports.pdf.seller_accounts_pdf'; 
                $filename = 'seller-accounts-' . $data['date'] . '.pdf'; 

            } elseif ($type === 'seller-locations') {
                // Ambil data rinci SEMUA SELLER (Aktif, Pending, Reject)
                $allSellers = Seller::select('sellers.store_name', 'sellers.pic_name', 'sellers.province', 'sellers.status')
                    ->leftJoin(DB::raw('(' . $subQuery->toSql() . ') as sub'), 'sellers.province', '=', 'sub.province')
                    ->mergeBindings($subQuery->getQuery()) // Penting untuk binding SQLITE
                    
                    // REVISI KRITIS: Urutkan berdasarkan Jumlah Seller (DESC)
                    ->orderBy('sub.count_province', 'desc') 
                    ->orderBy('sellers.province') // Urutan kedua: Abjad Provinsi (A-Z)
                    ->orderBy('sellers.store_name')
                    ->get();
                
                // Mengirim data yang dikelompokkan untuk PDF (untuk grouping di PDF view)
                $data['groupedSellers'] = $allSellers->groupBy('province'); 
                
                $view = 'platform.reports.pdf.seller_locations_pdf'; 
                $filename = 'seller-locations-' . $data['date'] . '.pdf';

            } elseif ($type === 'products_by_rating') { // SRS-11
                $data['products'] = Product::with(['seller', 'category'])
                    ->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->get();
                $view = 'platform.reports.pdf.products_by_rating'; 
                $filename = 'product-ratings-' . $data['date'] . '.pdf';
            } else {
                return back()->with('error', 'Tipe laporan tidak dikenal: ' . $type);
            }

            $pdfAvailable = class_exists(Pdf::class);
            if (!$pdfAvailable) {
                return redirect()->back()->with('error', 'PDF export requires package "barryvdh/laravel-dompdf".');
            }

            $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');
            $pdf->getDomPDF()->setHttpContext(
                stream_context_create([
                    'ssl' => [
                        'allow_self_signed'=> TRUE,
                        'verify_peer' => FALSE,
                        'verify_peer_name' => FALSE,
                    ]
                ])
            );
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error("PDF Export Error: " . $e->getMessage() . " on file " . $e->getFile() . " line " . $e->getLine());
            return back()->with('error', 'Terjadi kesalahan saat membuat PDF: ' . $e->getMessage() . ' (Cek log untuk detail).');
        }
    }
}