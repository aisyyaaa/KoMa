<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Seller\Auth\SellerAuthController;
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
Route::get('/register/seller', [SellerAuthController::class, 'showRegister'])->name('seller.register');
// Memproses data formulir registrasi (POST)
Route::post('/register/seller', [SellerAuthController::class, 'register'])->name('seller.store');

/*
|--------------------------------------------------------------------------
| Seller Area (Auth + Dashboard + Products + Reports)
|--------------------------------------------------------------------------
*/

// Seller authentication (register/login)
Route::get('seller/register', [SellerAuthController::class, 'showRegister'])->name('seller.auth.register');
Route::post('seller/register', [SellerAuthController::class, 'register'])->name('seller.auth.register.post');
Route::get('seller/login', [SellerAuthController::class, 'showLogin'])->name('seller.auth.login');
Route::post('seller/login', [SellerAuthController::class, 'login'])->name('seller.auth.login.post');
Route::post('seller/logout', [SellerAuthController::class, 'logout'])->name('seller.auth.logout');
// Verification (optional)
Route::get('seller/verify', [SellerVerify::class, 'show'])->name('seller.auth.verify');
Route::post('seller/verify', [SellerVerify::class, 'verify'])->name('seller.auth.verify.post');
Route::post('seller/verify/resend', [SellerVerify::class, 'resend'])->name('seller.auth.verify.resend');

// TEMPORARY: Seller Dashboard (akses tanpa login untuk development)
Route::get(uri: 'seller/dashboard', action: [SellerDashboardController::class, 'index'])->name('seller.dashboard');

// Dummy login route untuk development
Route::get('login', function () {
    return redirect('seller/login');
})->name('login');

// TEMPORARY: placeholder routes for development (to avoid missing route errors)
Route::get('seller/orders', function () {
    return view('seller.placeholder', ['title' => 'Pesanan']);
})->name('seller.orders.index');

// TEMPORARY: placeholder for reports index (used by dashboard links)
// Route::get('seller/reports', function () {
//     return view('seller.placeholder', ['title' => 'Laporan']);
// })->name('seller.reports.index');

// Reports
Route::get('seller/reports/stock-by-quantity', [SellerReportController::class, 'stockByQuantity'])->name('seller.reports.stock_by_quantity');
Route::get('seller/reports/stock-by-rating', [SellerReportController::class, 'stockByRating'])->name('seller.reports.stock_by_rating');
Route::get('seller/reports/low-stock', [SellerReportController::class, 'lowStock'])->name('seller.reports.low_stock');
Route::get('seller/reports/export/{type}', [SellerReportController::class, 'exportPdf'])->name('seller.reports.export.pdf');

// Public index route for reports (development): redirect to the first report
Route::get('seller/reports', function () {
    return redirect()->route('seller.reports.stock_by_quantity');
})->name('seller.reports.index');

// TEMPORARY: Seller Products routes (akses tanpa login untuk development)
Route::prefix('seller/products')->name('seller.products.')->group(function () {
    Route::get('', [SellerProductController::class, 'index'])->name('index');
    Route::get('create', [SellerProductController::class, 'create'])->name('create');
    Route::post('', [SellerProductController::class, 'store'])->name('store');
    Route::get('{product}', [SellerProductController::class, 'show'])->name('show');
    Route::get('{product}/edit', [SellerProductController::class, 'edit'])->name('edit');
    Route::put('{product}', [SellerProductController::class, 'update'])->name('update');
    Route::delete('{product}', [SellerProductController::class, 'destroy'])->name('destroy');
});

// Protected seller routes
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard sudah di-define di atas, jadi hanya route lain yang protected

    // Reviews
    Route::get('reviews', [SellerReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews', [SellerReviewController::class, 'store'])->name('reviews.store');
    Route::delete('reviews/{review}', [SellerReviewController::class, 'destroy'])->name('reviews.destroy');

    // Profile
    Route::get('profile/{seller}', [SellerProfileController::class, 'show'])->name('profile.show');
// ... existing code ...
    Route::put('profile/{seller}', [SellerProfileController::class, 'update'])->name('profile.update');
});

// TEMPORARY: Seller Products routes (akses tanpa login untuk development)
Route::prefix('seller/products')->name('seller.products.')->group(function () {
    Route::get('', [SellerProductController::class, 'index'])->name('index');
    Route::get('create', [SellerProductController::class, 'create'])->name('create');
    Route::post('', [SellerProductController::class, 'store'])->name('store');
    Route::get('{product}', [SellerProductController::class, 'show'])->name('show');
    Route::get('{product}/edit', [SellerProductController::class, 'edit'])->name('edit');
    Route::put('{product}', [SellerProductController::class, 'update'])->name('update');
    Route::delete('{product}', [SellerProductController::class, 'destroy'])->name('destroy');
});

// Protected seller routes
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard sudah di-define di atas, jadi hanya route lain yang protected

    // Reports (duplicate - routes exposed publicly during development above)
    // The public report routes are declared earlier to allow access without authentication.
    // Keep these commented while in dev to avoid route duplication and auth blocking.
    // Route::get('reports/stock-by-quantity', [SellerReportController::class, 'stockByQuantity'])->name('reports.stock_by_quantity');
    // Route::get('reports/stock-by-rating', [SellerReportController::class, 'stockByRating'])->name('reports.stock_by_rating');
    // Route::get('reports/low-stock', [SellerReportController::class, 'lowStock'])->name('reports.low_stock');
    // Route::get('reports/export/{type}', [SellerReportController::class, 'exportPdf'])->name('reports.export');

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