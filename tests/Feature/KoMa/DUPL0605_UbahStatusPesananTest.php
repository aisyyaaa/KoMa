<?php

namespace Tests\Feature\KoMa;

use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-06-05 | Mengubah status pesanan menjadi diproses
 * SKPL: SRS-KOMA-07
 * Kelas Uji: Pengujian Pesanan
 */
class DUPL0605_UbahStatusPesananTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private Seller $seller;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = $this->makeSeller();
        $category     = $this->makeCategory();
        $product      = $this->makeProduct($this->seller, $category, ['price' => 25000]);
        $buyer        = User::factory()->create();

        $this->order = $this->makeOrder($buyer, $product, ['status' => 'Menunggu']);
    }

    public function test_halaman_daftar_pesanan_seller_dapat_diakses(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.orders.index'));

        $response->assertStatus(200);
    }

    public function test_halaman_detail_pesanan_menampilkan_data_pembeli(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->get(route('seller.orders.show', $this->order));

        $response->assertStatus(200);
        $response->assertSee('Menunggu');
        $response->assertSee('COD');
    }

    public function test_penjual_dapat_mengubah_status_menjadi_diproses(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->patch(route('seller.orders.update_status', $this->order), [
                             'status' => 'Diproses',
                         ]);

        $response->assertRedirect(route('seller.orders.show', $this->order));

        $this->assertDatabaseHas('orders', [
            'id'     => $this->order->id,
            'status' => 'Diproses',
        ]);
    }

    public function test_penjual_tidak_dapat_mengubah_status_ke_nilai_tidak_valid(): void
    {
        $response = $this->actingAs($this->seller, 'seller')
                         ->patch(route('seller.orders.update_status', $this->order), [
                             'status' => 'StatusTidakValid',
                         ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('orders', ['id' => $this->order->id, 'status' => 'Menunggu']);
    }

    public function test_penjual_lain_tidak_dapat_mengubah_pesanan_milik_seller_lain(): void
    {
        $sellerLain = $this->makeSeller();

        $response = $this->actingAs($sellerLain, 'seller')
                         ->patch(route('seller.orders.update_status', $this->order), [
                             'status' => 'Diproses',
                         ]);

        $response->assertStatus(403);
    }
}
