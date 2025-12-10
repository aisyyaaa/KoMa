<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Menampilkan daftar semua produk untuk pengunjung (SRS-MartPlace-04).
     */
    public function index()
    {
        // 1. Ambil data produk
        // Menggunakan eager loading (with) untuk Seller dan Category agar efisien (N+1 problem)
        $products = Product::with(['seller', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12); // Menggunakan paginasi untuk efisiensi halaman
        
        // 2. Kirim data ke view
        return view('katalog.index', compact('products'));
    }

    /**
     * Menampilkan detail satu produk tertentu.
     */
    public function show(Product $product)
    {
        // Load data tambahan yang diperlukan di halaman detail:
        // seller (untuk nama toko/lokasi), category, dan reviews (untuk menampilkan komentar).
        $product->load([
            'seller', 
            'category', 
            'reviews' => function ($query) {
                $query->latest(); // Urutkan komentar dari yang terbaru
            }
        ]);
        
        // Model Product sudah memiliki accessor averageRating() yang bisa digunakan di view.
        
        return view('katalog.detail', compact('product'));
    }
}