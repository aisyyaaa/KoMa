<?php

namespace Tests\Feature\KoMa\Concerns;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;

trait CreatesTestData
{
    protected function makeSeller(array $overrides = []): Seller
    {
        static $count = 0;
        $count++;

        return Seller::create(array_merge([
            'store_name'       => "Toko Test {$count}",
            'email'            => "seller{$count}@test.com",
            'password'         => Hash::make('password'),
            'pic_name'         => 'Test PIC',
            'phone_number'     => "081" . str_pad($count, 8, '0', STR_PAD_LEFT),
            'address'          => 'Jl. Test No. 1',
            'rt'               => '001',
            'rw'               => '001',
            'village'          => 'Kelurahan Test',
            'district'         => 'Kecamatan Test',
            'city'             => 'Kota Test',
            'province'         => 'Jawa Tengah',
            'ktp_number'       => str_pad($count, 16, '0', STR_PAD_LEFT),
            'pic_photo_path'   => 'seller_docs/pic_photos/test_foto.jpg',
            'ktp_file_path'    => 'seller_docs/ktp_files/test_ktp.jpg',
            'is_active'        => true,
            'status'           => 'ACTIVE',
            'registration_date'=> now(),
        ], $overrides));
    }

    protected function makeCategory(array $overrides = []): Category
    {
        static $catCount = 0;
        $catCount++;

        return Category::create(array_merge([
            'name' => "Kategori Test {$catCount}",
            'slug' => "kategori-test-{$catCount}",
        ], $overrides));
    }

    protected function makeProduct(Seller $seller, Category $category, array $overrides = []): Product
    {
        static $prodCount = 0;
        $prodCount++;

        return Product::create(array_merge([
            'seller_id'   => $seller->id,
            'category_id' => $category->id,
            'name'        => "Produk Test {$prodCount}",
            'slug'        => "produk-test-{$prodCount}",
            'description' => 'Deskripsi produk untuk keperluan testing otomatis',
            'price'       => 50000,
            'stock'       => 20,
            'is_active'   => true,
        ], $overrides));
    }

    protected function makeOrder(\App\Models\User $buyer, Product $product, array $overrides = []): Order
    {
        $order = Order::create(array_merge([
            'user_id'        => $buyer->id,
            'name'           => $buyer->name,
            'phone'          => '08199887766',
            'address'        => 'Jl. Pembeli No. 5',
            'payment_method' => 'COD',
            'status'         => 'Menunggu',
            'total'          => $product->price,
        ], $overrides));

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'qty'        => 1,
            'price'      => $product->price,
        ]);

        return $order;
    }
}
