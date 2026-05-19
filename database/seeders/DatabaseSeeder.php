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

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User']
        );

         // 1. Buat Admin Default
        User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Admin Laundry',
                'password' => Hash::make('password'), // Password: 'password'
                'role' => 'admin',
            ]
        );

        // 2. Buat Akun Petugas (Opsional)
        User::updateOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Petugas Kasir / CS',
                'password' => Hash::make('123456'), // Password: '123456'
                'role' => 'staff',
                'division' => 'customer_service',
            ]
        );

        User::updateOrCreate(
            ['email' => 'washing@laundry.com'],
            [
                'name' => 'Petugas Washing',
                'password' => Hash::make('123456'),
                'role' => 'staff',
                'division' => 'washing',
            ]
        );

        User::updateOrCreate(
            ['email' => 'setrika@laundry.com'],
            [
                'name' => 'Petugas Setrika',
                'password' => Hash::make('123456'),
                'role' => 'staff',
                'division' => 'setrika',
            ]
        );

        User::updateOrCreate(
            ['email' => 'packing@laundry.com'],
            [
                'name' => 'Petugas Packing',
                'password' => Hash::make('123456'),
                'role' => 'staff',
                'division' => 'packing',
            ]
        );

        User::updateOrCreate(
            ['email' => 'inventory@laundry.com'],
            [
                'name' => 'Petugas Inventory',
                'password' => Hash::make('123456'),
                'role' => 'staff',
                'division' => 'inventory',
            ]
        );

        // 3. Jalankan Seeder Lainnya (jika ada)
        $this->call([
            ServicePriceSeeder::class,
            LayananSeeder::class,
        ]);

        // 4. Seed Petugas Piket Default
        \App\Models\Petugas::updateOrCreate(
            ['id_petugas' => 'STF-0001'],
            [
                'nama' => 'Siti Aminah',
                'role' => 'Operasional',
                'status' => 'Aktif',
                'shift' => '-',
            ]
        );

        \App\Models\Petugas::updateOrCreate(
            ['id_petugas' => 'STF-0002'],
            [
                'nama' => 'Budi Santoso',
                'role' => 'Operasional',
                'status' => 'Aktif',
                'shift' => '-',
            ]
        );

        \App\Models\Petugas::updateOrCreate(
            ['id_petugas' => 'STF-0003'],
            [
                'nama' => 'Rian Hidayat',
                'role' => 'Operasional',
                'status' => 'Aktif',
                'shift' => '-',
            ]
        );

        \App\Models\Petugas::updateOrCreate(
            ['id_petugas' => 'STF-0004'],
            [
                'nama' => 'Dewi Lestari',
                'role' => 'Operasional',
                'status' => 'Aktif',
                'shift' => '-',
            ]
        );
    }
}
