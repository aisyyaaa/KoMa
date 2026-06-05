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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seller_id')->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->constrained();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description');
                $table->decimal('price', 12, 2);
                $table->decimal('discount_price', 12, 2)->nullable();
                $table->integer('stock')->default(0);
                $table->integer('min_stock')->default(0);
                $table->string('sku')->unique();
                $table->string('brand')->nullable();
                $table->string('condition')->default('new');
                $table->decimal('weight', 8, 2)->nullable();
                $table->decimal('length', 8, 2)->nullable();
                $table->decimal('width', 8, 2)->nullable();
                $table->integer('warranty')->nullable();
                $table->string('shipment_origin_city', 100)->nullable();
                $table->decimal('base_shipping_cost', 10, 2)->default(0.00);
                $table->string('primary_image')->nullable();
                $table->json('additional_images')->nullable();
                $table->float('rating_average', 3, 1)->default(0.0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'seller_id')) {
                    $table->foreignId('seller_id')->nullable()->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('products', 'slug')) {
                    $table->string('slug')->nullable()->unique();
                }
                if (!Schema::hasColumn('products', 'sku')) {
                    $table->string('sku')->nullable()->unique();
                }
                if (!Schema::hasColumn('products', 'brand')) {
                    $table->string('brand')->nullable();
                }
                if (!Schema::hasColumn('products', 'condition')) {
                    $table->string('condition')->default('new');
                }
                if (!Schema::hasColumn('products', 'shipment_origin_city')) {
                    $table->string('shipment_origin_city', 100)->nullable();
                }
                if (!Schema::hasColumn('products', 'base_shipping_cost')) {
                    $table->decimal('base_shipping_cost', 10, 2)->default(0.00);
                }
                if (!Schema::hasColumn('products', 'primary_image')) {
                    $table->string('primary_image')->nullable();
                }
                if (!Schema::hasColumn('products', 'additional_images')) {
                    $table->json('additional_images')->nullable();
                }
                if (!Schema::hasColumn('products', 'rating_average')) {
                    $table->float('rating_average', 3, 1)->default(0.0);
                }
                if (!Schema::hasColumn('products', 'discount_price')) {
                    $table->decimal('discount_price', 12, 2)->nullable();
                }
                if (!Schema::hasColumn('products', 'min_stock')) {
                    $table->integer('min_stock')->default(0);
                }
                if (!Schema::hasColumn('products', 'weight')) {
                    $table->decimal('weight', 8, 2)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};