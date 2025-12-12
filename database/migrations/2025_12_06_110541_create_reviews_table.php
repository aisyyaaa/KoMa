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
            
            // --- Data Pengunjung (SRS-MartPlace-06: Visitor/Guest Review) ---
            $table->string('visitor_name', 100);
            $table->string('visitor_phone', 20); // Dihapus nullable agar sesuai required di Controller
            $table->string('visitor_email')->index();
            
            // Tambahan untuk Analitik Lokasi (SRS-MartPlace-08)
            $table->string('province', 100); // Dihapus nullable agar sesuai required di Controller
            
            // Komentar dan Rating (Skala 1 sampai 5)
            $table->unsignedTinyInteger('rating'); // 1 sampai 5
            $table->text('comment'); // Dihapus nullable agar sesuai required di Controller
            
            // Status Review (Opsional, untuk moderasi)
            $table->enum('status', ['published', 'pending', 'hidden'])->default('published');
            
            $table->timestamps(); // Menggunakan created_at untuk mencatat waktu review
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