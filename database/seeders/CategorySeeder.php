<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Asumsi model Kategori

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Alat Tulis & Kuliah', 'slug' => 'alat-tulis-kuliah'],
            ['name' => 'Kebutuhan Kos/Asrama', 'slug' => 'kebutuhan-kos-asrama'],
            ['name' => 'Buku, Modul, Skripsi', 'slug' => 'buku-modul-skripsi'],
            ['name' => 'Aksesoris Gadget', 'slug' => 'aksesoris-gadget'],
            ['name' => 'Makanan & Minuman Instan', 'slug' => 'makanan-minuman-instan'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}