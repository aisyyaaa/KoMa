<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\Seller\Auth\SellerAuthController as SellerAuth;
use App\Http\Controllers\Seller\Auth\SellerVerificationController as SellerVerify;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerReportController;
use App\Http\Controllers\Seller\SellerReviewController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Seller\Api\SellerChartController;
use App\Http\Controllers\Seller\Api\SellerDataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (KoMa Market Place)
|--------------------------------------------------------------------------
| Rute yang dapat diakses oleh Pengunjung Umum
*/

// 1. Halaman Utama / Katalog Produk (Sesuai instruksi dosen)
// Tampilan depan web langsung katalog.
Route::get('/', [CatalogController::class, 'index'])->name('katalog.index');
// 2. Registrasi Penjual (SRS-MartPlace-01)
// Menampilkan formulir registrasi penjual (GET)
Route::get('/register/seller', [SellerController::class, 'create'])->name('seller.register');
// Memproses data formulir registrasi (POST)
Route::post('/register/seller', [SellerController::class, 'store'])->name('seller.store');

/*
|--------------------------------------------------------------------------
| Seller Area (Auth + Dashboard + Products + Reports)
|--------------------------------------------------------------------------
*/

// Seller authentication (register/login)
Route::get('seller/register', [SellerAuth::class, 'showRegister'])->name('seller.auth.register');
Route::post('seller/register', [SellerAuth::class, 'register'])->name('seller.auth.register.post');
Route::get('seller/login', [SellerAuth::class, 'showLogin'])->name('seller.auth.login');
Route::post('seller/login', [SellerAuth::class, 'login'])->name('seller.auth.login.post');
Route::post('seller/logout', [SellerAuth::class, 'logout'])->name('seller.auth.logout');

// Verification (optional)
Route::get('seller/verify', [SellerVerify::class, 'show'])->name('seller.auth.verify');
Route::post('seller/verify', [SellerVerify::class, 'verify'])->name('seller.auth.verify.post');
Route::post('seller/verify/resend', [SellerVerify::class, 'resend'])->name('seller.auth.verify.resend');

// Protected seller routes
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Products (resource-like)
    Route::get('products', [SellerProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [SellerProductController::class, 'create'])->name('products.create');
    Route::post('products', [SellerProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [SellerProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [SellerProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [SellerProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');

    // Reports
    Route::get('reports/stock-by-quantity', [SellerReportController::class, 'stockByQuantity'])->name('reports.stock_by_quantity');
    Route::get('reports/stock-by-rating', [SellerReportController::class, 'stockByRating'])->name('reports.stock_by_rating');
    Route::get('reports/low-stock', [SellerReportController::class, 'lowStock'])->name('reports.low_stock');
    Route::get('reports/export/{type}', [SellerReportController::class, 'exportPdf'])->name('reports.export');

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
| Authenticated Routes (DINONAKTIFKAN SEMENTARA)
|--------------------------------------------------------------------------
| Ini adalah rute bawaan untuk Dashboard dan Profile.
*/

/*
// Rute Dashboard (Bawaan) - Nonaktifkan sementara
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profile (Bawaan) - Nonaktifkan sementara
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

// Rute Autentikasi Bawaan (Login, Register User Biasa, Reset Password, dll.) - Nonaktifkan sementara
// require __DIR__.'/auth.php';