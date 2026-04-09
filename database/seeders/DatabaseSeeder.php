<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
         // 1. Buat Admin Default
        User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password'), // Password: 'password'
            'role' => 'admin',
        ]);

        // 2. Buat Akun Petugas (Opsional)
        User::create([
            'name' => 'Petugas Kasir',
            'email' => 'petugas@laundry.com',
            'password' => Hash::make('123456'), // Password: '123456'
            'role' => 'staff',
        ]);

        // 3. Jalankan Seeder Lainnya (jika ada)
        $this->call([
            ServicePriceSeeder::class,
            LayananSeeder::class,
        ]);
    }
}
