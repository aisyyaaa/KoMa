<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SellerController; 
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