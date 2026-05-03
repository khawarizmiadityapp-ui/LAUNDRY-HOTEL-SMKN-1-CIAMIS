<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasUserSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing test users
        User::whereIn('email', [
            'admin@test.com',
            'washing@test.com',
            'setrika@test.com',
            'packing@test.com',
            'cs@test.com',
            'inventory@test.com',
        ])->delete();

        // Admin User (dapat akses semua menu)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'division' => null, // Admin tidak perlu division
        ]);

        // Staff Washing
        User::create([
            'name' => 'Petugas Washing',
            'email' => 'washing@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'washing',
        ]);

        // Staff Setrika
        User::create([
            'name' => 'Petugas Setrika',
            'email' => 'setrika@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'setrika',
        ]);

        // Staff Packing
        User::create([
            'name' => 'Petugas Packing',
            'email' => 'packing@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'packing',
        ]);

        // Staff Customer Service
        User::create([
            'name' => 'Customer Service Staff',
            'email' => 'cs@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'customer_service',
        ]);

        // Staff Inventory
        User::create([
            'name' => 'Staff Inventory',
            'email' => 'inventory@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'inventory',
        ]);

        $this->command->info('Test users created successfully!');
    }
}
