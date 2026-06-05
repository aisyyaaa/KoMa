<?php

return [

    // ... (Bagian 'defaults' tetap sama) ...
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Menambahkan guard untuk Seller dan Platform
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        // 💡 GUARD BARU UNTUK PENJUAL
        'seller' => [
            'driver' => 'session',
            'provider' => 'sellers', // Menunjuk ke provider 'sellers'
        ],
        // 💡 GUARD BARU UNTUK PLATFORM ADMIN
        // Asumsi Platform Admin menggunakan Model terpisah (atau Model User dengan provider berbeda)
        'platform' => [
            'driver' => 'session',
            'provider' => 'users', // Asumsi Platform Admin adalah bagian dari Model User
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | Menambahkan Provider untuk Model Seller dan Platform
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class), // Untuk Pengguna Umum/Buyer & Platform Admin
        ],

        // 💡 PROVIDER BARU UNTUK PENJUAL (Model Seller)
        'sellers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seller::class, // Harus menunjuk ke Model\Seller Anda
        ],
    ],

    // ... (Bagian 'passwords' dan 'password_timeout' tetap sama) ...

];