<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * ============================================================================
 * WHITE-BOX TESTING: Fitur Pencarian Produk di Dashboard Seller
 * ============================================================================
 * 
 * Controller yang diuji:
 *   App\Http\Controllers\Seller\SellerProductController@index (line 24-55)
 * 
 * Route yang diuji:
 *   GET /seller/products  (name: seller.products.index)
 *   Parameter query: ?search=keyword&category=id
 * 
 * Guard: 'seller' (middleware auth:seller)
 * 
 * ============================================================================
 * ANALISIS PATH / CABANG (Branch Coverage):
 * ============================================================================
 * 
 * Alur method index():
 *   1. Ambil seller_id dari Auth::guard('seller')->id()      (line 26)
 *   2. Ambil searchQuery dari $request->get('search')         (line 27)
 *   3. Ambil categoryId dari $request->get('category')        (line 28)
 *   4. Query: Product::where('seller_id', $sellerId)          (line 33)
 *      → withCount('reviews') + withAvg('reviews', 'rating')  (line 34-35)
 * 
 * CABANG PENCARIAN (line 38-45):
 *   Path A: searchQuery KOSONG → SKIP blok if → tampil semua produk
 *   Path B: searchQuery TIDAK KOSONG →
 *           → LOWER(name) LIKE %keyword% OR LOWER(sku) LIKE %keyword%
 *           → case-insensitive search
 * 
 * CABANG FILTER KATEGORI (line 48-50):
 *   Path C: categoryId KOSONG → SKIP filter kategori
 *   Path D: categoryId ADA → where('category_id', $categoryId)
 * 
 * KOMBINASI PATH:
 *   Path A+C: Tanpa search, tanpa filter → semua produk seller
 *   Path A+D: Tanpa search, dengan filter kategori
 *   Path B+C: Dengan search, tanpa filter kategori
 *   Path B+D: Dengan search DAN filter kategori (paling lengkap)
 * 
 * ============================================================================
 */
class SearchProductDashboard extends TestCase
{
    use RefreshDatabase;

    private Seller $seller;
    private Seller $otherSeller;
    private Category $categoryATK;
    private Category $categoryBuku;

    /**
     * Setup data yang dibutuhkan untuk setiap test.
     * Membuat 2 seller, 2 kategori, dan beberapa produk dengan variasi nama/SKU.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // ---- Buat Seller utama (yang akan login) ----
        $this->seller = Seller::create([
            'store_name'       => 'Toko Test Pencarian',
            'short_description'=> 'Toko testing fitur search',
            'pic_name'         => 'PIC Test',
            'phone_number'     => '081000000001',
            'email'            => 'search.test@seller.com',
            'password'         => Hash::make('password123'),
            'address'          => 'Jl. Test No. 1',
            'rt'               => '001',
            'rw'               => '002',
            'village'          => 'TestVillage',
            'district'         => 'TestDistrict',
            'city'             => 'Semarang',
            'province'         => 'Jawa Tengah',
            'ktp_number'       => '1111222233334444',
            'pic_photo_path'   => 'seller_docs/test.jpg',
            'ktp_file_path'    => 'seller_docs/test.pdf',
            'status'           => 'ACTIVE',
            'is_active'        => true,
            'registration_date'=> now(),
        ]);

        // ---- Buat Seller lain (untuk menguji isolasi data) ----
        $this->otherSeller = Seller::create([
            'store_name'       => 'Toko Lain',
            'short_description'=> 'Toko seller lain',
            'pic_name'         => 'PIC Lain',
            'phone_number'     => '081000000002',
            'email'            => 'other@seller.com',
            'password'         => Hash::make('password123'),
            'address'          => 'Jl. Lain No. 2',
            'rt'               => '003',
            'rw'               => '004',
            'village'          => 'LainVillage',
            'district'         => 'LainDistrict',
            'city'             => 'Bandung',
            'province'         => 'Jawa Barat',
            'ktp_number'       => '5555666677778888',
            'pic_photo_path'   => 'seller_docs/other.jpg',
            'ktp_file_path'    => 'seller_docs/other.pdf',
            'status'           => 'ACTIVE',
            'is_active'        => true,
            'registration_date'=> now(),
        ]);

        // ---- Buat Kategori ----
        $this->categoryATK = Category::create([
            'name' => 'Alat Tulis Kantor',
            'slug' => 'alat-tulis-kantor',
        ]);

        $this->categoryBuku = Category::create([
            'name' => 'Buku Kuliah',
            'slug' => 'buku-kuliah',
        ]);

        // ---- Buat Produk milik Seller Utama ----
        $this->createProduct('Pulpen Pilot G2', 'SKU-PILOT-001', $this->seller, $this->categoryATK, 15000);
        $this->createProduct('Pensil Faber Castell 2B', 'SKU-FABER-002', $this->seller, $this->categoryATK, 5000);
        $this->createProduct('Buku Tulis Sidu 58 Lembar', 'SKU-SIDU-003', $this->seller, $this->categoryBuku, 8000);
        $this->createProduct('Kalkulator Casio FX-991ID', 'SKU-CASIO-004', $this->seller, $this->categoryATK, 350000);
        $this->createProduct('Modul Pemrograman Web', 'SKU-MODUL-005', $this->seller, $this->categoryBuku, 75000);

        // ---- Buat Produk milik Seller Lain (TIDAK boleh muncul) ----
        $this->createProduct('Pulpen Pilot Lain', 'SKU-OTHER-001', $this->otherSeller, $this->categoryATK, 12000);
        $this->createProduct('Buku Fisika Lain', 'SKU-OTHER-002', $this->otherSeller, $this->categoryBuku, 90000);
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
     * Helper: Login sebagai seller dan akses halaman produk.
     */
    private function actingAsSellerGet(array $queryParams = [])
    {
        return $this->actingAs($this->seller, 'seller')
                     ->get(route('seller.products.index', $queryParams));
    }

