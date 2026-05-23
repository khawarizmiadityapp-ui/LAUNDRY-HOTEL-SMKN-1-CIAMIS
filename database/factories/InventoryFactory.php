<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'category' => fake()->randomElement(['detergent', 'fragrance', 'packaging']),
            'quantity' => fake()->numberBetween(10, 100),
            'type' => fake()->randomElement(['consumable', 'equipment']),
        ];
    }
}
