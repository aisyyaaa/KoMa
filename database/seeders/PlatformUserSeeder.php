<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PlatformUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            // Kunci unik: email
            ['email' => 'admin@koma.com'],
            [
                'name' => 'Master Admin Platform',
                'password' => Hash::make('password123'), // Gunakan password yang disarankan
                'role' => 'platform', // <<< PENTING: Gunakan role 'platform'
                'province' => 'DKI Jakarta', // Tambahkan data province yang wajib di Model
                // Hapus 'is_platform_admin' karena tidak ada di Model User Anda
            ]
        );
        
        $this->command->info('Akun Master Admin Platform berhasil dibuat: admin@koma.com / password123');
    }
}