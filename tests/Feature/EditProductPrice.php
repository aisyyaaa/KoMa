<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * ============================================================================
 * WHITE-BOX TESTING: Fitur Edit Harga Produk di Dashboard Seller
 * ============================================================================
 * 
 * Controller yang diuji:
 *   App\Http\Controllers\Seller\SellerProductController@update (line ~140-180)
 * 
 * Route yang diuji:
 *   PUT /seller/products/{product} (name: seller.products.update)
 * 
 * Request Validation:
 *   App\Http\Requests\ProductRequest (rules: price => numeric|min:0)
 * 
 * Guard: 'seller' (middleware auth:seller)
 * 
 * ============================================================================
 * ANALISIS PATH / CABANG (Branch Coverage):
 * ============================================================================
 * 
 * Alur method update():
 *   1. Cek autoritas (seller_id === Auth::id())                    (line ~145)
 *      → Path G1: Authorized (pass) → lanjut
 *      → Path G2: Unauthorized (abort 403)
 * 
 *   2. Validasi input via ProductRequest                           (line ~148)
 *      → Path A: price valid (numeric, min:0) → pass
 *      → Path B: price = 0 (boundary min) → pass
 *      → Path C: price negatif → validation error
 *      → Path D: price non-numeric → validation error
 *      → Path E: price tidak dikirim (required) → validation error
 * 
 *   3. Validasi discount_price (lt:price)                          (ProductRequest)
 *      → Path F1: discount_price < price → valid
 *      → Path F2: discount_price >= price → validation error
 *      → Path F3: discount_price null → valid (opsional)
 * 
 *   4. Update ke database                                          (line ~175)
 *      → $product->update($validated) → price berubah
 * 
 *   5. Redirect ke halaman detail                                  (line ~177-178)
 * 
 * ============================================================================
 */
class EditProductPriceTest extends TestCase
{
    use RefreshDatabase;

    private Seller $seller;
    private Seller $otherSeller;
    private Category $category;
    private Product $product;

    /**
     * Setup data awal: 2 seller, 1 kategori, 1 produk milik seller utama.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // ---- Seller utama (yang akan login) ----
        $this->seller = Seller::create([
            'store_name'        => 'Toko Test Edit Harga',
            'short_description' => 'Toko untuk testing edit harga',
            'pic_name'          => 'PIC Test',
            'phone_number'      => '081234567890',
            'email'             => 'editharga@test.com',
            'password'          => Hash::make('password123'),
            'address'           => 'Jl. Testing No. 1',
            'rt'                => '001',
            'rw'                => '002',
            'village'           => 'TestVillage',
            'district'          => 'TestDistrict',
            'city'              => 'Semarang',
            'province'          => 'Jawa Tengah',
            'ktp_number'        => '1111222233334444',
            'pic_photo_path'    => 'seller_docs/test.jpg',
            'ktp_file_path'     => 'seller_docs/test.pdf',
            'status'            => 'ACTIVE',
            'is_active'         => true,
            'registration_date' => now(),
        ]);

        // ---- Seller lain (untuk uji autoritas) ----
        $this->otherSeller = Seller::create([
            'store_name'        => 'Toko Lain',
            'short_description' => 'Toko seller lain',
            'pic_name'          => 'PIC Lain',
            'phone_number'      => '089876543210',
            'email'             => 'other@test.com',
            'password'          => Hash::make('password123'),
            'address'           => 'Jl. Lain No. 2',
            'rt'                => '003',
            'rt'                => '004',
            'village'           => 'LainVillage',
            'district'          => 'LainDistrict',
            'city'              => 'Bandung',
            'province'          => 'Jawa Barat',
            'ktp_number'        => '5555666677778888',
            'pic_photo_path'    => 'seller_docs/other.jpg',
            'ktp_file_path'     => 'seller_docs/other.pdf',
            'status'            => 'ACTIVE',
            'is_active'         => true,
            'registration_date' => now(),
        ]);

        // ---- Kategori ----
        $this->category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        // ---- Produk milik seller utama (harga awal Rp 100.000) ----
        $this->product = Product::create([
            'seller_id'          => $this->seller->id,
            'category_id'        => $this->category->id,
            'name'               => 'Mouse Wireless Logitech',
            'slug'               => 'mouse-wireless-logitech',
            'description'        => 'Mouse wireless dengan baterai tahan lama',
            'price'              => 100000,
            'stock'              => 50,
            'min_stock'          => 5,
            'sku'                => 'SKU-MOUSE-001',
            'brand'              => 'Logitech',
            'condition'          => 'new',
            'weight'             => 200,
            'shipment_origin_city' => 'Semarang',
            'base_shipping_cost' => 10000,
            'is_active'          => true,
        ]);
    }

    /**
     * Helper: Login sebagai seller utama dan kirim request update.
     * Data default mencakup field wajib dari ProductRequest.
     */
    private function updateProduct(array $additionalData = []): \Illuminate\Testing\TestResponse
    {
        $defaultData = [
            'category_id'           => $this->category->id,
            'name'                  => 'Mouse Wireless Logitech',
            'description'           => 'Mouse wireless dengan baterai tahan lama',
            'price'                 => 100000,
            'stock'                 => 50,
            'sku'                   => 'SKU-MOUSE-001',
            'condition'             => 'new',
            'shipment_origin_city'  => 'Semarang',
        ];

        return $this->actingAs($this->seller, 'seller')
                    ->put(route('seller.products.update', $this->product), 
                          array_merge($defaultData, $additionalData));
    }

