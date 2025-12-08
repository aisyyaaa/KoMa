<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seller;
use App\Models\User;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seller::create([
            'store_name' => 'Toko Buku ABC',
            'store_description' => 'Toko buku online terlengkap',
            'pic_name' => 'Test User',
            'pic_phone' => '081234567890',
            'pic_email' => 'seller@example.com',
            'pic_street' => 'Jl. ABC No. 123',
            'pic_rt' => '01',
            'pic_rw' => '02',
            'pic_village' => 'Kelurahan ABC',
            'pic_city' => 'Kota ABC',
            'pic_province' => 'Provinsi ABC',
            'pic_ktp_number' => '1234567890123456',
            'pic_photo_path' => 'sellers/photos/default.png',
            'pic_ktp_file_path' => 'sellers/ktp/default.png',
            'status' => 'ACTIVE',
            'password' => bcrypt('password'),
        ]);
    }
}