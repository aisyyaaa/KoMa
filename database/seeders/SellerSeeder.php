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
            $table->string('short_description', 500)->nullable(); // Deskripsi singkat
            
            // --- DATA PIC & AKUN (SRS-MartPlace-01: Elemen 3, 4, 5, dan Akun) ---
            $table->string('pic_name'); // Nama PIC
            $table->string('phone_number', 15)->unique(); // No Handphone PIC (Kolom DB: phone_number)
            $table->string('email')->unique(); // Email (Kolom DB: email)
            $table->string('password');
            
            // --- DETAIL ALAMAT PIC (SRS-MartPlace-01: Elemen 6 s/d 11) ---
            $table->string('address'); // Alamat (nama jalan) PIC
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('village', 100); // Nama kelurahan
            $table->string('district', 100); // Nama Kecamatan
            $table->string('city', 100); // Kabupaten/Kota
            $table->string('province', 100); // Provinsi
            
            // --- DOKUMEN PIC (SRS-MartPlace-01: Elemen 12 s/d 14) ---
            // NIK KTP (Digunakan di Controller sebagai 'no_ktp_pic')
            $table->string('ktp_number', 20)->unique(); // Kolom DB: ktp_number
            $table->string('pic_photo_path'); // Path Foto PIC
            $table->string('ktp_file_path'); // Path File KTP PIC
            
            // --- STATUS AKUN & VERIFIKASI (SRS-MartPlace-02) ---
            // PENTING: Kolom harus bernama 'status' agar controller updateStatus berhasil
            $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED'])->default('PENDING'); 
            
            // Status Akun Aktif/Tidak Aktif
            $table->boolean('is_active')->default(false); 
            
            // Mencatat tanggal dan waktu pendaftaran
            $table->timestamp('registration_date')->nullable(); 

            $table->rememberToken();
            $table->timestamps(); 
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