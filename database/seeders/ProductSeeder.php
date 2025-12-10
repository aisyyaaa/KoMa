<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use App\Models\Seller;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menghapus data lama (aman karena Anda menggunakan migrate:fresh)
        Product::truncate();
        Review::truncate();

        // Ambil ID Seller (pastikan SellerSeeder dijalankan sebelum ini)
        $seller1 = Seller::where('pic_email', 'seller@example.com')->first(); // Toko Buku ABC
        $seller2 = Seller::where('pic_email', 'koma.seller@example.com')->first(); // KoMa Stationery
        
        if (!$seller1 || !$seller2) {
             echo "Peringatan: Seller tidak ditemukan. Pastikan SellerSeeder telah dijalankan dan data email PIC sudah benar.\n";
             return; 
        }

        // Data Produk Dummy (tidak berubah)
        $productsData = [
            [
                'seller_id' => $seller1->id, 
                'category_id' => 1, 
                'name' => 'Buku Catatan Premium KoMa',
                'slug' => 'buku-catatan-premium-koma',
                'sku' => 'KC-BP-001',
                'description' => 'Buku catatan dengan kertas premium, 100gsm. Cocok untuk kuliah dan membuat jurnal. Tahan lama dan desain elegan.',
                'price' => 50000,
                'discount_price' => 45000,
                'stock' => 150,
                'primary_image' => 'https://i.pinimg.com/1200x/93/58/6c/93586cb1c58ebc3c5cb0a462d366f539.jpg',
                'additional_images' => json_encode([
                    'https://via.placeholder.com/400x300/e9ecef?text=Detail+Kertas',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'seller_id' => $seller2->id, 
                'category_id' => 2,
                'name' => 'Rice Cooker Mini Anak Kos',
                'slug' => 'rice-cooker-mini-anak-kos',
                'sku' => 'KC-RC-002',
                'description' => 'Rice Cooker mini kapasitas 0.8L, hemat listrik, cocok untuk anak kos.',
                'price' => 180000,
                'discount_price' => 0,
                'stock' => 50,
                'primary_image' => 'https://i.pinimg.com/1200x/32/36/a1/3236a1f5612532f5bc3bbbc530bdf5cb.jpg',
                'additional_images' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'seller_id' => $seller1->id,
                'category_id' => 3, 
                'name' => 'Modul Praktikum Algoritma',
                'slug' => 'modul-praktikum-algoritma',
                'sku' => 'KC-MA-003',
                'description' => 'Modul wajib untuk praktikum Algoritma semester 3. Edisi terbaru.',
                'price' => 85000,
                'discount_price' => 0,
                'stock' => 200,
                'primary_image' => 'https://i.pinimg.com/1200x/69/ad/52/69ad524512b3094f2672e6e8a7df7d1f.jpg',
                'additional_images' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        $reviewsData = [];
        $currentDate = Carbon::now();

        // Masukkan Produk
        DB::table('products')->insert($productsData);
        
        // Ambil kembali ID produk yang baru dibuat
        $product1 = Product::where('sku', 'KC-BP-001')->first();

        // --- Data Review ---
        if ($product1) {
            $reviewsData = array_merge($reviewsData, [
                [
                    'product_id' => $product1->id,
                    'visitor_name' => 'Pengunjung A',
                    'visitor_email' => 'a@test.com',
                    'visitor_phone' => '081211122233', // <-- Disesuaikan agar sesuai Regex HP Indonesia
                    'province' => 'Jawa Timur',
                    'rating' => 5,
                    'comment' => 'Produk sangat bagus, pengiriman cepat!',
                    'reviewed_at' => $currentDate->copy()->subDays(5),
                    'created_at' => $currentDate->copy()->subDays(5),
                    'updated_at' => $currentDate->copy()->subDays(5),
                ],
                [
                    'product_id' => $product1->id,
                    'visitor_name' => 'Pengunjung B',
                    'visitor_email' => 'b@test.com',
                    'visitor_phone' => '081555444333', // <-- Disesuaikan
                    'province' => 'Jawa Barat',
                    'rating' => 4,
                    'comment' => 'Kualitas kertas sesuai harga. Sangat membantu.',
                    'reviewed_at' => $currentDate->copy()->subDays(3),
                    'created_at' => $currentDate->copy()->subDays(3),
                    'updated_at' => $currentDate->copy()->subDays(3),
                ],
                // Catatan: Saya HAPUS pengunjung C untuk produk ini.
                // Jika Anda ingin menguji batasan unik, Anda bisa mencoba mereview produk ini
                // menggunakan email "a@test.com" atau "b@test.com" setelah seeder berjalan.
            ]);
        }

        // Masukkan semua data review 
        if (!empty($reviewsData)) {
            DB::table('reviews')->insert($reviewsData); 
        }
    }
}