<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
// use App\Models\User; // ❌ TIDAK PERLU DI AMBIL LAGI KARENA PAKAI GUEST REVIEW

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil produk yang sudah di-seed
        $product1 = Product::where('slug', 'buku-catatan-premium-koma')->first();
        
        // Data user tidak diperlukan, kita langsung sediakan data visitor
        
        if ($product1) {
            // Review 1 untuk Buku Catatan Premium (Rating 5)
            Review::create([
                'product_id' => $product1->id,
                // 💡 GANTI user_id DENGAN DATA VISITOR SESUAI MIGRATION
                'visitor_name' => 'Bambang Sudiro',
                'visitor_phone' => '081211112222',
                'visitor_email' => 'bambang.review@email.com',
                'province' => 'Jawa Barat', // SRS-MartPlace-08 data
                
                'rating' => 5,
                'comment' => 'Buku sangat bagus, kertas tebal!',
                'status' => 'published'
            ]);
            
            // Review 2 untuk Buku Catatan Premium (Rating 4)
            Review::create([
                'product_id' => $product1->id,
                // 💡 GANTI user_id DENGAN DATA VISITOR
                'visitor_name' => 'Siska Dewi',
                'visitor_phone' => '081233334444',
                'visitor_email' => 'siska.review@email.com',
                'province' => 'Jawa Tengah',
                
                'rating' => 4,
                'comment' => 'Harga terjangkau, pengiriman cepat.',
                'status' => 'published'
            ]);
        }
    }
}