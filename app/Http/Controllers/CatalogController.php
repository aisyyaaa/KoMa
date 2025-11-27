<?php 
// app/Http/Controllers/CatalogController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Akan digunakan nanti

class CatalogController extends Controller
{
    /**
     * Tampilkan halaman utama (katalog produk).
     */
    public function index()
    {
        // Di tahap ini, kita hanya menampilkan layoutnya saja.
        // Data produk akan diambil di tahap SRS selanjutnya.
        // $products = Product::all();
        
        return view('katalog.index');
    }
}