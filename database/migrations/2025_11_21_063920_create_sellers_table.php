<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();

            // --- DATA TOKO ---
            $table->string('storeName');             // 1. Nama toko
            $table->string('storeDescription')->nullable(); // 2. Deskripsi singkat
            
            // --- DATA PIC ---
            $table->string('picName');              // 3. Nama PIC
            $table->string('picPhone', 15)->unique(); // 4. No Handphone PIC (Unique)
            $table->string('picEmail')->unique();   // 5. email PIC (Unique)

            // --- ALAMAT PIC ---
            $table->string('picStreet');            // 6. Alamat (nama jalan) PIC
            $table->string('picRT', 5);              // 7. RT
            $table->string('picRW', 5);              // 8. RW
            $table->string('picVillage');           // 9. Nama kelurahan
            $table->string('picCity');              // 10. Kabupaten/Kota
            $table->string('picProvince');          // 11. Propinsi

            // --- DOKUMEN IDENTITAS PIC ---
            $table->string('picKtpNumber', 20)->unique(); // 12. No. KTP PIC (Unique)
            $table->string('picPhotoPath');         // 13. Path File Foto PIC [cite: 254]
            $table->string('picKtpFilePath');       // 14. Path File upload KTP PIC [cite: 255]

            // --- STATUS AKUN (Sesuai Class Diagram) ---
            // Sesuai Enumeration SellerStatus: PENDING, ACTIVE, REJECTED [cite: 111-113, 256]
            $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED'])->default('PENDING');

            // --- WAKTU ---
            $table->timestamps(); // creates createdAt and updatedAt columns [cite: 257, 258]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};