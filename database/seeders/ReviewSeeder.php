<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. AMBIL DATA PRODUK YANG SUDAH ADA ---
        $p1 = Product::where('slug', 'buku-catatan-premium-koma')->first();
        $p2 = Product::where('slug', 'rice-cooker-mini-anak-kos')->first();
        $p3 = Product::where('slug', 'modul-praktikum-algoritma')->first();
        $p4 = Product::where('slug', 'sticky-notes-rainbow')->first();
        $p5 = Product::where('slug', 'gembok-stainless-mini')->first();
        $p6 = Product::where('slug', 'buku-tulis-hardcover-a5')->first();
        
        // Bersihkan review lama sebelum seeding
        Review::truncate();

        // --- 2. DATA REVIEWS LENGKAP UNTUK UJI SRS-13 & SRS-08 ---
        
        $reviews = [];

        // P1: Buku Catatan Premium (Rating 5.0)
        if ($p1) {
            $reviews[] = [ 'product_id' => $p1->id, 'rating' => 5, 'comment' => 'Kualitas kertas dan sampulnya sangat premium, melebihi ekspektasi!', 'visitor_name' => 'Budi Setiawan', 'visitor_phone' => '081234567890', 'visitor_email' => 'budi@mail.com', 'province' => 'Jawa Barat' ];
            $reviews[] = [ 'product_id' => $p1->id, 'rating' => 5, 'comment' => 'Cocok untuk membuat mind map dan mencatat materi kuliah.', 'visitor_name' => 'Ani Suryani', 'visitor_phone' => '081122334455', 'visitor_email' => 'ani@mail.com', 'province' => 'Jawa Tengah' ];
            $reviews[] = [ 'product_id' => $p1->id, 'rating' => 5, 'comment' => 'Pengiriman cepat dan aman, produk sampai tanpa cacat.', 'visitor_name' => 'Candra Wijaya', 'visitor_phone' => '081556677889', 'visitor_email' => 'candra@mail.com', 'province' => 'Jawa Timur' ];
        }
        
        // P2: Rice Cooker Mini (Rating 3.0)
        if ($p2) {
            $reviews[] = [ 'product_id' => $p2->id, 'rating' => 4, 'comment' => 'Masak nasi cepat, pas untuk porsi sendiri.', 'visitor_name' => 'Diana Kartika', 'visitor_phone' => '085000111222', 'visitor_email' => 'diana@mail.com', 'province' => 'Jawa Barat' ];
            $reviews[] = [ 'product_id' => $p2->id, 'rating' => 2, 'comment' => 'Agak lengket di bagian bawah, perlu ditingkatkan lapisan anti lengketnya.', 'visitor_name' => 'Eko Susanto', 'visitor_phone' => '087766554433', 'visitor_email' => 'eko@mail.com', 'province' => 'Jawa Barat' ];
            $reviews[] = [ 'product_id' => $p2->id, 'rating' => 3, 'comment' => 'Ukuran ideal untuk kos, tapi panasnya kurang merata.', 'visitor_name' => 'Fani Akbar', 'visitor_phone' => '081345678901', 'visitor_email' => 'fani@mail.com', 'province' => 'Jawa Tengah' ];
            $reviews[] = [ 'product_id' => $p2->id, 'rating' => 3, 'comment' => 'Sesuai harga, hemat listrik.', 'visitor_name' => 'Gilang Pratama', 'visitor_phone' => '089988776655', 'visitor_email' => 'gilang@mail.com', 'province' => 'Jawa Tengah' ];
        }

        // P3: Modul Praktikum (Rating 4.0)
        if ($p3) {
            $reviews[] = [ 'product_id' => $p3->id, 'rating' => 4, 'comment' => 'Materi lengkap, covernya tebal, sangat berguna untuk praktikum.', 'visitor_name' => 'Hadi Firmansyah', 'visitor_phone' => '081234987654', 'visitor_email' => 'hadi@mail.com', 'province' => 'DKI Jakarta' ];
            $reviews[] = [ 'product_id' => $p3->id, 'rating' => 4, 'comment' => 'Contoh kodenya mudah diikuti, recommended!', 'visitor_name' => 'Indah Lestari', 'visitor_phone' => '081112223334', 'visitor_email' => 'indah@mail.com', 'province' => 'DKI Jakarta' ];
            $reviews[] = [ 'product_id' => $p3->id, 'rating' => 4, 'comment' => 'Wajib punya bagi mahasiswa informatika.', 'visitor_name' => 'Joko Santoso', 'visitor_phone' => '085887766554', 'visitor_email' => 'joko@mail.com', 'province' => 'Jawa Timur' ];
        }

        // P4: Sticky Notes Rainbow (Rating 4.5)
        if ($p4) {
            $reviews[] = [ 'product_id' => $p4->id, 'rating' => 5, 'comment' => 'Warnanya cerah, rekatnya pas, tidak merusak kertas.', 'visitor_name' => 'Kirana Wati', 'visitor_phone' => '089998887776', 'visitor_email' => 'kirana@mail.com', 'province' => 'Jawa Barat' ];
            $reviews[] = [ 'product_id' => $p4->id, 'rating' => 4, 'comment' => 'Stok cepat habis, tapi produknya bagus.', 'visitor_name' => 'Lina Mulyani', 'visitor_phone' => '081887766554', 'visitor_email' => 'lina@mail.com', 'province' => 'Jawa Barat' ];
        }

        // P5: Gembok Stainless Mini (Rating 5.0)
        if ($p5) {
            $reviews[] = [ 'product_id' => $p5->id, 'rating' => 5, 'comment' => 'Sangat aman dan anti karat, sesuai deskripsi.', 'visitor_name' => 'Miko Nugraha', 'visitor_phone' => '081223344556', 'visitor_email' => 'miko@mail.com', 'province' => 'Jawa Tengah' ];
        }

        // P6: Buku Tulis Hardcover A5 (Rating 1.0)
        if ($p6) {
            $reviews[] = [ 'product_id' => $p6->id, 'rating' => 1, 'comment' => 'Kertasnya terlalu tipis, tidak cocok untuk pulpen gel.', 'visitor_name' => 'Nia Safitri', 'visitor_phone' => '081998877665', 'visitor_email' => 'nia@mail.com', 'province' => 'Jawa Timur' ];
        }

        // --- 3. EKSEKUSI SEEDING ---
        foreach ($reviews as $review) {
            // Asumsi status default adalah 'published' atau diset di Model/Migration
            Review::create(array_merge($review, ['status' => 'published'])); 
        }
    }
}