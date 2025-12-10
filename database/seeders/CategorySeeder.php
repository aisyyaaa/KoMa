<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Alat Tulis & Kuliah', 'slug' => 'alat-tulis-kuliah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebutuhan Kos/Asrama', 'slug' => 'kebutuhan-kos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buku, Modul, Skripsi', 'slug' => 'buku-modul', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aksesoris Gadget', 'slug' => 'aksesoris-gadget', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Makanan & Minuman Instan', 'slug' => 'makanan-minuman', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}