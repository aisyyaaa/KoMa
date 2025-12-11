<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data Seller dan Category yang sudah dibuat
        $seller1 = Seller::where('store_name', 'Toko Buku ABC')->first();
        $seller2 = Seller::where('store_name', 'KoMa Stationery')->first();
        
        $catBuku = Category::where('name', 'Buku, Modul, Skripsi')->first();
        $catKos = Category::where('name', 'Kebutuhan Kos/Asrama')->first();
        $catAlatTulis = Category::where('name', 'Alat Tulis & Kuliah')->first();


        // Data Produk (Dibuat berdasarkan screenshot katalog)
        $products = [
            // Produk 1: Buku Catatan Premium (Toko Buku ABC)
            [
                'name' => 'Buku Catatan Premium Ko...',
                'slug' => 'buku-catatan-premium-koma',
                'sku' => 'KO-ATK-001', 
                'price' => 45000,
                'stock' => 120,
                'category_id' => $catAlatTulis->id ?? 1,
                'seller_id' => $seller1->id,
                'description' => 'Buku catatan dengan kualitas premium untuk mahasiswa.',
                'rating_average' => 4.5,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/d6/c8/04/d6c8041252accb64e1a489ec93dd675c.jpg', // 💡 URL GAMBAR BUKU CATATAN
            ],
            // Produk 2: Rice Cooker Mini (KoMa Stationery)
            [
                'name' => 'Rice Cooker Mini Anak Kos',
                'slug' => 'rice-cooker-mini-anak-kos',
                'sku' => 'KM-KOS-002', 
                'price' => 180000,
                'stock' => 50,
                'category_id' => $catKos->id ?? 2,
                'seller_id' => $seller2->id,
                'description' => 'Rice cooker mini ideal untuk anak kos.',
                'rating_average' => 0.0,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/41/ca/52/41ca525181a853463ab16ccf95b32b05.jpg', // 💡 URL GAMBAR RICE COOKER
            ],
            // Produk 3: Modul Praktikum (Toko Buku ABC)
            [
                'name' => 'Modul Praktikum Algoritma',
                'slug' => 'modul-praktikum-algoritma',
                'sku' => 'KO-BKS-003',
                'price' => 85000,
                'stock' => 80,
                'category_id' => $catBuku->id ?? 3,
                'seller_id' => $seller1->id,
                'description' => 'Modul wajib untuk praktikum algoritma.',
                'rating_average' => 0.0,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/88/78/f4/8878f449f90c9a6beceaed572e45d0b8.jpg', // 💡 URL GAMBAR PEMROGRAMAN WEB
            ],
        ];

        foreach ($products as $product) {
            // Menggunakan updateOrCreate agar lebih aman saat seeding ulang
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}