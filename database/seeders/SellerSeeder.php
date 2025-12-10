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
        // Seller Aktif 1: Toko Buku ABC (Diperbarui dengan pic_district)
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
            'pic_district' => 'Kecamatan XYZ', // <-- DIPERTAHANKAN
            'pic_city' => 'Kota ABC',
            'pic_province' => 'Provinsi ABC',
            'pic_ktp_number' => '1234567890123456',
            'pic_photo_path' => 'sellers/photos/default.png',
            'pic_ktp_file_path' => 'sellers/ktp/default.png',
            'status' => 'ACTIVE',
            'password' => bcrypt('password'),
        ]);
        
        // Seller Aktif 2: KoMa Stationery (DIPERTAHANKAN dari versi Lokal Anda)
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
            'pic_district' => 'Kecamatan Sentosa', // <-- DIPERTAHANKAN
            'pic_city' => 'Kota Bandung',
            'pic_province' => 'Jawa Barat',
            'pic_ktp_number' => '9876543210987654',
            'pic_photo_path' => 'sellers/photos/default2.png',
            'pic_ktp_file_path' => 'sellers/ktp/default2.png',
            'status' => 'ACTIVE',
            'password' => bcrypt('password'),
        ]);

        // Seller Nonaktif 1 (DITAMBAHKAN dari versi Incoming)
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
            'pic_district' => 'Kecamatan Lama', // <-- Ditambahkan agar sesuai skema database Anda
            'pic_city' => 'Kota Lama',
            'pic_province' => 'Provinsi Lama',
            'pic_ktp_number' => '2222333344445555',
            'pic_photo_path' => 'sellers/photos/default.png',
            'pic_ktp_file_path' => 'sellers/ktp/default.png',
            'status' => 'REJECTED',
            'password' => bcrypt('password'),
        ]);

        // Seller Nonaktif 2 (DITAMBAHKAN dari versi Incoming)
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
            'pic_district' => 'Kecamatan Non', // <-- Ditambahkan agar sesuai skema database Anda
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