<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// REVISI KRITIS: Import semua Seeder yang akan dipanggil
use Database\Seeders\SellerSeeder; 
use Database\Seeders\PlatformUserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    // Hapus use WithoutModelEvents; dari dalam class, karena sudah di-import di atas

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua Seeder yang diperlukan untuk mengisi data awal
        $this->call([
            // --- 1. SEEDER OTENTIKASI & PENGGUNA ---
            // Urutan: Seller -> PlatformUser
            SellerSeeder::class,
            PlatformUserSeeder::class, 
            
            // UserSeeder::class, // Jika ada User standar
            
            // --- 2. SEEDER DATA KATALOG ---
            // Urutan: Category -> Product
            CategorySeeder::class, 
            ProductSeeder::class, 
            
            // --- 3. SEEDER DATA TAMBAHAN ---
            // Review harus di-seed setelah Produk
            ReviewSeeder::class,
        ]);
    }
}