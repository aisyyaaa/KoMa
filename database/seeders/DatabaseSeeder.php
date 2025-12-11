<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents; // Trait dipindahkan ke sini

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua Seeder yang diperlukan untuk mengisi data awal
        $this->call([
            // --- 1. SEEDER OTENTIKASI & PENGGUNA ---
            // Urutan: Seller -> PlatformUser -> User (jika ada)
            SellerSeeder::class,
            PlatformUserSeeder::class, 
            
            // Tambahkan UserSeeder di sini jika Anda membuatnya:
            // UserSeeder::class, 
            
            // --- 2. SEEDER DATA KATALOG ---
            // Urutan: Category -> Product
            CategorySeeder::class, 
            ProductSeeder::class, 
            
            // --- 3. SEEDER DATA TAMBAHAN ---
            // Review harus di-seed setelah Produk
            ReviewSeeder::class, // 💡 DITAMBAHKAN
        ]);
    }
}