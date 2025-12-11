<?php 
// File: database/migrations/YYYY_MM_DD_HHMMSS_create_sellers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel).
     */
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            
            // --- DATA TOKO (SRS-MartPlace-01: Elemen 1 & 2) ---
            $table->string('store_name')->unique(); // Nama toko
            // Mengganti 'store_description' dengan 'short_description' (lebih sesuai dengan field Controller)
            $table->string('short_description', 500)->nullable(); // Deskripsi singkat
            
            // --- DATA PIC & AKUN (SRS-MartPlace-01: Elemen 3, 4, 5, dan Akun) ---
            $table->string('pic_name'); // Nama PIC
            // Mengganti 'pic_phone' menjadi 'phone_number' (konsistensi)
            $table->string('phone_number', 15)->unique(); // No Handphone PIC
            $table->string('email')->unique(); // Mengganti pic_email menjadi email (Standar Laravel untuk Auth)
            $table->string('password');
            
            // --- DETAIL ALAMAT PIC (SRS-MartPlace-01: Elemen 6 s/d 11) ---
            // Mengganti 'pic_street' menjadi 'address' (konsistensi dengan Controller)
            $table->string('address'); // Alamat (nama jalan) PIC
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('village', 100); // Nama kelurahan
            $table->string('district', 100); // Nama Kecamatan (Sesuai Permintaan)
            $table->string('city', 100); // Kabupaten/Kota
            $table->string('province', 100); // Provinsi
            
            // --- DOKUMEN PIC (SRS-MartPlace-01: Elemen 12 s/d 14) ---
            $table->string('ktp_number', 20)->unique(); // Mengganti 'pic_ktp_number'
            $table->string('pic_photo_path'); // Path Foto PIC
            $table->string('ktp_file_path'); // Path File KTP PIC
            
            // --- STATUS AKUN & VERIFIKASI (SRS-MartPlace-02) ---
            // KOREKSI: Mengubah 'verification_status' menjadi 'status' dan nilai enum menjadi uppercase
            $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED'])->default('PENDING'); 
            
            // Status Akun Aktif/Tidak Aktif (Digunakan saat Login)
            $table->boolean('is_active')->default(false); // Status aktif akun (true jika sudah di-approve Platform)
            
            // Mencatat tanggal dan waktu pendaftaran (SRS-MartPlace-02)
            $table->timestamp('registration_date')->nullable(); 

            $table->rememberToken();
            $table->timestamps(); // created_at (kapan data dibuat) dan updated_at
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};