<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Support\Facades\DB; 

class PlatformAnalyticsController extends Controller
{
    /**
     * Menghitung sebaran jumlah produk per kategori untuk grafik. (SRS-MartPlace-07)
     */
    public function productsPerCategory()
    {
        $rows = Category::leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.name', DB::raw('count(products.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get(); 

        return response()->json([
            'labels' => $rows->pluck('name'),
            'datasets' => [ 
                [
                    'label' => 'Jumlah Produk',
                    'data' => $rows->pluck('total'),
                ]
            ]
        ]);
    }

    /**
     * Menghitung sebaran jumlah penjual per provinsi (SEMUA SELLER). (SRS-MartPlace-07)
     */
    public function sellersPerProvince()
    {
        // Menggunakan kolom 'province' (sesuai migrasi Seller)
        $rows = Seller::select('province', DB::raw('count(*) as total'))
            // REVISI KRITIS: BARIS INI DIHAPUS agar menghitung semua penjual (ACTIVE, PENDING, REJECTED)
            // ->where('status', 'ACTIVE') 
            ->groupBy('province')
            ->whereNotNull('province') 
            ->where('province', '!=', '') 
            ->orderByDesc('total')
            ->get(); 

        return response()->json([
            'labels' => $rows->pluck('province'),
            'datasets' => [ 
                [
                    'label' => 'Total Jumlah Toko (Aktif, Tidak Aktif)', // Label diubah
                    'data' => $rows->pluck('total'),
                ]
            ]
        ]);
    }

    /**
     * Mengambil statistik ringkas (total penjual, aktif, tidak aktif, komentator). (SRS-MartPlace-07)
     */
    public function stats()
    {
        // Statistik Penjual
        $total = Seller::count();
        $active = Seller::where('status', 'ACTIVE')->count();
        $rejected = Seller::where('status', 'REJECTED')->count();
        $pending = Seller::where('status', 'PENDING')->count();
        
        // Penjual Tidak Aktif = Rejected + Pending (yang is_active=false)
        $inactive = Seller::where('is_active', false)
                         ->whereNotIn('status', ['ACTIVE'])
                         ->count();
        
        // Komentator unik
        $commenters = Review::distinct('visitor_email')->count('visitor_email'); 

        return response()->json([
            // Output ini digunakan oleh platform.dashboard.index jika memanggil AJAX
            'total_sellers' => $total,
            'active_sellers' => $active,
            'inactive_sellers' => $inactive, 
            'rejected_sellers' => $rejected, // Tambahkan rejected untuk display di card
            'pending_sellers' => $pending, // Tambahkan pending untuk display di card
            'commenters' => $commenters,
        ]);
    }
}