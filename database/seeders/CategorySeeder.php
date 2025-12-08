<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Buku', 'slug' => 'buku']);
        Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        Category::create(['name' => 'Pakaian', 'slug' => 'pakaian']);
    }
}