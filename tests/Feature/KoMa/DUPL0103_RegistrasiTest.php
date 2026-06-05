<?php

namespace Tests\Feature\KoMa;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * DUPL-01-03 | Registrasi dengan semua field wajib diisi
 * SKPL: SRS-KOMA-01
 * Kelas Uji: Pengujian Registrasi dan Login
 */
class DUPL0103_RegistrasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_registrasi_dapat_diakses(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registrasi_berhasil_dengan_semua_field_valid(): void
    {
        Storage::fake('public');

        $response = $this->post('/register', [
            'email'                => 'toko.baru@test.com',
            'password'             => 'Password123!',
            'password_confirmation'=> 'Password123!',
            'nama_toko'            => 'Toko Unit Test',
            'deskripsi_singkat'    => 'Toko untuk keperluan pengujian otomatis',
            'nama_pic'             => 'Siti Rahayu',
            'no_hp_pic'            => '08111222333',
            'alamat_pic'           => 'Jl. Testing No. 99',
            'rt'                   => '003',
            'rw'                   => '007',
            'nama_kelurahan'       => 'Kelurahan Tes',
            'nama_kecamatan'       => 'Kecamatan Tes',
            'kabupaten_kota'       => 'Kota Tes',
            'propinsi'             => 'Jawa Tengah',
            'no_ktp_pic'           => '3311223344556677',
            'foto_pic'             => UploadedFile::fake()->image('foto_pic.jpg', 200, 200),
            'file_ktp_pic'         => UploadedFile::fake()->image('ktp.jpg', 200, 200),
        ]);

        // Seller tersimpan di database dengan status PENDING
        $this->assertDatabaseHas('sellers', [
            'email'      => 'toko.baru@test.com',
            'store_name' => 'Toko Unit Test',
            'status'     => 'PENDING',
            'is_active'  => 0,
        ]);

        // Diarahkan ke halaman verifikasi
        $response->assertRedirect(route('seller.auth.verify'));
    }

    public function test_registrasi_gagal_jika_email_sudah_terdaftar(): void
    {
        Storage::fake('public');

        // Buat seller pertama
        $this->post('/register', [
            'email'                => 'duplikat@test.com',
            'password'             => 'Password123!',
            'password_confirmation'=> 'Password123!',
            'nama_toko'            => 'Toko Pertama',
            'nama_pic'             => 'John Doe',
            'no_hp_pic'            => '08100000001',
            'alamat_pic'           => 'Jl. A No. 1',
            'rt' => '001', 'rw' => '001',
            'nama_kelurahan'  => 'Kel A', 'nama_kecamatan' => 'Kec A',
            'kabupaten_kota'  => 'Kota A', 'propinsi' => 'Jawa Barat',
            'no_ktp_pic'      => '1111222233334444',
            'foto_pic'        => UploadedFile::fake()->image('f.jpg'),
            'file_ktp_pic'    => UploadedFile::fake()->image('k.jpg'),
        ]);

        // Coba daftar lagi dengan email yang sama
        $response = $this->post('/register', [
            'email'                => 'duplikat@test.com',
            'password'             => 'Password123!',
            'password_confirmation'=> 'Password123!',
            'nama_toko'            => 'Toko Kedua',
            'nama_pic'             => 'Jane Doe',
            'no_hp_pic'            => '08100000002',
            'alamat_pic'           => 'Jl. B No. 2',
            'rt' => '002', 'rw' => '002',
            'nama_kelurahan'  => 'Kel B', 'nama_kecamatan' => 'Kec B',
            'kabupaten_kota'  => 'Kota B', 'propinsi' => 'Jawa Tengah',
            'no_ktp_pic'      => '5555666677778888',
            'foto_pic'        => UploadedFile::fake()->image('f2.jpg'),
            'file_ktp_pic'    => UploadedFile::fake()->image('k2.jpg'),
        ]);

        $response->assertSessionHasErrors('email');
    }
}
