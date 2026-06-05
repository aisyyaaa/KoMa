<?php

namespace Tests\Feature\KoMa;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-02-03 | Menampilkan produk sesuai keyword ketika pengguna melakukan pencarian
 * SKPL: SRS-KOMA-03
 * Kelas Uji: Pengujian Halaman Utama, Pencarian dan Filter Produk
 */
class DUPL0203_PencarianProdukTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    protected function setUp(): void
    {
        parent::setUp();

        $seller   = $this->makeSeller();
        $category = $this->makeCategory(['name' => 'Buku', 'slug' => 'buku']);

        $this->makeProduct($seller, $category, [
            'name'        => 'Buku Matematika Dasar',
            'slug'        => 'buku-matematika-dasar',
            'description' => 'Buku untuk belajar matematika tingkat dasar',
        ]);

        $this->makeProduct($seller, $category, [
            'name'        => 'Pensil 2B',
            'slug'        => 'pensil-2b',
            'description' => 'Pensil untuk menulis dan menggambar',
        ]);
    }

    public function test_halaman_utama_dapat_diakses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_pencarian_menampilkan_produk_sesuai_keyword(): void
    {
        $response = $this->get('/?q=matematika');

        $response->assertStatus(200);
        $response->assertSee('Buku Matematika Dasar');
        $response->assertDontSee('Pensil 2B');
    }

    public function test_pencarian_tidak_menemukan_produk_yang_tidak_ada(): void
    {
        $response = $this->get('/?q=laptop-gaming-xyz-tidak-ada');

        $response->assertStatus(200);
        $response->assertDontSee('Buku Matematika Dasar');
        $response->assertDontSee('Pensil 2B');
    }

    public function test_halaman_tanpa_pencarian_menampilkan_semua_produk(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Buku Matematika Dasar');
        $response->assertSee('Pensil 2B');
    }
}
