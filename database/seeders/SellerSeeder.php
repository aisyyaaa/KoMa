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
            'pic_district' => 'Kecamatan XYZ', // <-- KOLOM BARU DITAMBAHKAN
            'pic_city' => 'Kota ABC',
            'pic_province' => 'Provinsi ABC',
            'pic_ktp_number' => '1234567890123456',
            'pic_photo_path' => 'sellers/photos/default.png',
            'pic_ktp_file_path' => 'sellers/ktp/default.png',
            'status' => 'ACTIVE',
            'password' => bcrypt('password'),
        ]);
        
        // Tambahkan satu seller lagi untuk data dummy yang lebih beragam
        Seller::create([
            'store_name' => 'KoMa Stationery',
            'store_description' => 'Menjual perlengkapan kuliah termurah.',
            'pic_name' => 'Budi Santoso',
            'pic_phone' => '087654321000',
            'pic_email' => 'koma.seller@example.com',
            'pic_street' => 'Jalan Merdeka Raya',
            'pic_rt' => '05',
            'pic_rw' => '01',
            'pic_village' => 'Kelurahan Jaya',
            'pic_district' => 'Kecamatan Sentosa',
            'pic_city' => 'Kota Bandung',
            'pic_province' => 'Jawa Barat',
            'pic_ktp_number' => '9876543210987654',
            'pic_photo_path' => 'sellers/photos/default2.png',
            'pic_ktp_file_path' => 'sellers/ktp/default2.png',
            'status' => 'ACTIVE',
            'password' => bcrypt('password'),
        ]);
    }
}