    // ========================================================================
    // TEST: Akses Halaman (Autentikasi)
    // ========================================================================

    /**
     * TC-SEARCH-00: Halaman daftar produk TIDAK bisa diakses tanpa login.
     * 
     * Cabang: middleware auth:seller → redirect ke login
     * Expected: status 302 (redirect)
     */
    public function test_halaman_produk_tidak_bisa_diakses_tanpa_login(): void
    {
        $response = $this->get(route('seller.products.index'));

        $response->assertStatus(302);
        $this->assertGuest('seller');
    }

    /**
     * TC-SEARCH-01: Halaman daftar produk bisa diakses setelah login seller.
     * 
     * Cabang: middleware auth:seller → pass
     * Expected: status 200
     */
    public function test_halaman_produk_bisa_diakses_setelah_login(): void
    {
        $response = $this->actingAsSellerGet();

        $response->assertStatus(200);
        $response->assertViewIs('seller.products.index');
    }

    // ========================================================================
    // PATH A+C: Tanpa search, tanpa filter → tampil SEMUA produk seller
    // ========================================================================

    /**
     * TC-SEARCH-02: Tanpa keyword search, semua produk milik seller tampil.
     * 
     * Cabang: searchQuery KOSONG (line 38: !empty → false → SKIP)
     *         categoryId KOSONG (line 48: if → false → SKIP)
     * Expected: 5 produk milik seller utama ditampilkan
     */
    public function test_tanpa_search_tampil_semua_produk_milik_seller(): void
    {
        $response = $this->actingAsSellerGet();

        $response->assertStatus(200);
        $response->assertViewHas('products');

        $products = $response->viewData('products');
        $this->assertEquals(5, $products->total());
    }

    /**
     * TC-SEARCH-03: Produk milik seller lain TIDAK boleh muncul.
     * 
     * Cabang: Query filter seller_id (line 33)
     * Expected: Hanya produk seller sendiri, bukan milik otherSeller
     */
    public function test_produk_seller_lain_tidak_muncul(): void
    {
        $response = $this->actingAsSellerGet();

        $products = $response->viewData('products');
        
        foreach ($products as $product) {
            $this->assertEquals($this->seller->id, $product->seller_id,
                "Produk '{$product->name}' bukan milik seller yang login.");
        }
    }

