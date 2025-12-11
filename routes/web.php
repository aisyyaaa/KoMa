// File: routes/web.php (MERGE FINAL - Menggabungkan Katalog dan Platform Admin)

<?php

use App\Http\Controllers\CatalogController; // <-- Dipertahankan (Katalog Anda)
use App\Http\Controllers\Review\ReviewController; // <-- Dipertahankan (Review Anda, dengan Namespace yang benar)
use App\Http\Controllers\LandingPageController; // <-- Ditambahkan dari Incoming
use App\Http\Controllers\Seller\Auth\SellerAuthController;
use App\Http\Controllers\Seller\Auth\SellerVerificationController as SellerVerify;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerReportController;
use App\Http\Controllers\Seller\SellerReviewController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Seller\Api\SellerChartController;
use App\Http\Controllers\Seller\Api\SellerDataController;
use App\Http\Controllers\Auth\AuthController; // <-- Ditambahkan (Login/Logout Global)
use App\Http\Controllers\Platform\PlatformDashboardController; // <-- Ditambahkan (Platform Admin)
use App\Http\Controllers\Platform\PlatformAnalyticsController; // <-- Ditambahkan
use App\Http\Controllers\Platform\PlatformReportController; // <-- Ditambahkan
use App\Http\Controllers\Platform\Auth\PlatformAuthController; // <-- Ditambahkan
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes (KoMa Market Place)
|--------------------------------------------------------------------------
| Rute yang dapat diakses oleh Pengunjung Umum
*/

// 1. Halaman Utama / Landing Page (Menggantikan Katalog di root)
Route::get('/', [LandingPageController::class, 'index'])->name('landingpage.index');

// 2. Rute Katalog Anda (Dipindahkan dari root, sekarang ada di /katalog)
// Halaman Utama Katalog Produk (SRS-MartPlace-04)
Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog.index');

// Detail Produk (SRS-MartPlace-04)
Route::get('/katalog/{product:slug}', [CatalogController::class, 'show'])->name('katalog.show'); 
// Menggunakan product:slug di sini agar URL terlihat bagus dan sesuai dengan seeder

// Pemberian Komentar dan Rating (SRS-MartPlace-06)
Route::post('/katalog/{product}/review', [ReviewController::class, 'store'])->name('review.store');

// 3. Registrasi Penjual (SRS-MartPlace-01)
Route::get('/register/seller', [SellerAuthController::class, 'showRegister'])->name('seller.register');
Route::post('/register/seller', [SellerAuthController::class, 'register'])->name('seller.store');

// 4. Registrasi Pembeli (Baru dari Incoming)
Route::get('buyer/register', [AuthController::class, 'showBuyerRegister'])->name('buyer.register');
Route::post('buyer/register', [AuthController::class, 'registerBuyer'])->name('buyer.register.post');

