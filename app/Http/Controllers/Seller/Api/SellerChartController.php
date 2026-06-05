<?php

namespace App\Http\Controllers\Seller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerChartController extends Controller
{
    // Helper function untuk mendapatkan Seller ID yang sedang login
    private function getSellerId()
    {
        // Gunakan guard('seller') dan ambil ID.
        $seller = Auth::guard('seller')->user();
        return $seller ? $seller->id : 1; 
    }
    
    /**
     * SRS-08: Informasi sebaran jumlah stok setiap produk (Grafik 1)
     */
    public function stockPerProduct()
    {
        $sellerId = $this->getSellerId();
        
        // Query: Ambil 10 produk dengan stok tertinggi (diurutkan menurun)
        $products = \App\Models\Product::where('seller_id', $sellerId)
            ->orderByDesc('stock')
            ->limit(10)
            ->pluck('stock', 'name');

        $labels = $products->keys()->values();
        $data = $products->values()->map(function ($v) { return (int) $v; })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * SRS-08: Informasi sebaran nilai rating per produk (Grafik 2)
     */
    public function ratingPerProduct()
    {
        $sellerId = $this->getSellerId();
        
        $products = \App\Models\Product::where('seller_id', $sellerId)
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->limit(10)
            ->get();

        $labels = $products->pluck('name')->values();
        
        $data = $products->map(function ($product) {
            return round($product->reviews_avg_rating ?? 0, 1); 
        })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * SRS-08: Informasi sebaran pemberi rating berdasarkan Lokasi propinsi (Grafik 3)
     */
    public function ratingByProvince()
    {
        $sellerId = $this->getSellerId();
        
        $productIds = \App\Models\Product::where('seller_id', $sellerId)->pluck('id');

        // PERBAIKAN KRITIS: MENGHAPUS JOIN KE TABEL USERS dan mengambil PROVINCE dari tabel REVIEWS
        $ratingsByProvince = DB::table('reviews')
            ->whereIn('reviews.product_id', $productIds)
            // Filter hanya review yang memiliki data province di kolom reviews
            ->whereNotNull('province') 
            ->where('province', '!=', '') 
            ->select('province', DB::raw('COUNT(id) as total_ratings')) // Hitung TOTAL REVIEW
            ->groupBy('province')
            ->orderByDesc('total_ratings')
            ->limit(10)
            ->get();
        
        $labels = $ratingsByProvince->pluck('province')->values();
        $data = $ratingsByProvince->pluck('total_ratings')->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}