<?php 
// app/Http/Controllers/LandingPageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Akan digunakan nanti

class LandingPageController extends Controller
{
    /**
     * Tampilkan halaman utama (katalog produk).
     */
    public function index()
    {
        // Di tahap ini, kita hanya menampilkan layoutnya saja.
        // Data produk akan diambil di tahap SRS selanjutnya.
        // $products = Product::all();
        
        return view('landingpage.index');
    }
}