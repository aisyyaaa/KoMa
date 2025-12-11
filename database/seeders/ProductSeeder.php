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


        // Data Produk UTAMA (Stok > 2)
        $products = [
            // Produk 1: Buku Catatan Premium (Stok 120)
            [
                'name' => 'Buku Catatan Premium Ko...',
                'slug' => 'buku-catatan-premium-koma',
                'sku' => 'KO-ATK-001', 
                'price' => 45000,
                'stock' => 120, 
                'category_id' => $catAlatTulis->id ?? 1,
                'seller_id' => $seller1->id,
                'description' => 'Buku catatan dengan kualitas premium untuk mahasiswa. Tersedia dalam berbagai warna pastel yang lembut.',
                'rating_average' => 4.5,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/d6/c8/04/d6c8041252accb64e1a489ec93dd675c.jpg', 
            ],
            // Produk 2: Rice Cooker Mini (Stok 50)
            [
                'name' => 'Rice Cooker Mini Anak Kos',
                'slug' => 'rice-cooker-mini-anak-kos',
                'sku' => 'KM-KOS-002', 
                'price' => 180000,
                'stock' => 50, 
                'category_id' => $catKos->id ?? 2,
                'seller_id' => $seller2->id,
                'description' => 'Rice cooker mini serbaguna, cocok untuk porsi tunggal anak kos. Hemat listrik.',
                'rating_average' => 0.0,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/41/ca/52/41ca525181a853463ab16ccf95b32b05.jpg',
            ],
            // Produk 3: Modul Praktikum (Stok 80)
            [
                'name' => 'Modul Praktikum Pemrograman Web 7 in 1',
                'slug' => 'modul-praktikum-algoritma',
                'sku' => 'KO-BKS-003',
                'price' => 85000,
                'stock' => 80, 
                'category_id' => $catBuku->id ?? 3,
                'seller_id' => $seller1->id,
                'description' => 'Modul wajib untuk praktikum algoritma. Mencakup 7 bahasa pemrograman web dasar untuk pemula.',
                'rating_average' => 0.0,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/88/78/f4/8878f449f90c9a6beceaed572e45d0b8.jpg',
            ],
            
            // --- DATA PENGUJIAN SRS-MartPlace-14 (Stok Rendah) ---
            
            // Produk 4: WAJIB MASUK LAPORAN (Stok 0, karena < 2)
            [
                'name' => 'Sticky Notes Rainbow', // 💡 NAMA DIREVISI
                'slug' => 'sticky-notes-rainbow',
                'sku' => 'KO-ATK-S00',
                'price' => 5000,
                'stock' => 0, // STOK KRITIS
                'category_id' => $catAlatTulis->id ?? 1,
                'seller_id' => $seller1->id, 
                'description' => 'Set sticky notes berwarna-warni dengan daya rekat kuat. Ideal untuk menandai halaman penting.', // 💡 DESKRIPSI DIREVISI
                'rating_average' => 4.0,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/736x/6e/c7/1b/6ec71b543f7fa90c408aecb5889a42e9.jpg',
            ],
            
            // Produk 5: WAJIB MASUK LAPORAN (Stok 1, karena < 2)
            [
                'name' => 'Gembok Stainless Mini', // 💡 NAMA DIREVISI
                'slug' => 'gembok-stainless-mini',
                'sku' => 'KM-KOS-S01',
                'price' => 20000,
                'stock' => 1, // STOK KRITIS
                'category_id' => $catKos->id ?? 2,
                'seller_id' => $seller2->id, 
                'description' => 'Gembok mini anti karat dari stainless steel, cocok untuk lemari atau koper. Dilengkapi 3 kunci cadangan.', // 💡 DESKRIPSI DIREVISI
                'rating_average' => 5.0,
                'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/bb/83/b2/bb83b224b85f46827b90ab5ccd6e3632.jpg',
            ],
            
            // Produk 6: TIDAK BOLEH MASUK LAPORAN (Stok 2)
            [
                'name' => 'Buku Tulis Hardcover A5',
                'slug' => 'buku-tulis-hardcover-a5',
                'sku' => 'KO-ATK-S02',
                'price' => 15000,
                'stock' => 2, // STOK AMAN (Tidak < 2)
                'category_id' => $catAlatTulis->id ?? 1,
                'seller_id' => $seller1->id,
                'description' => 'Buku tulis hardcover ukuran A5 dengan 100 lembar kertas bergaris.',
                'rating_average' => 3.5,
                'is_active' => true,
                'primary_image' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}