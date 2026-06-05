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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Relasi
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            
            // Data Dasar
            $table->string('name');
            $table->string('slug')->unique(); // Untuk URL Detail
            $table->text('description');
            
            // Harga & Stok
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->integer('stock')->default(value: 0);
            $table->integer('min_stock')->default(value: 0);
            $table->string('sku')->unique();
            
            // Atribut
            $table->string('brand')->nullable();
            $table->enum('condition', ['new', 'used'])->default('new');
            
            // Dimensi/Pengiriman
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->integer('warranty')->nullable(); 
            
            // REVISI KRITIS: Tambahkan kolom pengiriman
            $table->string('shipment_origin_city', 100)->nullable();
            $table->decimal('base_shipping_cost', 10, 2)->default(0.00); 
            
            // Gambar (untuk sinkronisasi dengan Seeder)
            $table->string('primary_image')->nullable();
            $table->json('additional_images')->nullable();
            
            // --- KOLOM KRITIS YANG DITAMBAHKAN SEBELUMNYA ---
            
            // 💡 1. Rating Rata-rata 
            $table->float('rating_average', 3, 1)->default(0.0); 

            // 💡 2. Status Produk Aktif
            $table->boolean('is_active')->default(true); 
            
            // ----------------------------------------
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};