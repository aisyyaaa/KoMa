<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada seller dan category
        if (Seller::count() == 0 || Category::count() == 0) {
            $this->command->info('Tidak dapat membuat produk. Pastikan ada data seller dan category terlebih dahulu.');
            return;
        }

        $seller = Seller::first();
        $category = Category::first();

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Buku Laravel 11',
            'description' => 'Buku panduan lengkap untuk mempelajari framework Laravel 11 dari dasar hingga mahir.',
            'price' => 150000,
            'stock' => 50,
            'sku' => 'BK-LRVL-11',
            'brand' => 'Penerbit Informatika',
            'condition' => 'new',
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Mouse Gaming RGB',
            'description' => 'Mouse gaming dengan sensor presisi tinggi dan lampu RGB yang bisa diatur.',
            'price' => 250000,
            'stock' => 30,
            'sku' => 'MS-GMRGB-01',
            'brand' => 'TechGear',
            'condition' => 'new',
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Keyboard Mechanical',
            'description' => 'Keyboard mechanical dengan switch biru yang responsif dan tahan lama.',
            'price' => 450000,
            'stock' => 20,
            'sku' => 'KB-MECH-BLUE',
            'brand' => 'KeyChron',
            'condition' => 'new',
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Headset Wireless',
            'description' => 'Headset wireless dengan kualitas suara jernih dan bass yang mendalam.',
            'price' => 350000,
            'stock' => 25,
            'sku' => 'HS-WL-BASS',
            'brand' => 'SoundWave',
            'condition' => 'new',
        ]);
    }
}
