<?php

use App\Http\Controllers\CatalogController; 
use App\Http\Controllers\Review\ReviewController; 
// --- AUTH CONTROLLERS (App\Http\Controllers\Auth) ---
use App\Http\Controllers\Auth\SellerAuthController; 
use App\Http\Controllers\Auth\SellerVerificationController as SellerVerify;
use App\Http\Controllers\Auth\AuthController; 

// --- SELLER CONTROLLERS (App\Http\Controllers\Seller) ---
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerOrderController; 
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerReportController;
use App\Http\Controllers\Seller\SellerReviewController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Seller\Api\SellerChartController;
use App\Http\Controllers\Seller\Api\SellerDataController;

// --- PLATFORM CONTROLLERS (App\Http\Controllers\Platform) ---
use App\Http\Controllers\Platform\PlatformDashboardController; 
use App\Http\Controllers\Platform\PlatformAnalyticsController; 
use App\Http\Controllers\Platform\PlatformReportController; 
use App\Http\Controllers\Platform\SellerVerificationController as PlatformSellerVerify;

// --- LARAVEL FACADES ---
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Public Routes (KoMa Market Place)
|--------------------------------------------------------------------------
*/

// --- 1. Katalog Produk (SRS-MartPlace-04, SRS-MartPlace-05, SRS-MartPlace-06) ---
Route::get('/', [CatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog.index.alt'); 
Route::get('/katalog/autocomplete', [CatalogController::class, 'autocomplete'])->name('katalog.autocomplete');
Route::get('/katalog/{product:slug}', [CatalogController::class, 'show'])->name('katalog.show'); 
// Pemberian Komentar dan Rating (SRS-MartPlace-06)
Route::post('/katalog/{product}/review', [ReviewController::class, 'store'])->name('katalog.review.store');

// --- 2. Global Authentication (Login/Logout, Pendaftaran KHUSUS Penjual) ---
Route::group([], function () {
    // Login Global
    Route::get('login', [AuthController::class, 'showLoginSelection'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('katalog.index'); 
    })->name('logout');
    
    // Pendaftaran Penjual (SRS-MartPlace-01)
    Route::get('/register', [SellerAuthController::class, 'showRegister'])->name('seller.register');
    Route::post('/register', [SellerAuthController::class, 'register'])->name('seller.store');
});


/*
|--------------------------------------------------------------------------
| Seller Area: AUTHENTICATION & VERIFICATION
|--------------------------------------------------------------------------
*/
Route::prefix('seller/auth')->name('seller.auth.')->group(function () {
    // Login Penjual
    Route::get('login', [SellerAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [SellerAuthController::class, 'login'])->name('login.post');

    // Verification Penjual (SRS-MartPlace-02)
    Route::get('verify', [SellerVerify::class, 'show'])->name('verify');
});


/*
|--------------------------------------------------------------------------
| Protected Seller Area (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:seller'])->prefix('seller')->name('seller.')->group(function () {
    
    // Dashboard (SRS-MartPlace-08)
    Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    
    // Produk (SRS-MartPlace-03)
    Route::resource('products', SellerProductController::class);

    // Order (Opsional: Jika ada Order Process)
    Route::get('orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::post('orders/{order}/status', [SellerOrderController::class, 'update'])->name('orders.update');

    // Review/Rating (Melihat Review Produknya)
    Route::get('reviews', [SellerReviewController::class, 'index'])->name('reviews.index');

    // Profile (Mengelola data toko)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('{seller}', [SellerProfileController::class, 'show'])->name('show');
        Route::get('{seller}/edit', [SellerProfileController::class, 'edit'])->name('edit');
        Route::put('{seller}', [SellerProfileController::class, 'update'])->name('update');
    });

    // Laporan (SRS-MartPlace-12, 13, 14)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('', function () { return redirect()->route('seller.reports.stock_by_quantity'); })->name('index');
        Route::get('stock-by-quantity', [SellerReportController::class, 'stockByQuantity'])->name('stock_by_quantity');
        Route::get('stock-by-rating', [SellerReportController::class, 'stockByRating'])->name('stock_by_rating');
        Route::get('low-stock', [SellerReportController::class, 'lowStock'])->name('low_stock');
        Route::get('export/{type}', [SellerReportController::class, 'exportPdf'])->name('export.pdf');
    });

    // API endpoints (untuk Dashboard Charts/Data)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('charts/stock-per-product', [SellerChartController::class, 'stockPerProduct'])->name('charts.stock_per_product'); 
        Route::get('charts/rating-per-product', [SellerChartController::class, 'ratingPerProduct'])->name('charts.rating_per_product'); 
        Route::get('charts/rating-by-province', [SellerChartController::class, 'ratingByProvince'])->name('charts.rating_by_province'); 

        Route::get('data/products-summary', [SellerDataController::class, 'productsSummary'])->name('data.products_summary');
        Route::get('data/reviews-summary', [SellerDataController::class, 'reviewsSummary'])->name('data.reviews_summary');
    });
});


/*
|--------------------------------------------------------------------------
| Platform Admin Area (TANPA AUTH UNTUK TESTING)
|--------------------------------------------------------------------------
*/
Route::prefix('platform')->name('platform.')->group(function () {

    // Dashboard (SRS-MartPlace-07)
    Route::get('dashboard', [PlatformDashboardController::class, 'index'])->name('dashboard');

    // Verifikasi Penjual (SRS-MartPlace-02)
    Route::prefix('verifications/sellers')->name('verifications.sellers.')->group(function () {
        Route::get('', [PlatformSellerVerify::class, 'index'])->name('index');
        Route::get('{seller}', [PlatformSellerVerify::class, 'show'])->name('show');
        Route::post('{seller}/approve', [PlatformSellerVerify::class, 'approve'])->name('approve');
        Route::post('{seller}/reject', [PlatformSellerVerify::class, 'reject'])->name('reject');
        
        Route::patch('{seller}/status', [PlatformSellerVerify::class, 'updateStatus'])->name('status'); 
        Route::post('{seller}/notify', [PlatformSellerVerify::class, 'sendActivationEmail'])->name('notify'); 
    });

    // Laporan (SRS-MartPlace-09, 10, 11)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('active-sellers', [PlatformReportController::class, 'activeSellers'])->name('active_sellers');
        Route::get('sellers-by-province', [PlatformReportController::class, 'sellersByProvince'])->name('sellers_by_province');
        Route::get('products-by-rating', [PlatformReportController::class, 'productsByRating'])->name('products_by_rating');
        Route::get('export/{type}', [PlatformReportController::class, 'exportPdf'])->name('export');
    });

    // Analytics API for charts (SRS-MartPlace-07)
    // FIX KRITIS: Menyederhanakan routing untuk menghindari RouteNotFoundException
    Route::prefix('api')->name('api.')->group(function () {
        
        Route::get('stats', [PlatformAnalyticsController::class, 'stats'])->name('stats'); 
        
        // Menggunakan nama rute tunggal untuk memanggil API
        Route::get('charts/products-per-category', [PlatformAnalyticsController::class, 'productsPerCategory'])->name('products_per_category'); // platform.api.products_per_category
        Route::get('charts/sellers-per-province', [PlatformAnalyticsController::class, 'sellersPerProvince'])->name('sellers_per_province'); // platform.api.sellers_per_province
        
    });
});