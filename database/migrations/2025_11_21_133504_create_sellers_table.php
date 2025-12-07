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
        // migration
Schema::create('sellers', function (Blueprint $table) {
    $table->id();
    $table->string('store_name');
    $table->text('store_description')->nullable();
    $table->string('pic_name');
    $table->string('pic_phone')->unique();
    $table->string('pic_email')->unique();
    $table->string('pic_street');
    $table->string('pic_rt');
    $table->string('pic_rw');
    $table->string('pic_village');
    $table->string('pic_city');
    $table->string('pic_province');
    $table->string('pic_ktp_number')->unique();
    $table->string('pic_photo_path');
    $table->string('pic_ktp_file_path');
    $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED'])->default('PENDING');
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};