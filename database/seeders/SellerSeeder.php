<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash; // Diperlukan untuk hashing password

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Password default yang digunakan untuk semua akun: 'password'
        $defaultPassword = Hash::make('password');

        // Seller Aktif 1: Toko Buku ABC (AKUN UTAMA UNTUK TESTING LOGIN)
        Seller::create([
            'store_name' => 'Toko Buku ABC',
            'short_description' => 'Toko buku online terlengkap', // ✅ SESUAI MODEL & MIGRATION
            'pic_name' => 'Test User',
            'phone_number' => '081234567890', // ✅ SESUAI MODEL & MIGRATION
            'email' => 'seller@example.com', // ✅ SESUAI MODEL & MIGRATION
            
            // Detail Alamat
            'address' => 'Jl. ABC No. 123', // ✅ SESUAI MODEL & MIGRATION
            'rt' => '01',
            'rw' => '02',
            'village' => 'Kelurahan ABC',
            'district' => 'Kecamatan XYZ', // ✅ SESUAI MODEL & MIGRATION
            'city' => 'Kota ABC',
            'province' => 'Provinsi ABC',
            
            // Dokumen & Status
            'ktp_number' => '1234567890123456', // ✅ SESUAI MODEL & MIGRATION
            'pic_photo_path' => 'sellers/photos/default.png',
            'ktp_file_path' => 'sellers/ktp/default.png',
            
            // STATUS AKTIF (SRS-MartPlace-02)
            'verification_status' => 'approved', // ✅
            'is_active' => true,                 // ✅ Kunci untuk bisa login
            'password' => $defaultPassword,
        ]);
        
        // Seller Aktif 2: KoMa Stationery
        Seller::create([
            'store_name' => 'KoMa Stationery',
            'short_description' => 'Menjual perlengkapan kuliah termurah.',
            'pic_name' => 'Budi Santoso',
            'phone_number' => '087654321000',
            'email' => 'koma.seller@example.com',
            'address' => 'Jalan Merdeka Raya',
            'rt' => '05',
            'rw' => '01',
            'village' => 'Kelurahan Jaya',
            'district' => 'Kecamatan Sentosa', 
            'city' => 'Kota Bandung',
            'province' => 'Jawa Barat',
            'ktp_number' => '9876543210987654',
            'pic_photo_path' => 'sellers/photos/default2.png',
            'ktp_file_path' => 'sellers/ktp/default2.png',
            'verification_status' => 'approved', 
            'is_active' => true,
            'password' => $defaultPassword,
        ]);

        // Seller Nonaktif 1 (REJECTED)
        Seller::create([
            'store_name' => 'Toko Lama',
            'short_description' => 'Toko lama, sudah tidak aktif',
            'pic_name' => 'Budi',
            'phone_number' => '081100000001',
            'email' => 'budi@example.com',
            'address' => 'Jl. Lama 1',
            'rt' => '02',
            'rw' => '03',
            'village' => 'Kelurahan Lama',
            'district' => 'Kecamatan Lama', 
            'city' => 'Kota Lama',
            'province' => 'Provinsi Lama',
            'ktp_number' => '2222333344445555',
            'pic_photo_path' => 'sellers/photos/default.png',
            'ktp_file_path' => 'sellers/ktp/default.png',
            'verification_status' => 'rejected', // ✅ Ditolak
            'is_active' => false,                 // ✅ Tidak aktif
            'password' => $defaultPassword,
        ]);

        // Seller Nonaktif 2 (PENDING - Belum diverifikasi)
        Seller::create([
            'store_name' => 'Toko Menunggu',
            'short_description' => 'Contoh penjual menunggu verifikasi',
            'pic_name' => 'Siti',
            'phone_number' => '081100000002',
            'email' => 'siti@example.com',
            'address' => 'Jl. Nonaktif 5',
            'rt' => '03',
            'rw' => '04',
            'village' => 'Kelurahan Non',
            'district' => 'Kecamatan Non', 
            'city' => 'Kota Non',
            'province' => 'Provinsi Non',
            'ktp_number' => '5555444433332222',
            'pic_photo_path' => 'sellers/photos/default.png',
            'ktp_file_path' => 'sellers/ktp/default.png',
            'verification_status' => 'pending', // ✅ Menunggu
            'is_active' => false,               // ✅ Tidak aktif
            'password' => $defaultPassword,
        ]);
    }
}