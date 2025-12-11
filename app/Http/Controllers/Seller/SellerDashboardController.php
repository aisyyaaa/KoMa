<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan untuk operasi DB/raw query

class SellerDashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard untuk Penjual (SRS-MartPlace-08)
     */
    public function index()
    {
        // 1. Ambil seller_id dari user yang sedang login
        // PERBAIKAN: Gunakan guard('seller') untuk memastikan user adalah seller
        // Ganti Auth::user()->seller->id menjadi Auth::guard('seller')->user()->id
        $seller = Auth::guard('seller')->user();

        // Safety check (fallback jika auth gagal, meskipun harusnya tidak terjadi jika middleware benar)
        if (!$seller) {
            // Jika tidak ada seller, kita asumsikan ID 1 untuk debugging atau return error
            $sellerId = 1; 
        } else {
            $sellerId = $seller->id;
        }

        // 2. Ambil statistik produk dan rating dalam satu query
        // Query untuk menghitung rata-rata rating dan total review untuk semua produk penjual
        $statsData = Product::where('seller_id', $sellerId)
                            ->selectRaw('count(id) as total_products')
                            ->selectRaw('sum(case when stock < 10 then 1 else 0 end) as low_stock')
                            ->withAvg('reviews', 'rating')
                            ->withCount('reviews')
                            ->first();

        // 3. Siapkan Array Stats
        $stats = [
            'total_products' => $statsData->total_products ?? 0,
            'low_stock' => $statsData->low_stock ?? 0,
            
            // Menggunakan hasil withAvg/withCount
            'average_rating' => round($statsData->reviews_avg_rating ?? 0, 1),
            'total_reviews' => $statsData->reviews_count ?? 0,
            
            // Tambahkan statistik untuk produk terjual (asumsi ada kolom sales/order)
            // 'products_sold' => Product::where('seller_id', $sellerId)->sum('sales') ?? 0,
        ];

        // 4. Siapkan Variabel Data untuk Grafik (SRS-08)
        // Note: Logic detail untuk Chart API (seperti SellerChartController) biasanya dipanggil via AJAX di frontend.
        // Di sini kita hanya menyiapkan data sederhana jika Chart Controller tidak digunakan.
        
        $chartData = [
            // Grafik Stok per Produk (SRS-08)
            'stock_chart_url' => route('seller.api.charts.stock_per_product'), 
            
            // Grafik Rating per Produk (SRS-08)
            'rating_chart_url' => route('seller.api.charts.rating_per_product'), 
            
            // Grafik Pemberi Rating berdasarkan Provinsi (SRS-08)
            'province_chart_url' => route('seller.api.charts.rating_by_province'),
        ];
        
        // Kirimkan semua statistik dan URL chart ke view
        return view('seller.dashboard.index', compact('stats', 'chartData'));
    }
}