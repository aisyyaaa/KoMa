<?php

namespace Tests\Feature\KoMa;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-07-01 | Menampilkan laporan penjualan
 * SKPL: SRS-KOMA-08
 * Kelas Uji: Pengujian Laporan dan Akun
 */
class DUPL0701_LaporanPenjualanTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = $this->makeSeller();
        $category     = $this->makeCategory(['name' => 'Fashion', 'slug' => 'fashion']);

        $this->makeProduct($this->seller, $category, [
            'name'  => 'Kaos Polos Putih',
            'slug'  => 'kaos-polos-putih',
            'stock' => 100,
            'price' => 75000,
        ]);

        $this->makeProduct($this->seller, $category, [
            'name'  => 'Celana Jogger',
            'slug'  => 'celana-jogger',
            'stock' => 1, // stok rendah untuk low_stock report
            'price' => 120000,
        ]);
    }

    public function test_laporan_stok_per_kuantitas_dapat_diakses(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.reports.stock_by_quantity'));

        $response->assertStatus(200);
        $response->assertSee('Kaos Polos Putih');
        $response->assertSee('Celana Jogger');
    }

    public function test_laporan_stok_per_rating_dapat_diakses(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.reports.stock_by_rating'));

        $response->assertStatus(200);
        $response->assertSee('Kaos Polos Putih');
    }

    public function test_laporan_stok_rendah_dapat_diakses(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.reports.low_stock'));

        $response->assertStatus(200);
        $response->assertSee('Celana Jogger'); // stok 1 = rendah (< 2)
    }

    public function test_laporan_tidak_dapat_diakses_tanpa_login(): void
    {
        $response = $this->get(route('seller.reports.stock_by_quantity'));

        $response->assertRedirect();
    }

    public function test_laporan_hanya_menampilkan_produk_seller_yang_login(): void
    {
        $sellerLain = $this->makeSeller();
        $cat        = $this->makeCategory(['name' => 'Lainnya', 'slug' => 'lainnya']);
        $this->makeProduct($sellerLain, $cat, [
            'name' => 'Produk Saingan Rahasia',
            'slug' => 'produk-saingan-rahasia',
        ]);

        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.reports.stock_by_quantity'));

        $response->assertSee('Kaos Polos Putih');
        $response->assertDontSee('Produk Saingan Rahasia');
    }
}