    // ========================================================================
    // TEST: Autentikasi & Autoritas
    // ========================================================================

    /**
     * TC-PRICE-00: Edit harga TIDAK bisa dilakukan tanpa login.
     * Expected: redirect 302 ke halaman login
     */
    public function test_edit_harga_tidak_bisa_tanpa_login(): void
    {
        $response = $this->put(route('seller.products.update', $this->product), [
            'price' => 150000
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login'); // atau route login
        $this->assertGuest('seller');
    }

    /**
     * TC-PRICE-01: Edit harga TIDAK bisa oleh seller lain (bukan pemilik produk).
     * Path G2: Unauthorized → abort 403
     */
    public function test_edit_harga_tidak_bisa_oleh_seller_lain(): void
    {
        $response = $this->actingAs($this->otherSeller, 'seller')
                    ->put(route('seller.products.update', $this->product), [
                        'category_id'          => $this->category->id,
                        'name'                 => 'Mouse Wireless Logitech',
                        'description'          => 'Deskripsi',
                        'price'                => 200000,
                        'stock'                => 50,
                        'sku'                  => 'SKU-MOUSE-001',
                        'condition'            => 'new',
                        'shipment_origin_city' => 'Semarang',
                    ]);

        $response->assertStatus(403); // Forbidden
        
        // Pastikan harga tidak berubah
        $this->product->refresh();
        $this->assertEquals(100000, $this->product->price);
    }

    /**
     * TC-PRICE-02: Edit harga bisa dilakukan oleh pemilik produk.
     * Path G1: Authorized → pass
     */
    public function test_edit_harga_bisa_oleh_pemilik_produk(): void
    {
        $response = $this->updateProduct(['price' => 150000]);

        $response->assertStatus(302);
        $response->assertRedirect(route('seller.products.detail', ['id' => $this->product->id]));
        $response->assertSessionHas('success', 'Produk berhasil diupdate');
        
        $this->product->refresh();
        $this->assertEquals(150000, $this->product->price);
    }

    // ========================================================================
    // PATH A: Valid price → update berhasil
    // ========================================================================

    /**
     * TC-PRICE-03: Update harga dengan nilai valid positif.
     * Path A: price = 150000 (numeric, >0) → valid, update berhasil
     */
    public function test_update_harga_valid_positif_berhasil(): void
    {
        $response = $this->updateProduct(['price' => 175000]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals(175000, $this->product->price);
    }

    /**
     * TC-PRICE-04: Update harga dengan nilai desimal (float).
     * Path A: price = 99999.99 → valid (numeric menerima desimal)
     */
    public function test_update_harga_dengan_desimal_berhasil(): void
    {
        $response = $this->updateProduct(['price' => 99999.99]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals(99999.99, $this->product->price);
    }

    // ========================================================================
    // PATH B: price = 0 (boundary minimal) → valid
    // ========================================================================

    /**
     * TC-PRICE-05: Update harga dengan nilai 0 (nol).
     * Path B: price = 0 → min:0 → valid (produk gratis)
     */
    public function test_update_harga_nol_berhasil(): void
    {
        $response = $this->updateProduct(['price' => 0]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals(0, $this->product->price);
    }

    // ========================================================================
    // PATH C: price negative → error validation
    // ========================================================================

    /**
     * TC-PRICE-06: Update harga dengan nilai negatif.
     * Path C: price = -5000 → min:0 → validation error
     */
    public function test_update_harga_negatif_error(): void
    {
        $response = $this->updateProduct(['price' => -5000]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
        
        // Harga tidak berubah
        $this->product->refresh();
        $this->assertEquals(100000, $this->product->price);
    }

    /**
     * TC-PRICE-07: Update harga dengan nilai negatif desimal.
     * Path C: price = -100.50 → validation error
     */
    public function test_update_harga_negatif_desimal_error(): void
    {
        $response = $this->updateProduct(['price' => -100.50]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
        
        $this->product->refresh();
        $this->assertEquals(100000, $this->product->price);
    }

    // ========================================================================
    // PATH D: price non-numeric → error validation
    // ========================================================================

    /**
     * TC-PRICE-08: Update harga dengan string non-numeric.
     * Path D: price = 'abc' → numeric validation fails
     */
    public function test_update_harga_string_error(): void
    {
        $response = $this->updateProduct(['price' => 'abc']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
        
        $this->product->refresh();
        $this->assertEquals(100000, $this->product->price);
    }

    /**
     * TC-PRICE-09: Update harga dengan string kosong.
     * Path D: price = '' → numeric validation fails
     */
    public function test_update_harga_string_kosong_error(): void
    {
        $response = $this->updateProduct(['price' => '']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * TC-PRICE-10: Update harga dengan null.
     * Path E (required): price tidak dikirim → validation error
     */
    public function test_update_harga_null_error(): void
    {
        // Hapus field price dari request
        $response = $this->actingAs($this->seller, 'seller')
                    ->put(route('seller.products.update', $this->product), [
                        'category_id' => $this->category->id,
                        'name' => 'Mouse Wireless Logitech',
                        'description' => 'Deskripsi',
                        'stock' => 50,
                        'sku' => 'SKU-MOUSE-001',
                        'condition' => 'new',
                        'shipment_origin_city' => 'Semarang',
                        // price tidak disertakan
                    ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
    }

    // ========================================================================
    // PATH F: discount_price validation (lt:price)
    // ========================================================================

    /**
     * TC-PRICE-11: discount_price valid (lebih kecil dari price).
     * Path F1: discount_price = 75000 < price = 100000 → valid
     */
    public function test_discount_price_valid_kurang_dari_harga(): void
    {
        $response = $this->updateProduct([
            'price' => 100000,
            'discount_price' => 75000
        ]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals(75000, $this->product->discount_price);
    }

    /**
     * TC-PRICE-12: discount_price sama dengan price (tidak boleh).
     * Path F2: discount_price = 100000, price = 100000 → lt:price fails
     */
    public function test_discount_price_sama_dengan_harga_error(): void
    {
        $response = $this->updateProduct([
            'price' => 100000,
            'discount_price' => 100000
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['discount_price']);
    }

    /**
     * TC-PRICE-13: discount_price lebih besar dari price (tidak boleh).
     * Path F2: discount_price = 120000 > price = 100000 → lt:price fails
     */
    public function test_discount_price_lebih_besar_dari_harga_error(): void
    {
        $response = $this->updateProduct([
            'price' => 100000,
            'discount_price' => 120000
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['discount_price']);
    }

    /**
     * TC-PRICE-14: discount_price null (opsional, valid).
     * Path F3: discount_price tidak dikirim → valid
     */
    public function test_discount_price_kosong_valid(): void
    {
        $response = $this->updateProduct([
            'price' => 100000,
            // discount_price tidak disertakan
        ]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertNull($this->product->discount_price);
    }

    // ========================================================================
    // EDGE CASES & BOUNDARY TESTING
    // ========================================================================

    /**
     * TC-PRICE-15: Update harga dengan nilai maksimum integer.
     * Boundary test: PHP_INT_MAX
     */
    public function test_update_harga_max_integer(): void
    {
        $maxPrice = PHP_INT_MAX;
        $response = $this->updateProduct(['price' => $maxPrice]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals($maxPrice, $this->product->price);
    }

    /**
     * TC-PRICE-16: Update harga dengan nilai sangat kecil (0.0001).
     * Boundary test: decimal mendekati 0
     */
    public function test_update_harga_very_small_decimal(): void
    {
        $response = $this->updateProduct(['price' => 0.0001]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals(0.0001, $this->product->price);
    }

    /**
     * TC-PRICE-17: Update harga dengan nilai besar (1 milyar).
     */
    public function test_update_harga_satu_milyar(): void
    {
        $response = $this->updateProduct(['price' => 1000000000]);

        $response->assertStatus(302);
        $this->product->refresh();
        $this->assertEquals(1000000000, $this->product->price);
    }

    // ========================================================================
    // MULTI FIELD: Update price bersamaan dengan field lain
    // ========================================================================

    /**
     * TC-PRICE-18: Update price + name + stock sekaligus.
     * Memastikan update price tidak mengganggu field lain
     */
    public function test_update_price_bersamaan_field_lain(): void
    {
        $response = $this->updateProduct([
            'price' => 200000,
            'name' => 'Mouse Wireless Logitech Pro',
            'stock' => 25,
        ]);

        $response->assertStatus(302);
        $this->product->refresh();
        
        $this->assertEquals(200000, $this->product->price);
        $this->assertEquals('Mouse Wireless Logitech Pro', $this->product->name);
        $this->assertEquals(25, $this->product->stock);
    }

    /**
     * TC-PRICE-19: Update hanya price saja (field lain tetap).
     */
    public function test_update_hanya_price_field_lain_tetap(): void
    {
        $originalName = $this->product->name;
        $originalStock = $this->product->stock;
        
        $response = $this->updateProduct(['price' => 300000]);

        $response->assertStatus(302);
        $this->product->refresh();
        
        $this->assertEquals(300000, $this->product->price);
        $this->assertEquals($originalName, $this->product->name);
        $this->assertEquals($originalStock, $this->product->stock);
    }

    // ========================================================================
    // VERIFIKASI REDIRECT & SESSION MESSAGE
    // ========================================================================

    /**
     * TC-PRICE-20: Redirect ke halaman detail produk setelah update.
     */
    public function test_redirect_ke_halaman_detail_setelah_update(): void
    {
        $response = $this->updateProduct(['price' => 150000]);

        $response->assertRedirect(route('seller.products.detail', ['id' => $this->product->id]));
    }

    /**
     * TC-PRICE-21: Session flash message 'success' setelah update.
     */
    public function test_session_success_message_setelah_update(): void
    {
        $response = $this->updateProduct(['price' => 150000]);

        $response->assertSessionHas('success', 'Produk berhasil diupdate');
    }

    /**
     * TC-PRICE-22: Tidak ada session success jika update gagal (validasi error).
     */
    public function test_tidak_ada_session_success_jika_gagal(): void
    {
        $response = $this->updateProduct(['price' => -5000]);

        $response->assertSessionDoesntHave('success');
        $response->assertSessionHasErrors(['price']);
    }

    // ========================================================================
    // XSS & SECURITY: Memastikan price tidak rentan injection
    // ========================================================================

    /**
     * TC-PRICE-23: Price dengan script injection harus ditolak oleh validasi numeric.
     */
    public function test_harga_dengan_xss_script_ditolak(): void
    {
        $response = $this->updateProduct(['price' => '<script>alert(1)</script>']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
        
        $this->product->refresh();
        $this->assertEquals(100000, $this->product->price);
    }

    /**
     * TC-PRICE-24: Price dengan SQL injection pattern ditolak.
     */
    public function test_harga_dengan_sql_injection_ditolak(): void
    {
        $response = $this->updateProduct(['price' => "1' OR '1'='1"]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
    }
}