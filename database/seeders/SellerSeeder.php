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
        // Active seller
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

        // Inactive sellers (for testing the report assumption)
        Seller::create([
            'store_name' => 'Toko Lama',
            'store_description' => 'Toko lama, sudah tidak aktif',
            'pic_name' => 'Budi',
            'pic_phone' => '081100000001',
            'pic_email' => 'budi@example.com',
            'pic_street' => 'Jl. Lama 1',
            'pic_rt' => '02',
            'pic_rw' => '03',
            'pic_village' => 'Kelurahan Lama',
            'pic_city' => 'Kota Lama',
            'pic_province' => 'Provinsi Lama',
            'pic_ktp_number' => '2222333344445555',
            'pic_photo_path' => 'sellers/photos/default.png',
            'pic_ktp_file_path' => 'sellers/ktp/default.png',
            'status' => 'REJECTED',
            'password' => bcrypt('password'),
        ]);

        Seller::create([
            'store_name' => 'Toko Nonaktif',
            'store_description' => 'Contoh penjual nonaktif',
            'pic_name' => 'Siti',
            'pic_phone' => '081100000002',
            'pic_email' => 'siti@example.com',
            'pic_street' => 'Jl. Nonaktif 5',
            'pic_rt' => '03',
            'pic_rw' => '04',
            'pic_village' => 'Kelurahan Non',
            'pic_city' => 'Kota Non',
            'pic_province' => 'Provinsi Non',
            'pic_ktp_number' => '5555444433332222',
            'pic_photo_path' => 'sellers/photos/default.png',
            'pic_ktp_file_path' => 'sellers/ktp/default.png',
            'status' => 'REJECTED',
            'password' => bcrypt('password'),
        ]);
    }
}