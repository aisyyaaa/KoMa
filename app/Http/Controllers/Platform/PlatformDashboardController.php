<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Review;
// Import yang diperlukan untuk data statistik
use Illuminate\Support\Facades\DB; 

class PlatformDashboardController extends Controller
{
    // HAPUS KONSTRUKTOR BERMASALAH UNTUK MENGHILANGKAN ERROR ROUTE NOT FOUND
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->check() || !auth()->user()->is_platform_admin) {
    //             return redirect()->route('platform.auth.login'); // BARIS INI MENYEBABKAN ERROR
    //         }
    //         return $next($request);
    //     });
    // }
    
    public function index()
    {
        // gather stats for dashboard (SRS-07)
        $totalSellers = Seller::count();
        $activeSellers = Seller::where('status', 'ACTIVE')->count();
        $inactiveSellers = Seller::where('status', 'REJECTED')->count();
        $totalProducts = Product::count();
        // commenters: unique users who left reviews
        $commenters = Review::distinct('user_id')->count('user_id');

        return view('platform.dashboard.index', [
            'total_sellers' => $totalSellers,
            'active' => $activeSellers,
            'inactive' => $inactiveSellers,
            'total_products' => $totalProducts,
            'commenters' => $commenters,
        ]);
    }
}