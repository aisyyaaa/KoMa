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
use Illuminate\Database\Eloquent\Builder; 

class PlatformReportController extends Controller
{
    // KONSTRUKTOR AUTH TELAH DIHAPUS (Mengikuti struktur yang Anda berikan)

    /**
     * Laporan Penjual Aktif (SRS-MartPlace-09)
     */
    public function activeSellers(Request $request)
    {
        $searchQuery = trim($request->get('search')); // FIX: Tambahkan trim()
        
        $active = Seller::where('status', 'ACTIVE')->count();
        $inactive = Seller::whereNotIn('status', ['ACTIVE'])->count();
        $total = $active + $inactive;

        $query = Seller::select('id', 'store_name', 'pic_name', 'email', 'phone_number', 'status', 'updated_at', 'registration_date')
            ->withCount('products');
            
        // REVISI KRITIS 1: Tambahkan Filter Pencarian (Search Bar)
        if ($searchQuery) {
            $query->where(function (Builder $q) use ($searchQuery) {
                // Cari di Nama Toko, Nama PIC, Email, atau Nomor Telepon
                $q->where('store_name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('pic_name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $searchQuery . '%')
                  ->orWhere('phone_number', 'like', '%' . $searchQuery . '%');
            });
        }
            
        // Pengurutan tetap sama
        $sellers = $query->orderByRaw("CASE 
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

        return view('platform.reports.active_sellers', compact('active', 'inactive', 'total', 'sellers', 'searchQuery'));
    }

    /**
     * Laporan Penjual Berdasarkan Provinsi (SRS-MartPlace-10)
     */
    public function sellersByProvince(Request $request)
    {
        $searchQuery = trim($request->get('search')); // FIX: Tambahkan trim()

        // Query Sub-Select untuk menghitung total seller per provinsi
        $subQuery = Seller::select('province', DB::raw('count(*) as count_province'))
                             ->groupBy('province');

        // 1. Ambil data rinci SEMUA penjual (Aktif, Pending, Reject)
        $query = Seller::select('sellers.id', 'sellers.store_name', 'sellers.pic_name', 'sellers.province', 'sellers.status', 'sellers.city', 'sellers.district')
            ->withCount('products') 
            // Join dengan sub-query untuk mendapatkan jumlah seller per provinsi
            ->leftJoin(DB::raw('(' . $subQuery->toSql() . ') as sub'), 'sellers.province', '=', 'sub.province')
            ->mergeBindings($subQuery->getQuery());
        
        // REVISI KRITIS 2: Tambahkan Filter Pencarian (FIX Ambiguous Column)
        if ($searchQuery) {
            $query->where(function (Builder $q) use ($searchQuery) {
                // Cari di Nama Toko, PIC
                $q->where('store_name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('pic_name', 'like', '%' . $searchQuery . '%')
                  
                  // Cari di Lokasi (HARUS menggunakan sellers.kolom untuk mengatasi error HY000)
                  ->orWhere('sellers.province', 'like', '%' . $searchQuery . '%')
                  ->orWhere('sellers.city', 'like', '%' . $searchQuery . '%')
                  ->orWhere('sellers.district', 'like', '%' . $searchQuery . '%');
            });
        }
            
        // Eksekusi Query dan Pengurutan
        $allSellers = $query->orderBy('sub.count_province', 'desc') 
            ->orderBy('sellers.province') 
            ->orderBy('sellers.store_name')
            ->get();
            
        // 2. Kelompokkan data untuk View HTML (Tabular) dan Ringkasan
        $groupedSellers = $allSellers->groupBy('province');

        // Untuk tampilan ringkas (ringkasan total di View HTML)
        $byProvince = $allSellers->groupBy('province')->map(function ($group) {
            $province = $group->first()->province ?? 'Tidak Diketahui';
            return (object)['total' => $group->count(), 'province' => $province];
        })->sortByDesc('total');

        return view('platform.reports.sellers_by_province', compact('allSellers', 'byProvince', 'groupedSellers', 'searchQuery'));
    }

    /**
     * Laporan Produk Berdasarkan Rating (SRS-MartPlace-11)
     */
    public function productsByRating(Request $request)
    {
        $searchQuery = trim($request->get('search')); // FIX KRITIS 3: Tambahkan trim()
        $searchLower = strtolower($searchQuery); // Ubah ke lowercase untuk pencarian yang konsisten

        $query = Product::with(['seller', 'category']) 
            ->withAvg('reviews', 'rating') 
            ->orderByDesc('reviews_avg_rating');
            
        // REVISI KRITIS 4: Tambahkan Filter Pencarian (Memaksa Lowercase Match)
        if ($searchQuery) {
            $query->where(function (Builder $q) use ($searchLower) {
                // Cari di Nama Produk atau SKU (Menggunakan whereRaw untuk LOWERCASE comparison)
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%'])
                  ->orWhereRaw('LOWER(sku) LIKE ?', ['%' . $searchLower . '%'])
                  
                  // Cari di Nama Toko (Seller) - Juga harus di lowercase
                  ->orWhereHas('seller', function ($sq) use ($searchLower) {
                      $sq->whereRaw('LOWER(store_name) LIKE ?', ['%' . $searchLower . '%']);
                  });
            });
        }
            
        $products = $query->paginate(15)->withQueryString();
        
        return view('platform.reports.products_by_rating', compact('products', 'searchQuery'));
    }

    /**
     * Export platform reports to PDF (SRS-MartPlace-09, 10, 11)
     */
    public function exportPdf($type)
    {
        // Logika Export PDF (Mengambil semua data, tanpa filter 'search' dari URL)
        
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
                $allSellers = Seller::select('sellers.store_name', 'sellers.pic_name', 'sellers.province', 'sellers.status')
                    ->leftJoin(DB::raw('(' . $subQuery->toSql() . ') as sub'), 'sellers.province', '=', 'sub.province')
                    ->mergeBindings($subQuery->getQuery()) 
                    ->orderBy('sub.count_province', 'desc') 
                    ->orderBy('sellers.province') 
                    ->orderBy('sellers.store_name')
                    ->get();
                
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