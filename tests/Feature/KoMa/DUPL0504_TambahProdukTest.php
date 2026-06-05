<?php

namespace Tests\Feature\KoMa;

use App\Models\Category;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-05-04 | Menambahkan produk baru
 * SKPL: SRS-KOMA-06
 * Kelas Uji: Pengujian Manajemen Produk
 */
class DUPL0504_TambahProdukTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private Seller $seller;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller   = $this->makeSeller();
        $this->category = $this->makeCategory(['name' => 'Peralatan', 'slug' => 'peralatan']);
    }

    public function test_halaman_tambah_produk_dapat_diakses(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.products.create'));

        $response->assertStatus(200);
    }

    public function test_produk_baru_berhasil_ditambahkan_dengan_data_valid(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->seller, 'seller')
                         ->post(route('seller.products.store'), [
                             'category_id'          => $this->category->id,
                             'name'                 => 'Gunting Besi Premium',
                             'description'          => 'Gunting besi berkualitas tinggi untuk berbagai keperluan rumah tangga',
                             'price'                => 35000,
                             'stock'                => 50,
                             'sku'                  => 'GBP-001',
                             'condition'            => 'new',
                             'shipment_origin_city' => 'Semarang',
                             'primary_image'        => UploadedFile::fake()->image('produk.jpg', 400, 400),
                         ]);

        $this->assertDatabaseHas('products', [
            'name'      => 'Gunting Besi Premium',
            'seller_id' => $this->seller->id,
            'price'     => 35000,
            'stock'     => 50,
        ]);
    }

    public function test_produk_muncul_di_daftar_produk_setelah_ditambahkan(): void
    {
        Storage::fake('public');

        $this->actingAs($this->seller, 'seller')
             ->post(route('seller.products.store'), [
                 'category_id'          => $this->category->id,
                 'name'                 => 'Palu Kayu Kecil',
                 'description'          => 'Palu kayu ringan untuk pekerjaan rumah sehari-hari',
                 'price'                => 28000,
                 'stock'                => 25,
                 'sku'                  => 'PKK-001',
                 'condition'            => 'new',
                 'shipment_origin_city' => 'Semarang',
                 'primary_image'        => UploadedFile::fake()->image('palu.jpg'),
             ]);

        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Palu Kayu Kecil');
    }

    public function test_tambah_produk_gagal_jika_nama_kosong(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->seller, 'seller')
                         ->post(route('seller.products.store'), [
                             'category_id'          => $this->category->id,
                             'name'                 => '',
                             'description'          => 'Deskripsi cukup panjang untuk melewati validasi minimal dua puluh karakter',
                             'price'                => 10000,
                             'stock'                => 5,
                             'sku'                  => 'TEST-002',
                             'condition'            => 'new',
                             'shipment_origin_city' => 'Kota',
                             'primary_image'        => UploadedFile::fake()->image('test.jpg'),
                         ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('products', ['sku' => 'TEST-002']);
    }

    public function test_tambah_produk_tidak_dapat_diakses_tanpa_login(): void
    {
        $response = $this->post(route('seller.products.store'), [
            'name' => 'Produk Tanpa Login',
        ]);

        $response->assertRedirect();
    }
}
