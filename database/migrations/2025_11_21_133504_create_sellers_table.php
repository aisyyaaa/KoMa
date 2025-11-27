<?php 
// database/migrations/YYYY_MM_DD_HHMMSS_create_sellers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menggunakan sintaks Anonymous Class yang disarankan Laravel terbaru
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('storeName'); // Nama toko
            $table->string('storeDescription')->nullable(); // Deskripsi singkat
            $table->string('picName'); // Nama PIC
            $table->string('picPhone')->unique(); // No Handphone PIC
            $table->string('picEmail')->unique(); // email PIC
            $table->string('picStreet'); // Alamat (nama jalan) PIC
            $table->string('picRT'); // RT
            $table->string('picRW'); // RW
            $table->string('picVillage'); // Nama kelurahan
            $table->string('picDistrict'); // KECAMATAN (Tambahan)
            $table->string('picCity'); // Kabupaten/Kota
            $table->string('picProvince'); // Propinsi
            $table->string('picKtpNumber')->unique(); // No. KTP PIC
            $table->string('picPhotoPath')->nullable(); // Foto PIC (path)
            $table->string('picKtpFilePath')->nullable(); // File upload KTP PIC (path)
            $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED'])->default('PENDING'); // Status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};