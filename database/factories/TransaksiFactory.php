<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaksi_code' => 'TRX-' . strtoupper(fake()->unique()->bothify('??????')),
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'service_type' => fake()->randomElement(['regular', 'express']),
            'weight' => fake()->randomFloat(1, 1, 10),
            'price_per_kg' => fake()->randomFloat(2, 5000, 10000),
            'total_price' => fake()->randomFloat(2, 10000, 100000),
            'status' => fake()->randomElement(['diterima', 'disortir', 'dicuci', 'dikeringkan', 'disetrika', 'dipacking', 'selesai', 'diambil']),
            'payment_status' => fake()->randomElement(['belum_bayar', 'lunas']),
            'payment_method' => fake()->randomElement(['tunai', 'qris', 'transfer', 'cash', 'dana']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
