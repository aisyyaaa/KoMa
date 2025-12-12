<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use Illuminate\Support\Str; 

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tabel dikosongkan sebelum seeding
        Product::truncate(); 
        
        // 1. AMBIL DATA SELLER (ID 1, 2, 3 AKTIF)
        $seller1 = Seller::where('store_name', 'Toko Buku ABC')->first();
        $seller2 = Seller::where('store_name', 'KoMa Stationery')->first();
        // Fallback aman untuk seller3 jika dia belum ada
        $seller3 = Seller::where('store_name', 'Grosir Kos Jakarta')->first() ?? $seller1; 

        // 2. AMBIL DATA KATEGORI (Menggunakan slug yang sudah disinkronkan)
        $catBuku = Category::where('slug', 'buku-modul')->first();
        $catKos = Category::where('slug', 'kebutuhan-kos')->first();
        $catAlatTulis = Category::where('slug', 'alat-tulis-kuliah')->first();
        
        // Peringatan Kritis: Hentikan jika data dasar hilang
        if (!$seller1 || !$seller2 || !$seller3 || !$catAlatTulis || !$catKos || !$catBuku) {
             echo "ERROR: Data dasar (Seller/Category) tidak ditemukan. Pastikan SellerSeeder dan CategorySeeder sudah dijalankan.\n";
             return;
        }

        $catIdAlatTulis = $catAlatTulis->id;
        $catIdKos = $catKos->id;
        $catIdBuku = $catBuku->id;
        
        // --- DATA PRODUK (TOTAL 7 PRODUK) ---
        $products = [
            // Produk 1: Buku Catatan Premium (ATK - Seller 1)
            [
                'name' => 'Buku Catatan Premium KoMa',
                'slug' => 'buku-catatan-premium-koma',
                'sku' => 'KO-ATK-001', 
                'price' => 45000, 'stock' => 120, 'min_stock' => 5,
                'category_id' => $catIdAlatTulis,
                'seller_id' => $seller1->id, 
                'description' => 'Buku catatan dengan kualitas premium untuk mahasiswa.',
                'rating_average' => 4.5, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/d6/c8/04/d6c8041252accb64e1a489ec93dd675c.jpg', // FIX KRITIS
            ],
            // Produk 2: Rice Cooker Mini (KOS - Seller 2)
            [
                'name' => 'Rice Cooker Mini Anak Kos',
                'slug' => 'rice-cooker-mini-anak-kos',
                'sku' => 'KM-KOS-002', 
                'price' => 180000, 'stock' => 50, 'min_stock' => 5,
                'category_id' => $catIdKos,
                'seller_id' => $seller2->id, 
                'description' => 'Rice cooker mini serbaguna, cocok untuk porsi tunggal anak kos.',
                'rating_average' => 3.0, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/41/ca/52/41ca525181a853463ab16ccf95b32b05.jpg', // FIX KRITIS
            ],
            // Produk 3: Modul Praktikum (BUKU - Seller 1)
            [
                'name' => 'Modul Praktikum Pemrograman Web 7 in 1',
                'slug' => 'modul-praktikum-algoritma',
                'sku' => 'KO-BKS-003',
                'price' => 85000, 'stock' => 80, 'min_stock' => 5,
                'category_id' => $catIdBuku,
                'seller_id' => $seller1->id,
                'description' => 'Modul wajib untuk praktikum algoritma.',
                'rating_average' => 0.0, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/88/78/f4/8878f449f90c9a6beceaed572e45d0b8.jpg', // FIX KRITIS
            ],
            
            // Produk 4: Sticky Notes Rainbow (ATK - Seller 3) - Stok Rendah
            [
                'name' => 'Sticky Notes Rainbow',
                'slug' => 'sticky-notes-rainbow',
                'sku' => 'KO-ATK-S00',
                'price' => 5000, 'stock' => 1, 'min_stock' => 5, 
                'category_id' => $catIdAlatTulis,
                'seller_id' => $seller3->id, 
                'description' => 'Set sticky notes berwarna-warni.',
                'rating_average' => 4.0, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/57/72/c8/5772c87a184425b66d2b165c19e3e743.jpg', // FIX KRITIS
            ],
            
            // Produk 5: Gembok Stainless Mini (KOS - Seller 3)
            [
                'name' => 'Gembok Stainless Mini',
                'slug' => 'gembok-stainless-mini',
                'sku' => 'KM-KOS-S01',
                'price' => 20000, 'stock' => 150, 'min_stock' => 5, 
                'category_id' => $catIdKos,
                'seller_id' => $seller3->id, 
                'description' => 'Gembok mini anti karat dari stainless steel.',
                'rating_average' => 5.0, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/bb/83/b2/bb83b224b85f46827b90ab5ccd6e3632.jpg', // FIX KRITIS
            ],
            
            // Produk 6: Mouse Wireless (ATK - Seller 2) - DENGAN GAMBAR BARU
            [
                'name' => 'Mouse Wireless Ergonomis',
                'slug' => 'mouse-wireless-ergonomis',
                'sku' => 'KO-ATK-002',
                'price' => 75000, 'stock' => 90, 'min_stock' => 10,
                'category_id' => $catIdAlatTulis,
                'seller_id' => $seller2->id, 
                'description' => 'Mouse tanpa kabel yang nyaman digunakan untuk sesi belajar yang panjang.',
                'rating_average' => 3.5, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/25/b5/30/25b530135b0c1327f8155f184420ad11.jpg', // FIX KRITIS
            ],

            // Produk 7: Kamus Teknik (BUKU - Seller 2) - DENGAN GAMBAR BARU
            [
                'name' => 'Kamus Teknik Lengkap Edisi Terbaru',
                'slug' => 'kamus-teknik-lengkap',
                'sku' => 'KO-BKS-004',
                'price' => 125000, 'stock' => 30, 'min_stock' => 5,
                'category_id' => $catIdBuku,
                'seller_id' => $seller2->id, 
                'description' => 'Kamus wajib untuk mahasiswa teknik.',
                'rating_average' => 4.8, 'is_active' => true,
                'primary_image' => 'https://i.pinimg.com/1200x/40/3f/f7/403ff7edc4e90747501487fed716f7b0.jpg', // FIX KRITIS
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}