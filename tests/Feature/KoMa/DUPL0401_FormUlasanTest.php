<?php

namespace Tests\Feature\KoMa;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-04-01 | Menampilkan form pengulasan produk
 * SKPL: SRS-KOMA-05
 * Kelas Uji: Pengujian Ulasan dan Rating
 */
class DUPL0401_FormUlasanTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $seller        = $this->makeSeller();
        $category      = $this->makeCategory(['name' => 'Makanan', 'slug' => 'makanan']);
        $this->product = $this->makeProduct($seller, $category, [
            'name'        => 'Keripik Tempe Pedas',
            'slug'        => 'keripik-tempe-pedas',
            'description' => 'Keripik tempe renyah dan pedas khas Jogja',
        ]);
    }

    public function test_form_ulasan_tersedia_di_halaman_detail_produk(): void
    {
        $response = $this->get(route('katalog.show', $this->product));

        $response->assertStatus(200);
        $response->assertSee('ulasan', false);
        $response->assertSee('rating', false);
        $response->assertSee('comment', false);
    }

    public function test_pengiriman_ulasan_valid_tersimpan_di_database(): void
    {
        $response = $this->post(route('katalog.review.store', $this->product), [
            'visitor_name'  => 'Pembeli Setia',
            'visitor_phone' => '08199988877',
            'visitor_email' => 'pembeli@test.com',
            'province'      => 'Jawa Tengah',
            'rating'        => 5,
            'comment'       => 'Produk sangat bagus dan cepat sampai!',
        ]);

        $this->assertDatabaseHas('reviews', [
            'product_id'    => $this->product->id,
            'visitor_name'  => 'Pembeli Setia',
            'visitor_email' => 'pembeli@test.com',
            'rating'        => 5,
        ]);
    }

    public function test_ulasan_gagal_jika_rating_tidak_diisi(): void
    {
        $response = $this->post(route('katalog.review.store', $this->product), [
            'visitor_name'  => 'Pembeli Setia',
            'visitor_phone' => '08199988877',
            'visitor_email' => 'pembeli@test.com',
            'province'      => 'Jawa Tengah',
            'comment'       => 'Produk bagus',
            // rating sengaja tidak diisi
        ]);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseMissing('reviews', ['visitor_email' => 'pembeli@test.com']);
    }

    public function test_ulasan_gagal_jika_nomor_hp_tidak_valid(): void
    {
        $response = $this->post(route('katalog.review.store', $this->product), [
            'visitor_name'  => 'Pembeli',
            'visitor_phone' => '12345',  // format tidak valid (bukan 08/+62)
            'visitor_email' => 'tes@test.com',
            'province'      => 'Jawa Tengah',
            'rating'        => 4,
            'comment'       => 'Oke banget produknya',
        ]);

        $response->assertSessionHasErrors('visitor_phone');
    }
}
