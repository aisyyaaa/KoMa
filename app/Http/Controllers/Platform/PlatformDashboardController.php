<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Seller; 
use App\Models\Product; 
use App\Models\Review; 
use Illuminate\Http\Request;

class PlatformDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Platform dengan metrik utama (SRS-MartPlace-07).
     */
    public function index()
    {
        // 1. Ambil Metrik Utama dari Database
        $totalSellers = Seller::count();
        $activeSellers = Seller::where('status', 'ACTIVE')->count();
        $pendingSellersCount = Seller::where('status', 'PENDING')->count();
        
        // Menghitung Penjual Tidak Aktif (REJECTED atau is_active=false & belum ACTIVE)
        $inactiveSellers = Seller::where('is_active', false)
                                 ->where('status', '!=', 'ACTIVE')
                                 ->count();
        
        $totalProducts = Product::count();
        // Menghitung jumlah komentator unik (yang mengisi visitor_email)
        $uniqueReviewers = Review::distinct('visitor_email')->count('visitor_email'); 

        // 2. Kumpulkan data metrik ke dalam satu array
        $stats = [
            'total_sellers' => $totalSellers,
            'active_sellers' => $activeSellers,
            'pending_count' => $pendingSellersCount,
            'inactive_sellers' => $inactiveSellers, // Penjual Tidak Aktif
            'total_products' => $totalProducts,
            'unique_reviewers' => $uniqueReviewers,
        ];
        
        // 3. Kirim data metrik ke View
        return view('platform.dashboard.index', $stats);
    }
}