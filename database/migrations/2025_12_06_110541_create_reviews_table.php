<?php
// File: database/migrations/YYYY_MM_DD_HHMMSS_create_reviews_table.php

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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Produk
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Data Pengunjung (SRS-MartPlace-06)
            $table->string('visitor_name', 100);
            $table->string('visitor_phone', 20);
            $table->string('visitor_email')->index();
            
            // Tambahan untuk Analitik Lokasi (SRS-MartPlace-08)
            $table->string('province', 100)->nullable(); 
            
            // Komentar dan Rating (Skala 1 sampai 5)
            $table->unsignedTinyInteger('rating'); 
            $table->text('comment')->nullable(); 
            
            // KOLOM BARU DITAMBAHKAN (Untuk mengatasi error reviewed_at)
            $table->timestamp('reviewed_at')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};