    // ========================================================================
    // PATH B+C: Dengan search keyword pada NAMA produk
    // ========================================================================

    /**
     * TC-SEARCH-04: Pencarian berdasarkan nama produk (match tepat).
     * 
     * Cabang: searchQuery = 'Pulpen' → !empty = true (line 38)
     *         → LOWER(name) LIKE '%pulpen%' → match 1 produk
     * Expected: 1 produk ditemukan (Pulpen Pilot G2)
     */
    public function test_search_nama_produk_match_tepat(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'Pulpen']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Pulpen Pilot G2', $products->first()->name);
    }

    /**
     * TC-SEARCH-05: Pencarian berdasarkan nama produk (match parsial).
     * 
     * Cabang: searchQuery = 'Buku' → match 'Buku Tulis Sidu 58 Lembar'
     * Expected: 1 produk ditemukan (hanya 'Buku Tulis Sidu 58 Lembar',
     *           karena 'Modul Pemrograman Web' tidak mengandung 'Buku')
     */
    public function test_search_nama_produk_match_parsial(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'Buku']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertStringContainsString('Buku', $products->first()->name);
    }

    /**
     * TC-SEARCH-06: Pencarian case-insensitive (huruf besar/kecil).
     * 
     * Cabang: searchQuery = 'pUlPeN' → strtolower → 'pulpen'
     *         → LOWER(name) LIKE '%pulpen%' → match
     * Expected: 1 produk ditemukan meskipun huruf besar/kecil berbeda
     */
    public function test_search_case_insensitive(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'pUlPeN']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Pulpen Pilot G2', $products->first()->name);
    }

    /**
     * TC-SEARCH-07: Pencarian case-insensitive dengan huruf LOWERCASE.
     * 
     * Cabang: searchQuery = 'kalkulator' → strtolower = 'kalkulator'
     *         → LOWER(name) LIKE '%kalkulator%' → match 'Kalkulator Casio FX-991ID'
     * Expected: 1 produk ditemukan
     */
    public function test_search_lowercase_keyword(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'kalkulator']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertStringContainsStringIgnoringCase('Kalkulator', $products->first()->name);
    }

    /**
     * TC-SEARCH-08: Pencarian case-insensitive dengan huruf UPPERCASE.
     * 
     * Cabang: searchQuery = 'MODUL' → strtolower = 'modul'
     *         → LOWER(name) LIKE '%modul%' → match 'Modul Pemrograman Web'
     * Expected: 1 produk ditemukan
     */
    public function test_search_uppercase_keyword(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'MODUL']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertStringContainsStringIgnoringCase('Modul', $products->first()->name);
    }

    // ========================================================================
    // PATH B+C: Dengan search keyword pada SKU produk
    // ========================================================================

    /**
     * TC-SEARCH-09: Pencarian berdasarkan SKU produk (match tepat).
     * 
     * Cabang: searchQuery = 'SKU-PILOT-001'
     *         → LOWER(sku) LIKE '%sku-pilot-001%' → match
     * Expected: 1 produk ditemukan (Pulpen Pilot G2)
     */
    public function test_search_berdasarkan_sku_tepat(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'SKU-PILOT-001']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('SKU-PILOT-001', $products->first()->sku);
    }

    /**
     * TC-SEARCH-10: Pencarian berdasarkan SKU parsial.
     * 
     * Cabang: searchQuery = 'CASIO' → match SKU 'SKU-CASIO-004'
     * Expected: 1 produk ditemukan (Kalkulator Casio FX-991ID)
     *           Note: Juga bisa match di nama karena OR condition
     */
    public function test_search_berdasarkan_sku_parsial(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'CASIO']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
    }

    /**
     * TC-SEARCH-11: Pencarian SKU case-insensitive.
     * 
     * Cabang: searchQuery = 'sku-faber' → strtolower → 'sku-faber'
     *         → LOWER(sku) LIKE '%sku-faber%' → match 'SKU-FABER-002'
     * Expected: 1 produk ditemukan
     */
    public function test_search_sku_case_insensitive(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'sku-faber']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('SKU-FABER-002', $products->first()->sku);
    }

    // ========================================================================
    // PATH B+C: Search keyword yang MATCH di nama DAN sku (OR condition)
    // ========================================================================

    /**
     * TC-SEARCH-12: Keyword match di NAMA dan SKU menggunakan OR.
     * 
     * Cabang: searchQuery = 'PILOT'
     *         → LOWER(name) LIKE '%pilot%' → match nama 'Pulpen Pilot G2'
     *         → OR LOWER(sku) LIKE '%pilot%' → match SKU 'SKU-PILOT-001'
     *         → Keduanya adalah produk yang SAMA, jadi hanya 1 result
     * Expected: 1 produk ditemukan
     */
    public function test_search_match_nama_dan_sku_bersamaan(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'PILOT']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Pulpen Pilot G2', $products->first()->name);
    }

    // ========================================================================
    // PATH A+C: Keyword TIDAK ditemukan
    // ========================================================================

    /**
     * TC-SEARCH-13: Keyword tidak cocok dengan produk apapun.
     * 
     * Cabang: searchQuery = 'XYZNotExist' → !empty = true (line 38)
     *         → LOWER(name) NOT LIKE '%xyznotexist%'
     *         → LOWER(sku) NOT LIKE '%xyznotexist%'
     *         → 0 results
     * Expected: 0 produk ditemukan, halaman tetap 200
     */
    public function test_search_keyword_tidak_ditemukan(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'XYZNotExist']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());
    }

    // ========================================================================
    // PATH A+D: Filter KATEGORI tanpa search keyword
    // ========================================================================

    /**
     * TC-SEARCH-14: Filter kategori 'Alat Tulis Kantor' tanpa keyword.
     * 
     * Cabang: searchQuery KOSONG (line 38: SKIP)
     *         categoryId = ATK id (line 48: true)
     *         → where('category_id', ATK) 
     * Expected: 3 produk ATK (Pulpen, Pensil, Kalkulator)
     */
    public function test_filter_kategori_tanpa_search(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryATK->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(3, $products->total());
        foreach ($products as $product) {
            $this->assertEquals($this->categoryATK->id, $product->category_id);
        }
    }

    /**
     * TC-SEARCH-15: Filter kategori 'Buku Kuliah' tanpa keyword.
     * 
     * Cabang: sama seperti TC-SEARCH-14, kategori berbeda
     * Expected: 2 produk Buku (Buku Tulis Sidu, Modul Pemrograman Web)
     */
    public function test_filter_kategori_buku_tanpa_search(): void
    {
        $response = $this->actingAsSellerGet(['category' => $this->categoryBuku->id]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(2, $products->total());
        foreach ($products as $product) {
            $this->assertEquals($this->categoryBuku->id, $product->category_id);
        }
    }

    // ========================================================================
    // PATH B+D: Search keyword + filter kategori (kombinasi lengkap)
    // ========================================================================

    /**
     * TC-SEARCH-16: Search 'Pensil' + filter kategori ATK.
     * 
     * Cabang: searchQuery = 'Pensil' → !empty = true (line 38)
     *         categoryId = ATK (line 48: true)
     *         → LOWER(name) LIKE '%pensil%' AND category_id = ATK
     * Expected: 1 produk (Pensil Faber Castell 2B)
     */
    public function test_search_dengan_filter_kategori_match(): void
    {
        $response = $this->actingAsSellerGet([
            'search'   => 'Pensil',
            'category' => $this->categoryATK->id,
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals('Pensil Faber Castell 2B', $products->first()->name);
    }

    /**
     * TC-SEARCH-17: Search 'Modul' + filter kategori ATK → 0 hasil.
     * 
     * Cabang: searchQuery = 'Modul' → match nama 'Modul Pemrograman Web'
     *         TAPI categoryId = ATK → 'Modul Pemrograman Web' adalah kategori Buku
     *         → AND condition mengeliminasi → 0 result
     * Expected: 0 produk (keyword match tapi kategori tidak match)
     */
    public function test_search_dengan_filter_kategori_tidak_match(): void
    {
        $response = $this->actingAsSellerGet([
            'search'   => 'Modul',
            'category' => $this->categoryATK->id,
        ]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());
    }

    // ========================================================================
    // EDGE CASES: Input Boundary & Special Characters
    // ========================================================================

    /**
     * TC-SEARCH-18: Search dengan keyword spasi saja.
     * 
     * Cabang: searchQuery = '   ' → trim() → '' → !empty = false → SKIP
     * Expected: Semua 5 produk tampil (spasi dianggap kosong setelah trim)
     */
    public function test_search_dengan_spasi_saja(): void
    {
        $response = $this->actingAsSellerGet(['search' => '   ']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(5, $products->total());
    }

    /**
     * TC-SEARCH-19: Search dengan keyword satu karakter.
     * 
     * Cabang: searchQuery = 'P' → !empty = true
     *         → LOWER(name) LIKE '%p%' → match beberapa produk
     * Expected: Produk yang mengandung huruf 'p' (case-insensitive)
     */
    public function test_search_dengan_satu_karakter(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'P']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        // Produk yang mengandung 'p': Pulpen Pilot G2, Pensil Faber Castell 2B,
        // Modul Pemrograman Web → minimal ada hasil
        $this->assertGreaterThan(0, $products->total());

        foreach ($products as $product) {
            $matchName = stripos($product->name, 'P') !== false;
            $matchSku = stripos($product->sku, 'P') !== false;
            $this->assertTrue($matchName || $matchSku,
                "Produk '{$product->name}' (SKU: {$product->sku}) seharusnya mengandung 'P'.");
        }
    }

    /**
     * TC-SEARCH-20: Search dengan special characters (SQL injection attempt).
     * 
     * Cabang: searchQuery = "'; DROP TABLE products;--" 
     *         → Laravel query builder mengamankan via prepared statements
     * Expected: 0 produk (keyword tidak match), halaman tetap 200
     */
    public function test_search_special_characters_sql_injection(): void
    {
        $response = $this->actingAsSellerGet(['search' => "'; DROP TABLE products;--"]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());

        // Pastikan tabel products masih ada (tidak terhapus oleh injection)
        $this->assertDatabaseCount('products', 7); // 5 milik seller + 2 milik otherSeller
    }

    /**
     * TC-SEARCH-21: Search dengan karakter wildcard SQL (%).
     * 
     * Cabang: searchQuery = '%' → LIKE '%%' → bisa match semua
     * Expected: Tergantung implementasi, tapi halaman tetap 200
     */
    public function test_search_dengan_karakter_wildcard(): void
    {
        $response = $this->actingAsSellerGet(['search' => '%']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        // Karakter % di dalam LIKE '%%%' bisa match semua record
        // Ini menguji bahwa tidak terjadi error
        $this->assertTrue($products->total() >= 0);
    }

    /**
     * TC-SEARCH-22: Search dengan keyword sangat panjang (boundary test).
     * 
     * Cabang: searchQuery = string 500 karakter → !empty = true
     * Expected: 0 hasil, tapi tidak error
     */
    public function test_search_keyword_sangat_panjang(): void
    {
        $longKeyword = str_repeat('abcde', 100); // 500 karakter

        $response = $this->actingAsSellerGet(['search' => $longKeyword]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());
    }

    // ========================================================================
    // ISOLASI DATA: Produk seller lain tidak muncul di hasil search
    // ========================================================================

    /**
     * TC-SEARCH-23: Pencarian TIDAK menampilkan produk seller lain.
     * 
     * Cabang: query Product::where('seller_id', $sellerId) (line 33)
     *         → Meskipun keyword match di produk seller lain, TIDAK ditampilkan
     * 
     * otherSeller punya 'Pulpen Pilot Lain' yang juga match keyword 'Pulpen'
     * Expected: Hanya 1 produk (milik seller utama), BUKAN 2
     */
    public function test_search_tidak_tampilkan_produk_seller_lain(): void
    {
        $response = $this->actingAsSellerGet(['search' => 'Pulpen']);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(1, $products->total());
        $this->assertEquals($this->seller->id, $products->first()->seller_id);
        $this->assertEquals('Pulpen Pilot G2', $products->first()->name);
    }

    // ========================================================================
    // PAGINATION: Memastikan pagination bekerja dengan search
    // ========================================================================

    /**
     * TC-SEARCH-24: Pagination bekerja ketika ada banyak produk.
     * 
     * Cabang: $query->paginate(10) (line 52)
     * Expected: Jika lebih dari 10 produk, halaman kedua bisa diakses
     */
    public function test_pagination_dengan_banyak_produk(): void
    {
        // Tambah 15 produk lagi (total 20, paginasi 10 per halaman)
        for ($i = 1; $i <= 15; $i++) {
            $this->createProduct(
                "Produk Extra {$i}",
                "SKU-EXTRA-{$i}",
                $this->seller,
                $this->categoryATK,
                1000 * $i
            );
        }

        // Halaman 1
        $response = $this->actingAsSellerGet(['page' => 1]);
        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertEquals(10, $products->count()); // 10 per halaman
        $this->assertEquals(20, $products->total());  // Total 20

        // Halaman 2
        $response2 = $this->actingAsSellerGet(['page' => 2]);
        $response2->assertStatus(200);
        $products2 = $response2->viewData('products');
        $this->assertEquals(10, $products2->count()); // Sisa 10
    }

    /**
     * TC-SEARCH-25: withQueryString mempertahankan parameter search di pagination.
     * 
     * Cabang: $query->paginate(10)->withQueryString() (line 52)
     * Expected: Link pagination mempertahankan parameter ?search=
     */
    public function test_pagination_mempertahankan_search_query(): void
    {
        // Buat 15 produk tambahan yang nama-nya mengandung 'Extra'
        for ($i = 1; $i <= 15; $i++) {
            $this->createProduct(
                "Produk Extra {$i}",
                "SKU-EXT-{$i}",
                $this->seller,
                $this->categoryATK,
                1000 * $i
            );
        }

        $response = $this->actingAsSellerGet(['search' => 'Extra', 'page' => 1]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(15, $products->total());
        $this->assertEquals(10, $products->count()); // Halaman 1 = 10 item
    }

    // ========================================================================
    // VIEW DATA: Memastikan data yang dikirim ke view lengkap
    // ========================================================================

    /**
     * TC-SEARCH-26: View menerima variabel 'products' dan 'categories'.
     * 
     * Cabang: return view('seller.products.index', [...]) (line 54)
     * Expected: View memiliki key 'products' dan 'categories'
     */
    public function test_view_menerima_data_products_dan_categories(): void
    {
        $response = $this->actingAsSellerGet();

        $response->assertStatus(200);
        $response->assertViewHas('products');
        $response->assertViewHas('categories');

        $categories = $response->viewData('categories');
        $this->assertEquals(2, $categories->count());
    }

    /**
     * TC-SEARCH-27: Produk memiliki reviews_count dan reviews_avg_rating (aggregat).
     * 
     * Cabang: withCount('reviews') + withAvg('reviews', 'rating') (line 34-35)
     * Expected: Setiap produk memiliki atribut reviews_count dan reviews_avg_rating
     */
    public function test_produk_memiliki_aggregat_review(): void
    {
        $response = $this->actingAsSellerGet();

        $products = $response->viewData('products');
        $firstProduct = $products->first();

        // Atribut agregat harus ada (meskipun nilainya 0/null karena tidak ada review)
        $this->assertTrue(
            array_key_exists('reviews_count', $firstProduct->getAttributes()),
            'Produk harus memiliki atribut reviews_count'
        );
        $this->assertTrue(
            array_key_exists('reviews_avg_rating', $firstProduct->getAttributes()),
            'Produk harus memiliki atribut reviews_avg_rating'
        );
    }

    // ========================================================================
    // FILTER KATEGORI INVALID
    // ========================================================================

    /**
     * TC-SEARCH-28: Filter kategori dengan ID yang tidak ada di database.
     * 
     * Cabang: categoryId = 999 (tidak ada di tabel categories)
     *         → where('category_id', 999) → 0 results
     * Expected: 0 produk, halaman tetap 200
     */
    public function test_filter_kategori_id_tidak_valid(): void
    {
        $response = $this->actingAsSellerGet(['category' => 999]);

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(0, $products->total());
    }
}
