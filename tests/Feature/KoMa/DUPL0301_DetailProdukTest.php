<?php

namespace Tests\Feature\KoMa;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-03-01 | Menampilkan halaman detail produk
 * SKPL: SRS-KOMA-04
 * Kelas Uji: Pengujian Detail Produk
 */
class DUPL0301_DetailProdukTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $seller   = $this->makeSeller(['store_name' => 'Toko Detail Produk']);
        $category = $this->makeCategory(['name' => 'Alat Tulis', 'slug' => 'alat-tulis']);

        $this->product = $this->makeProduct($seller, $category, [
            'name'        => 'Spidol Whiteboard Merah',
            'slug'        => 'spidol-whiteboard-merah',
            'description' => 'Spidol berkualitas tinggi untuk whiteboard',
            'price'       => 15000,
        ]);
    }

    public function test_halaman_detail_produk_dapat_diakses(): void
    {
        $response = $this->get(route('katalog.show', $this->product));

        $response->assertStatus(200);
    }

    public function test_halaman_detail_menampilkan_nama_produk(): void
    {
        $response = $this->get(route('katalog.show', $this->product));

        $response->assertSee('Spidol Whiteboard Merah');
    }

    public function test_halaman_detail_menampilkan_harga_produk(): void
    {
        $response = $this->get(route('katalog.show', $this->product));

        $response->assertSee('15.000');
    }

    public function test_halaman_detail_menampilkan_deskripsi_produk(): void
    {
        $response = $this->get(route('katalog.show', $this->product));

        $response->assertSee('Spidol berkualitas tinggi untuk whiteboard');
    }

    public function test_halaman_detail_menampilkan_nama_toko(): void
    {
        $response = $this->get(route('katalog.show', $this->product));

        $response->assertSee('Toko Detail Produk');
    }

    public function test_halaman_detail_produk_tidak_ditemukan_mengembalikan_404(): void
    {
        $response = $this->get('/katalog/slug-produk-tidak-ada-xyz');

        $response->assertStatus(404);
    }
}
