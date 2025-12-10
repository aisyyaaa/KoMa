<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a known buyer account
        User::updateOrCreate(
            ['email' => 'buyer@example.com'],
            [
                'name' => 'Buyer Test',
                'password' => Hash::make('password'),
            ]
        );

        // Create several random buyers using factory if available
        if (class_exists(User::class) && method_exists(User::class, 'factory')) {
            User::factory()->count(8)->create();
        }
    }
}
