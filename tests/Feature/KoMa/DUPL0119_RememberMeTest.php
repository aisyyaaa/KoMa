<?php

namespace Tests\Feature\KoMa;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\KoMa\Concerns\CreatesTestData;
use Tests\TestCase;

/**
 * DUPL-01-19 | Login dengan fitur Remember Me
 * SKPL: SRS-KOMA-02
 * Kelas Uji: Pengujian Registrasi dan Login
 */
class DUPL0119_RememberMeTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seller = $this->makeSeller([
            'email'    => 'seller.remember@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);
    }

    public function test_halaman_login_seller_dapat_diakses(): void
    {
        $response = $this->get('/seller/auth/login');

        $response->assertStatus(200);
        $response->assertSee('remember');
    }

    public function test_login_berhasil_dengan_remember_me_dicentang(): void
    {
        $response = $this->post('/seller/auth/login', [
            'email'    => 'seller.remember@test.com',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('seller.dashboard'));
        $this->assertAuthenticatedAs($this->seller, 'seller');
    }

    public function test_sesi_seller_aktif_setelah_login(): void
    {
        $this->actingAs($this->seller, 'seller');

        $response = $this->get(route('seller.dashboard'));

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($this->seller, 'seller');
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $response = $this->post('/seller/auth/login', [
            'email'    => 'seller.remember@test.com',
            'password' => 'salah_password',
            'remember' => '1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('seller');
    }
}