// 5. Global Login/Logout (Pilihan akun)
Route::get('login', [AuthController::class, 'showLoginSelection'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


/*
|--------------------------------------------------------------------------
| Seller Area (AUTH & PUBLIC ACCESS FOR DEV)
|--------------------------------------------------------------------------
*/

// Seller authentication
Route::get('seller/register', [SellerAuthController::class, 'showRegister'])->name('seller.auth.register');
Route::post('seller/register', [SellerAuthController::class, 'register'])->name('seller.auth.register.post');
Route::get('seller/login', [SellerAuthController::class, 'showLogin'])->name('seller.auth.login');
Route::post('seller/login', [SellerAuthController::class, 'login'])->name('seller.auth.login.post');
// Verification (optional)
Route::get('seller/verify', [SellerVerify::class, 'show'])->name('seller.auth.verify');
Route::post('seller/verify', [SellerVerify::class, 'verify'])->name('seller.auth.verify.post');
Route::post('seller/verify/resend', [SellerVerify::class, 'resend'])->name('seller.auth.verify.resend');

// TEMPORARY: Public access for DEV (Dashboard, Products, Reports)
Route::get(uri: 'seller/dashboard', action: [SellerDashboardController::class, 'index'])->name('seller.dashboard');
Route::get('seller/orders', [SellerOrderController::class, 'index'])->name('seller.orders.index');
Route::post('seller/orders/{order}/status', [SellerOrderController::class, 'update'])->name('seller.orders.update');


// Seller Reports (Public for DEV, as in your current code)
Route::get('seller/reports/stock-by-quantity', [SellerReportController::class, 'stockByQuantity'])->name('seller.reports.stock_by_quantity');
Route::get('seller/reports/stock-by-rating', [SellerReportController::class, 'stockByRating'])->name('seller.reports.stock_by_rating');
Route::get('seller/reports/low-stock', [SellerReportController::class, 'lowStock'])->name('seller.reports.low_stock');
Route::get('seller/reports/export/{type}', [SellerReportController::class, 'exportPdf'])->name('seller.reports.export.pdf');
Route::get('seller/reports', function () {
    return redirect()->route('seller.reports.stock_by_quantity');
})->name('seller.reports.index');

// Seller Products CRUD (Public for DEV)
Route::prefix('seller/products')->name('seller.products.')->group(function () {
    Route::get('', [SellerProductController::class, 'index'])->name('index');
    Route::get('create', [SellerProductController::class, 'create'])->name('create');
    Route::post('', [SellerProductController::class, 'store'])->name('store');
    Route::get('{product}', [SellerProductController::class, 'show'])->name('show');
    Route::get('{product}/edit', [SellerProductController::class, 'edit'])->name('edit');
    Route::put('{product}', [SellerProductController::class, 'update'])->name('update');
    Route::delete('{product}', [SellerProductController::class, 'destroy'])->name('destroy');
});


/*
|--------------------------------------------------------------------------
| Protected Seller Routes (Requires Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    // Reviews
    Route::get('reviews', [SellerReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews', [SellerReviewController::class, 'store'])->name('reviews.store');
    Route::delete('reviews/{review}', [SellerReviewController::class, 'destroy'])->name('reviews.destroy');

    // Profile
    Route::get('profile/{seller}', [SellerProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/{seller}/edit', [SellerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{seller}', [SellerProfileController::class, 'update'])->name('profile.update');
});

// Seller API endpoints (used by charts)
Route::prefix('seller/api')->name('seller.api.')->group(function () {
    Route::get('charts/stock-per-product', [SellerChartController::class, 'stockPerProduct'])->name('charts.stock_per_product');
    Route::get('charts/rating-per-product', [SellerChartController::class, 'ratingPerProduct'])->name('charts.rating_per_product');
    Route::get('charts/rating-by-province', [SellerChartController::class, 'ratingByProvince'])->name('charts.rating_by_province');

    Route::get('data/products-summary', [SellerDataController::class, 'productsSummary'])->name('data.products_summary');
    Route::get('data/reviews-summary', [SellerDataController::class, 'reviewsSummary'])->name('data.reviews_summary');
});


/*
|--------------------------------------------------------------------------
| Platform Admin Area (Baru dari Incoming)
|--------------------------------------------------------------------------
*/
Route::prefix('platform')->name('platform.')->group(function () {
    // Platform authentication
    Route::get('auth/login', [PlatformAuthController::class, 'showLogin'])->name('auth.login');
    Route::post('auth/login', [PlatformAuthController::class, 'login'])->name('auth.login.post');
    Route::post('auth/logout', [PlatformAuthController::class, 'logout'])->name('auth.logout');

    Route::get('dashboard', [PlatformDashboardController::class, 'index'])->name('dashboard');

    // Analytics API for charts
    Route::get('api/charts/products-per-category', [PlatformAnalyticsController::class, 'productsPerCategory'])->name('api.products_per_category');
    Route::get('api/charts/sellers-per-province', [PlatformAnalyticsController::class, 'sellersPerProvince'])->name('api.sellers_per_province');
    Route::get('api/stats', [PlatformAnalyticsController::class, 'stats'])->name('api.stats');

    // Reports
    Route::get('reports/active-sellers', [PlatformReportController::class, 'activeSellers'])->name('reports.active_sellers');
    Route::get('reports/sellers-by-province', [PlatformReportController::class, 'sellersByProvince'])->name('reports.sellers_by_province');
    Route::get('reports/products-by-rating', [PlatformReportController::class, 'productsByRating'])->name('reports.products_by_rating');
    Route::get('reports/export/{type}', [PlatformReportController::class, 'exportPdf'])->name('reports.export');

    // Seller verification (platform admin)
    Route::get('verifications/sellers', [\App\Http\Controllers\Platform\SellerVerificationController::class, 'index'])->name('verifications.sellers.index');
    Route::get('verifications/sellers/{seller}', [\App\Http\Controllers\Platform\SellerVerificationController::class, 'show'])->name('verifications.sellers.show');
    Route::post('verifications/sellers/{seller}/approve', [\App\Http\Controllers\Platform\SellerVerificationController::class, 'approve'])->name('verifications.sellers.approve');
    Route::post('verifications/sellers/{seller}/reject', [\App\Http\Controllers\Platform\SellerVerificationController::class, 'reject'])->name('verifications.sellers.reject');
});
