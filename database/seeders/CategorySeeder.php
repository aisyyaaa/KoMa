<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Asumsi model Kategori

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // REVISI KRITIS 1: Bersihkan tabel untuk menghindari masalah duplikasi/caching
        Category::truncate(); 
        
        $categories = [
            // REVISI KRITIS 2: Mengubah slug agar sesuai dengan yang dicari ProductSeeder
            // (Diasumsikan ProductSeeder mencari slug pendek: 'alat-tulis-kuliah', 'kebutuhan-kos', 'buku-modul')
            
            ['name' => 'Alat Tulis & Kuliah', 'slug' => 'alat-tulis-kuliah'],
            // Sinkronisasi: dari 'kebutuhan-kos-asrama' menjadi 'kebutuhan-kos'
            ['name' => 'Kebutuhan Kos/Asrama', 'slug' => 'kebutuhan-kos'], 
            // Sinkronisasi: dari 'buku-modul-skripsi' menjadi 'buku-modul'
            ['name' => 'Buku, Modul, Skripsi', 'slug' => 'buku-modul'], 
            
            ['name' => 'Aksesoris Gadget', 'slug' => 'aksesoris-gadget'],
            ['name' => 'Makanan & Minuman Instan', 'slug' => 'makanan-minuman-instan'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}