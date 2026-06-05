<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // REVISI KRITIS: Bersihkan tabel terlebih dahulu untuk menghindari UNIQUE CONSTRAINT errors
        Seller::truncate(); 

        // Pastikan folder storage/app/public/seller_docs ada
        if (!Storage::disk('public')->exists('seller_docs')) {
            Storage::disk('public')->makeDirectory('seller_docs');
        }

        // --- Data Seller Uji Coba (6 Total Seller: 3 ACTIVE, 2 PENDING, 1 REJECTED) ---

        // 1. Seller AKTIF - Jawa Tengah (ID 1)
        Seller::create([
            'store_name' => 'Toko Buku ABC',
            'short_description' => 'Menyediakan modul dan buku kuliah terlengkap di Jawa Tengah.',
            'pic_name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'email' => 'budi.abc@mail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Merdeka No. 10', 'rt' => '001', 'rw' => '002', 
            'village' => 'Sukamakmur', 'district' => 'Tembalang', 
            'city' => 'Semarang', 'province' => 'Jawa Tengah',
            'ktp_number' => '1122334455667788',
            'pic_photo_path' => 'seller_docs/pic_budi.jpg', 
            'ktp_file_path' => 'seller_docs/ktp_budi.pdf',
            'status' => 'ACTIVE', 
            'is_active' => true,
            'registration_date' => now(), 
        ]);
        
        // 2. Seller AKTIF - DIY Yogyakarta (ID 2)
        Seller::create([
            'store_name' => 'KoMa Stationery',
            'short_description' => 'Menjual perlengkapan kos dan alat tulis modern.',
            'pic_name' => 'Dewi Lestari',
            'phone_number' => '085000111222',
            'email' => 'dewi.koma@mail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Pelajar No. 5', 'rt' => '003', 'rw' => '001', 
            'village' => 'Kampus Baru', 'district' => 'Depok', 
            'city' => 'Sleman', 'province' => 'DIY Yogyakarta',
            'ktp_number' => '9988776655443322',
            'pic_photo_path' => 'seller_docs/pic_dewi.jpg',
            'ktp_file_path' => 'seller_docs/ktp_dewi.pdf',
            'status' => 'ACTIVE', 
            'is_active' => true,
            'registration_date' => now(), 
        ]);

        // 3. Seller AKTIF - DKI Jakarta (ID 3)
        Seller::create([
            'store_name' => 'Grosir Kos Jakarta',
            'short_description' => 'Grosir kebutuhan dapur dan kebersihan kos.',
            'pic_name' => 'Fajar Hakim',
            'phone_number' => '081112223334',
            'email' => 'fajar.grosir@mail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Kebon Jeruk No. 8', 'rt' => '004', 'rw' => '005', 
            'village' => 'Kebon', 'district' => 'Kemayoran', 
            'city' => 'Jakarta Pusat', 'province' => 'DKI Jakarta',
            'ktp_number' => '3101010101010101',
            'pic_photo_path' => 'seller_docs/pic_fajar.jpg',
            'ktp_file_path' => 'seller_docs/ktp_fajar.pdf',
            'status' => 'ACTIVE', 
            'is_active' => true,
            'registration_date' => now(), 
        ]);

        // 4. Seller PENDING - Jawa Tengah (ID 4)
        Seller::create([
            'store_name' => 'Teknologi Cepat',
            'short_description' => 'Aksesoris Gadget dan Laptop Murah.',
            'pic_name' => 'Gina Putri',
            'phone_number' => '082345678901',
            'email' => 'gina.tech@mail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Pahlawan No. 7', 'rt' => '002', 'rw' => '004', 
            'village' => 'Pekalongan', 'district' => 'Kajen', 
            'city' => 'Pekalongan', 'province' => 'Jawa Tengah',
            'ktp_number' => '3333444455556666',
            'pic_photo_path' => 'seller_docs/pic_gina.jpg',
            'ktp_file_path' => 'seller_docs/ktp_gina.pdf',
            'status' => 'PENDING', 
            'is_active' => false,
            'registration_date' => now(), 
        ]);

        // 5. Seller PENDING - Jawa Barat (ID 5)
        Seller::create([
            'store_name' => 'Bumdes Hasil Tani',
            'short_description' => 'Menyediakan makanan dan minuman lokal.',
            'pic_name' => 'Haris Gunawan',
            'phone_number' => '087654321098',
            'email' => 'haris.bumdes@mail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Desa Cepat No. 1', 'rt' => '001', 'rw' => '001', 
            'village' => 'Cibiru', 'district' => 'Cileunyi', 
            'city' => 'Bandung', 'province' => 'Jawa Barat',
            'ktp_number' => '3210987654321098',
            'pic_photo_path' => 'seller_docs/pic_haris.jpg',
            'ktp_file_path' => 'seller_docs/ktp_haris.pdf',
            'status' => 'PENDING', 
            'is_active' => false,
            'registration_date' => now(), 
        ]);

        // 6. Seller REJECTED - Jawa Barat (ID 6)
        Seller::create([
            'store_name' => 'Drop Shipper Abal',
            'short_description' => 'Menjual barang tanpa stok.',
            'pic_name' => 'Indra Jaya',
            'phone_number' => '089911223344',
            'email' => 'indra.reject@mail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Raya Bogor No. 1', 'rt' => '005', 'rw' => '005', 
            'village' => 'Cibinong', 'district' => 'Bogor', 
            'city' => 'Bogor', 'province' => 'Jawa Barat',
            'ktp_number' => '3210112233445566',
            'pic_photo_path' => 'seller_docs/pic_indra.jpg',
            'ktp_file_path' => 'seller_docs/ktp_indra.pdf',
            'status' => 'REJECTED', 
            'is_active' => false,
            'registration_date' => now(), 
        ]);
    }
}