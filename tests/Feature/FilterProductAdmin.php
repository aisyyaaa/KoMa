<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * ============================================================================
 * WHITE-BOX TESTING: Fitur Filter Kategori Produk di Dashboard Seller
 * ============================================================================
 * 
 * Controller yang diuji:
 *   App\Http\Controllers\Seller\SellerProductController@index (line 24-55)
 * 
 * Route yang diuji:
 *   GET /seller/products (name: seller.products.index)
 *   Parameter query: ?category=id (filter kategori)
 *                     ?search=keyword (bisa kombinasi)
 * 
 * Guard: 'seller' (middleware auth:seller)
 * 
 * ============================================================================
 * ANALISIS PATH / CABANG (Branch Coverage):
 * ============================================================================
 * 
 * Alur method index() - Fokus pada Filter Kategori:
 *   1. Ambil seller_id dari Auth::guard('seller')->id()      (line 26)
 *   2. Ambil categoryId dari $request->get('category')        (line 28)
 *   3. Query: Product::where('seller_id', $sellerId)          (line 33)
 * 
 * CABANG FILTER KATEGORI (line 48-50):
 *   Path C1: categoryId KOSONG → SKIP filter → tampil semua produk seller
 *   Path C2: categoryId ADA → where('category_id', $categoryId) → tampil produk kategori tertentu
 *   Path C3: categoryId TIDAK VALID (tidak ada di DB) → tetap filter → 0 hasil
 * 
 * KOMBINASI DENGAN SEARCH (Branch Integration):
 *   Path C2+S1: Filter kategori + search keyword (match)
 *   Path C2+S2: Filter kategori + search keyword (tidak match) → 0 hasil
 * 
 * ============================================================================
 */
class FilterProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    private Seller $seller;
    private Seller $otherSeller;
    private Category $categoryElektronik;
    private Category $categoryFashion;
    private Category $categoryMakanan;
    private Category $categoryBuku;

    /**
     * Setup data: 2 seller, 4 kategori, produk dengan variasi kategori.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // ---- Seller utama (yang akan login) ----
        $this->seller = Seller::create([
            'store_name'        => 'Toko Test Filter',
            'short_description' => 'Toko untuk testing filter kategori',
            'pic_name'          => 'PIC Test',
            'phone_number'      => '081234567890',
            'email'             => 'filter@test.com',
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

        // ---- Seller lain (untuk uji isolasi data) ----
        $this->otherSeller = Seller::create([
            'store_name'        => 'Toko Lain',
            'short_description' => 'Toko seller lain',
            'pic_name'          => 'PIC Lain',
            'phone_number'      => '089876543210',
            'email'             => 'other@test.com',
            'password'          => Hash::make('password123'),
            'address'           => 'Jl. Lain No. 2',
            'rt'                => '003',
            'rw'                => '004',
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

        // ---- Buat 4 Kategori ----
        $this->categoryElektronik = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $this->categoryFashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
        ]);

        $this->categoryMakanan = Category::create([
            'name' => 'Makanan & Minuman',
            'slug' => 'makanan-minuman',
        ]);

        $this->categoryBuku = Category::create([
            'name' => 'Buku & Alat Tulis',
            'slug' => 'buku-alat-tulis',
        ]);

        // ---- Buat Produk milik Seller Utama dengan berbagai kategori ----
        
        // Elektronik: 3 produk
        $this->createProduct('Mouse Logitech', 'SKU-ELEC-001', $this->seller, $this->categoryElektronik, 150000);
        $this->createProduct('Keyboard Mechanical', 'SKU-ELEC-002', $this->seller, $this->categoryElektronik, 350000);
        $this->createProduct('Monitor 24 Inch', 'SKU-ELEC-003', $this->seller, $this->categoryElektronik, 1500000);
        
        // Fashion: 2 produk
        $this->createProduct('Kaos Polos Hitam', 'SKU-FASH-001', $this->seller, $this->categoryFashion, 75000);
        $this->createProduct('Jaket Hoodie', 'SKU-FASH-002', $this->seller, $this->categoryFashion, 250000);
        
        // Makanan: 2 produk
        $this->createProduct('Kopi Arabika', 'SKU-FOOD-001', $this->seller, $this->categoryMakanan, 50000);
        $this->createProduct('Snack Sehat', 'SKU-FOOD-002', $this->seller, $this->categoryMakanan, 25000);
        
        // Buku: 1 produk
        $this->createProduct('Novel Fiksi', 'SKU-BOOK-001', $this->seller, $this->categoryBuku, 85000);

        // ---- Buat Produk milik Seller Lain (untuk uji isolasi) ----
        $this->createProduct('Produk Lain Elektronik', 'SKU-OTHER-001', $this->otherSeller, $this->categoryElektronik, 200000);
        $this->createProduct('Produk Lain Fashion', 'SKU-OTHER-002', $this->otherSeller, $this->categoryFashion, 100000);
    }

    /**
     * Helper: Buat produk di database.
     */
    private function createProduct(
        string $name,
        string $sku,
        Seller $seller,
        Category $category,
        int $price
    ): Product {
        return Product::create([
            'seller_id'          => $seller->id,
            'category_id'        => $category->id,
            'name'               => $name,
            'slug'               => \Illuminate\Support\Str::slug($name),
            'description'        => 'Deskripsi untuk ' . $name,
            'price'              => $price,
            'stock'              => 100,
            'min_stock'          => 10,
            'sku'                => $sku,
            'condition'          => 'new',
            'base_shipping_cost' => 0,
            'is_active'          => true,
        ]);
    }

    /**
     * Helper: Login sebagai seller utama dan akses halaman produk dengan parameter filter.
     */
    private function actingAsSellerGet(array $queryParams = [])
    {
        return $this->actingAs($this->seller, 'seller')
                     ->get(route('seller.products.index', $queryParams));
    }

    // ========================================================================
    // PATH C1: Tanpa filter kategori → tampil SEMUA produk seller
    // ========================================================================

    /**
     * TC-FILTER-01: Tanpa parameter category, semua produk milik seller tampil.
     * 
     * Path C1: categoryId KOSONG → SKIP filter where('category_id')
     * Expected: 8 produk (3 Elektronik + 2 Fashion + 2 Makanan + 1 Buku)
     */
    public function test_tanpa_filter_tampil_semua_produk_seller(): void
    {
        $response = $this->actingAsSellerGet();

        $response->assertStatus(200);
        $response->assertViewHas('products');

        $products = $response->viewData('products');
        $this->assertEquals(8, $products->total());
    }

    /**
     * TC-FILTER-02: Tanpa filter, produk seller lain TIDAK muncul.
     * 
     * Path C1 + Isolasi data via seller_id filter
     * Expected: Hanya 8 produk milik seller sendiri
     */
    public function test_tanpa_filter_produk_seller_lain_tidak_muncul(): void
    {
        $response = $this->actingAsSellerGet();

        $products = $response->viewData('products');
        
        foreach ($products as $product) {
            $this->assertEquals($this->seller->id, $product->seller_id,
                "Produk '{$product->name}' bukan milik seller yang login.");
        }
        
        // Pastikan produk seller lain tidak masuk
        $this->assertDatabaseMissing('products', [
            'seller_id' => $this->otherSeller->id,
            'name' => 'Produk Lain Elektronik'
        ]);
    }

    // ========================================================================
    // PATH C2: Filter kategori dengan ID valid → tampil produk kategori tertentu
    // ========================================================================

    /**
     * TC-FILTER-03: Filter kategori Elektronik (3 produk).
     * 
     * Path C2: categoryId = $categoryElektronik->id
     *         → where('category_id', $id) → hanya produk Elektronik
     * Expected: 3 produk (Mouse, Keyboard, Monitor)
     */
    public function test_filter_kategori_elektronik_berhasil(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryElektronik->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(3, $products->total());
        
        foreach ($products as $product) {
            $this->assertEquals($this->categoryElektronik->id, $product->category_id,
                "Produk '{$product->name}' bukan kategori Elektronik.");
        }
    }

    /**
     * TC-FILTER-04: Filter kategori Fashion (2 produk).
     * 
     * Path C2: categoryId = $categoryFashion->id
     * Expected: 2 produk (Kaos, Jaket)
     */
    public function test_filter_kategori_fashion_berhasil(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryFashion->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(2, $products->total());
        
        foreach ($products as $product) {
            $this->assertEquals($this->categoryFashion->id, $product->category_id);
        }
    }

    /**
     * TC-FILTER-05: Filter kategori Makanan (2 produk).
     * 
     * Path C2: categoryId = $categoryMakanan->id
     * Expected: 2 produk (Kopi, Snack)
     */
    public function test_filter_kategori_makanan_berhasil(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryMakanan->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(2, $products->total());
        
        foreach ($products as $product) {
            $this->assertEquals($this->categoryMakanan->id, $product->category_id);
        }
    }

    /**
     * TC-FILTER-06: Filter kategori Buku (1 produk).
     * 
     * Path C2: categoryId = $categoryBuku->id
     * Expected: 1 produk (Novel Fiksi)
     */
    public function test_filter_kategori_buku_berhasil(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryBuku->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Novel Fiksi', $products->first()->name);
    }

    // ========================================================================
    // PATH C3: Filter dengan kategori ID TIDAK VALID → 0 hasil
    // ========================================================================

    /**
     * TC-FILTER-07: Filter dengan ID kategori yang tidak ada di database.
     * 
     * Path C3: categoryId = 9999 (tidak ada)
     *         → where('category_id', 9999) → 0 results
     * Expected: 0 produk, halaman tetap 200 (tidak error)
     */
    public function test_filter_kategori_id_tidak_valid(): void
    {
        $response = $this->actingAsSellerGet(['category' => 9999]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());
    }

    /**
     * TC-FILTER-08: Filter dengan ID kategori string (bukan integer).
     * 
     * Path C3: categoryId = 'abc' → Laravel akan casting ke integer? 
     *         → where('category_id', 0) atau error tergantung DB driver
     * Expected: 0 produk (karena tidak ada category_id = 0)
     */
    public function test_filter_kategori_id_string(): void
    {
        $response = $this->actingAsSellerGet(['category' => 'abc']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        // String non-numeric biasanya di-cast ke 0 oleh MySQL
        $this->assertEquals(0, $products->total());
    }

    // ========================================================================
    // KOMBINASI: Filter Kategori + Search Keyword (Branch Integration)
    // ========================================================================

    /**
     * TC-FILTER-09: Filter kategori Elektronik + search 'Mouse'.
     * 
     * Path C2+S1: categoryId = Elektronik AND search = 'Mouse'
     * Expected: 1 produk (Mouse Logitech)
     */
    public function test_filter_kategori_dan_search_match(): void
    {
        $response = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'search' => 'Mouse'
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Mouse Logitech', $products->first()->name);
        $this->assertEquals($this->categoryElektronik->id, $products->first()->category_id);
    }

    /**
     * TC-FILTER-10: Filter kategori Elektronik + search 'Jaket' (tidak match).
     * 
     * Path C2+S2: categoryId = Elektronik AND search = 'Jaket'
     * Expected: 0 produk (Jaket di kategori Fashion, bukan Elektronik)
     */
    public function test_filter_kategori_dan_search_tidak_match(): void
    {
        $response = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'search' => 'Jaket'
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());
    }

    /**
     * TC-FILTER-11: Filter kategori Fashion + search 'Kaos'.
     * 
     * Path C2+S1: Match tepat
     * Expected: 1 produk (Kaos Polos Hitam)
     */
    public function test_filter_kategori_fashion_dan_search_kaos(): void
    {
        $response = $this->actingAsSellerGet([
            'category' => $this->categoryFashion->id,
            'search' => 'Kaos'
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Kaos Polos Hitam', $products->first()->name);
    }

    /**
     * TC-FILTER-12: Filter kategori Makanan + search 'Kopi'.
     * 
     * Path C2+S1: Match tepat
     * Expected: 1 produk (Kopi Arabika)
     */
    public function test_filter_kategori_makanan_dan_search_kopi(): void
    {
        $response = $this->actingAsSellerGet([
            'category' => $this->categoryMakanan->id,
            'search' => 'Kopi'
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Kopi Arabika', $products->first()->name);
    }

    // ========================================================================
    // ISOLASI DATA: Filter kategori hanya untuk produk seller yang login
    // ========================================================================

    /**
     * TC-FILTER-13: Filter kategori Elektronik, produk seller lain TIDAK muncul.
     * 
     * Meskipun seller lain punya produk Elektronik ('Produk Lain Elektronik'),
     * tidak boleh tampil karena seller_id berbeda.
     */
    public function test_filter_kategori_tidak_menampilkan_produk_seller_lain(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryElektronik->id]);

        $products = $response->viewData('products');
        $productNames = $products->pluck('name')->toArray();

        $this->assertNotContains('Produk Lain Elektronik', $productNames);
        $this->assertContains('Mouse Logitech', $productNames);
        $this->assertContains('Keyboard Mechanical', $productNames);
        $this->assertContains('Monitor 24 Inch', $productNames);
    }

    /**
     * TC-FILTER-14: Filter kategori Fashion, produk seller lain TIDAK muncul.
     */
    public function test_filter_kategori_fashion_tidak_menampilkan_seller_lain(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryFashion->id]);

        $products = $response->viewData('products');
        $productNames = $products->pluck('name')->toArray();

        $this->assertNotContains('Produk Lain Fashion', $productNames);
        $this->assertContains('Kaos Polos Hitam', $productNames);
        $this->assertContains('Jaket Hoodie', $productNames);
    }

    // ========================================================================
    // VIEW DATA: Memastikan data categories dikirim ke view
    // ========================================================================

    /**
     * TC-FILTER-15: View menerima data 'categories' untuk dropdown filter.
     * 
     * Expected: View memiliki variabel $categories dengan 4 kategori
     */
    public function test_view_menerima_data_categories(): void
    {
        $response = $this->actingAsSellerGet();

        $response->assertStatus(200);
        $response->assertViewHas('categories');
        
        $categories = $response->viewData('categories');
        $this->assertEquals(4, $categories->count());
        
        $categoryNames = $categories->pluck('name')->toArray();
        $this->assertContains('Elektronik', $categoryNames);
        $this->assertContains('Fashion', $categoryNames);
        $this->assertContains('Makanan & Minuman', $categoryNames);
        $this->assertContains('Buku & Alat Tulis', $categoryNames);
    }

    /**
     * TC-FILTER-16: Filter tetap mempertahankan data categories di view.
     */
    public function test_filter_tetap_mengirim_categories_ke_view(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryElektronik->id]);

        $response->assertStatus(200);
        $response->assertViewHas('categories');
        
        $categories = $response->viewData('categories');
        $this->assertEquals(4, $categories->count());
    }

    // ========================================================================
    // PAGINATION: Filter + Pagination
    // ========================================================================

    /**
     * TC-FILTER-17: Pagination bekerja dengan filter kategori.
     * 
     * Tambah 15 produk Elektronik untuk memicu pagination (10 per halaman)
     * Expected: Halaman 1 = 10 produk, halaman 2 = sisa produk
     */
    public function test_pagination_dengan_filter_kategori(): void
    {
        // Tambah 15 produk Elektronik (total Elektronik jadi 18)
        for ($i = 1; $i <= 15; $i++) {
            $this->createProduct(
                "Produk Elektronik Extra {$i}",
                "SKU-ELEC-EXTRA-{$i}",
                $this->seller,
                $this->categoryElektronik,
                100000 + ($i * 10000)
            );
        }

        // Halaman 1
        $response = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'page' => 1
        ]);
        
        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertEquals(10, $products->count()); // 10 per halaman
        $this->assertEquals(18, $products->total());  // Total 3 awal + 15 extra

        // Halaman 2
        $response2 = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'page' => 2
        ]);
        
        $response2->assertStatus(200);
        $products2 = $response2->viewData('products');
        $this->assertEquals(8, $products2->count()); // Sisa 8 produk
    }

    /**
     * TC-FILTER-18: withQueryString mempertahankan parameter category di pagination.
     */
    public function test_pagination_mempertahankan_filter_kategori(): void
    {
        // Tambah 15 produk Elektronik
        for ($i = 1; $i <= 15; $i++) {
            $this->createProduct(
                "Extra Elektronik {$i}",
                "SKU-EXTRA-ELEC-{$i}",
                $this->seller,
                $this->categoryElektronik,
                100000
            );
        }

        $response = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'page' => 1
        ]);

        $response->assertStatus(200);
        
        // Cek bahwa URL pagination mengandung parameter category
        $content = $response->getContent();
        $this->assertStringContainsString(
            "category={$this->categoryElektronik->id}",
            $content,
            'Pagination link harus mempertahankan parameter category'
        );
    }

    // ========================================================================
    // EDGE CASES: Nilai batas dan input aneh
    // ========================================================================

    /**
     * TC-FILTER-19: Filter dengan category = 0 (nol).
     * 
     * Expected: 0 produk (karena tidak ada kategori dengan ID 0)
     */
    public function test_filter_kategori_id_nol(): void
    {
        $response = $this->actingAsSellerGet(['category' => 0]);

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertEquals(0, $products->total());
    }

    /**
     * TC-FILTER-20: Filter dengan category = null (parameter ada tapi kosong).
     * 
     * Expected: Perilaku seperti tidak ada filter (semua produk tampil)
     * Note: $request->get('category') untuk nilai kosong return null
     */
    public function test_filter_kategori_param_kosong(): void
    {
        $response = $this->actingAsSellerGet(['category' => '']);

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        // Parameter kosong dianggap tidak ada filter
        $this->assertEquals(8, $products->total());
    }

    /**
     * TC-FILTER-21: Filter dengan category ID negatif.
     * 
     * Expected: 0 produk (tidak ada category_id negatif)
     */
    public function test_filter_kategori_id_negatif(): void
    {
        $response = $this->actingAsSellerGet(['category' => -5]);

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertEquals(0, $products->total());
    }

    /**
     * TC-FILTER-22: Kombinasi filter + search + pagination bersamaan.
     * 
     * Simulasi penggunaan nyata: filter Elektronik, search 'Mouse', halaman 1
     */
    public function test_kombinasi_filter_search_pagination(): void
    {
        // Tambah beberapa produk Mouse di Elektronik
        for ($i = 1; $i <= 5; $i++) {
            $this->createProduct(
                "Mouse Gaming {$i}",
                "SKU-MOUSE-GAMING-{$i}",
                $this->seller,
                $this->categoryElektronik,
                200000 + ($i * 50000)
            );
        }
        
        // Tambah produk Mouse di kategori lain (tidak boleh keluar)
        $this->createProduct(
            "Mouse Pad",
            "SKU-MOUSEPAD-001",
            $this->seller,
            $this->categoryFashion,
            50000
        );

        $response = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'search' => 'Mouse',
            'page' => 1
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        // Hanya produk dengan kata 'Mouse' di kategori Elektronik
        // 1 (Mouse Logitech original) + 5 (Mouse Gaming) = 6 produk
        $this->assertEquals(6, $products->total());
        
        // Pastikan semua produk adalah Elektronik dan mengandung kata 'Mouse'
        foreach ($products as $product) {
            $this->assertEquals($this->categoryElektronik->id, $product->category_id);
            $this->assertStringContainsStringIgnoringCase('Mouse', $product->name);
        }
    }

    // ========================================================================
    // KATEGORI TANPA PRODUK
    // ========================================================================

    /**
     * TC-FILTER-23: Filter kategori yang TIDAK memiliki produk apapun.
     * 
     * Buat kategori baru tanpa produk, lalu filter dengan ID tersebut
     * Expected: 0 produk, halaman tetap 200
     */
    public function test_filter_kategori_tanpa_produk(): void
    {
        $emptyCategory = Category::create([
            'name' => 'Kategori Kosong',
            'slug' => 'kategori-kosong',
        ]);

        $response = $this->actingAsSellerGet(['category' => $emptyCategory->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertEquals(0, $products->total());
    }

    // ========================================================================
    // MULTIPLE PARAMETER: Parameter lain tidak mengganggu filter
    // ========================================================================

    /**
     * TC-FILTER-24: Parameter tambahan (sort, order) tidak mengganggu filter.
     * 
     * Simulasi jika nanti ada fitur sorting
     */
    public function test_filter_dengan_parameter_tambahan(): void
    {
        $response = $this->actingAsSellerGet([
            'category' => $this->categoryElektronik->id,
            'sort' => 'price',
            'order' => 'desc'
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        // Filter tetap bekerja meskipun ada parameter tambahan
        $this->assertEquals(3, $products->total());
        
        foreach ($products as $product) {
            $this->assertEquals($this->categoryElektronik->id, $product->category_id);
        }
    }
}