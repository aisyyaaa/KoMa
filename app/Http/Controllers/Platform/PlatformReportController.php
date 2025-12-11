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
        // ... (Logika SRS-09 tidak diubah) ...
        $active = Seller::where('status', 'ACTIVE')->count();
        $inactive = Seller::where('status', 'REJECTED')->count();
        $total = $active + $inactive;

        $sellers = Seller::select('id', 'store_name', 'pic_name', 'pic_email', 'pic_phone', 'status', 'updated_at')
            ->withCount('products')
            ->orderBy('store_name')
            ->get()
            ->map(function ($s) {
                if (strtoupper($s->status) !== 'ACTIVE') {
                    $s->last_active = now()->subMonth();
                } else {
                    $s->last_active = $s->updated_at;
                }
                return $s;
            });

        return view('platform.reports.active_sellers', compact('active', 'inactive', 'total', 'sellers'));
    }

    /**
     * Laporan Penjual Berdasarkan Provinsi (SRS-MartPlace-10)
     */
    public function sellersByProvince()
    {
        // ... (Logika SRS-10 tidak diubah) ...
        $byProvince = Seller::select('pic_province', \DB::raw('count(*) as total'))
            ->groupBy('pic_province')
            ->orderByDesc('total')
            ->get();
        return view('platform.reports.sellers_by_province', compact('byProvince'));
    }

    /**
     * Laporan Produk Berdasarkan Rating (SRS-MartPlace-11)
     */
    public function productsByRating()
    {
        // ... (Logika SRS-11 View HTML tidak diubah) ...
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

        $pemroses = User::where('role', 'platform')->first(); 
        $data['pemroses'] = $pemroses;
        $data['date'] = now()->format('d-m-Y');

        try {
            if ($type === 'seller-accounts') {
                $data['active'] = Seller::where('status', 'ACTIVE')->count();
                $data['inactive'] = Seller::where('status', 'REJECTED')->count();
                $data['sellers'] = Seller::select('store_name', 'pic_name', 'pic_email', 'pic_phone', 'status', 'updated_at')
                    ->withCount('products')->orderBy('store_name')->get();
                // Mengubah path view: platform.reports.pdf.seller-accounts-pdf
                $view = 'platform.reports.pdf.seller_accounts_pdf'; 
                $filename = 'seller-accounts.pdf';
            } elseif ($type === 'seller-locations') {
                $data['byProvince'] = Seller::select('pic_province', \DB::raw('count(*) as total'))
                    ->groupBy('pic_province')->orderByDesc('total')->get();
                // Mengubah path view: platform.reports.pdf.seller-locations-pdf
                $view = 'platform.reports.pdf.seller_locations_pdf'; 
                $filename = 'seller-locations.pdf';
            } elseif ($type === 'products_by_rating') { // SRS-11
                $data['products'] = Product::with(['seller', 'category'])
                    ->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->get();

                // PERUBAHAN KRITIS SRS-11: Mengubah path view untuk menghindari konflik
                // Hati-hati dengan tanda hubung, gunakan underscore:
                $view = 'platform.reports.pdf.products_by_rating'; 
                $filename = 'product-ratings.pdf';
                
            } else {
                return back()->with('error', 'Tipe laporan tidak dikenal: ' . $type);
            }

            // ... (Kode DomPDF tetap sama) ...
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
            // MENGEMBALIKAN ERROR LENGKAP AGAR KITA BISA LIHAT ROOT EXCEPTIONNYA
            return back()->with('error', 'Terjadi kesalahan saat membuat PDF: ' . $e->getMessage());
        }
    }
}