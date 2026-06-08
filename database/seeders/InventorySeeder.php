<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Detergents
            [
                'name' => 'Bio-Enzyme Blue (Liquid)',
                'category' => 'detergent',
                'unit_type' => 'botol',
                'capacity_per_unit' => 1000,
                'unit_of_measurement' => 'ml',
                'stock_units' => 12,
                'stock_subunits' => 500,
                'quantity' => 12,
                'minimum_stock' => 5,
                'type' => 'Heavy Duty',
            ],
            [
                'name' => 'Silk & Wool Mist (Liquid)',
                'category' => 'detergent',
                'unit_type' => 'botol',
                'capacity_per_unit' => 1000,
                'unit_of_measurement' => 'ml',
                'stock_units' => 48,
                'stock_subunits' => 800,
                'quantity' => 48,
                'minimum_stock' => 10,
                'type' => 'Delicate',
            ],
            [
                'name' => 'Rinso Sachet',
                'category' => 'detergent',
                'unit_type' => 'sachet',
                'capacity_per_unit' => 20,
                'unit_of_measurement' => 'ml',
                'stock_units' => 150,
                'stock_subunits' => 0,
                'quantity' => 150,
                'minimum_stock' => 20,
                'type' => 'Sachet Powdery',
            ],
            // Fragrances
            [
                'name' => 'Morning Meadow (Liquid)',
                'category' => 'fragrance',
                'unit_type' => 'botol',
                'capacity_per_unit' => 1000,
                'unit_of_measurement' => 'ml',
                'stock_units' => 8,
                'stock_subunits' => 450,
                'quantity' => 8,
                'minimum_stock' => 3,
                'type' => 'Concentrated Oil',
            ],
            [
                'name' => 'Arctic Breeze Sachet',
                'category' => 'fragrance',
                'unit_type' => 'sachet',
                'capacity_per_unit' => 20,
                'unit_of_measurement' => 'ml',
                'stock_units' => 120,
                'stock_subunits' => 0,
                'quantity' => 120,
                'minimum_stock' => 15,
                'type' => 'Sachet Liquid',
            ],
            [
                'name' => 'Sunset Vanilla (Liquid)',
                'category' => 'fragrance',
                'unit_type' => 'botol',
                'capacity_per_unit' => 1000,
                'unit_of_measurement' => 'ml',
                'stock_units' => 4,
                'stock_subunits' => 200,
                'quantity' => 4,
                'minimum_stock' => 2,
                'type' => 'Warm Finish',
            ],
            // Hangers
            [
                'name' => 'Wooden Suit Hanger',
                'category' => 'hanger',
                'unit_type' => 'pcs',
                'capacity_per_unit' => 1,
                'unit_of_measurement' => 'pcs',
                'stock_units' => 240,
                'stock_subunits' => 0,
                'quantity' => 240,
                'minimum_stock' => 50,
                'type' => 'Premium Wood',
            ],
            [
                'name' => 'Standard Wire',
                'category' => 'hanger',
                'unit_type' => 'pcs',
                'capacity_per_unit' => 1,
                'unit_of_measurement' => 'pcs',
                'stock_units' => 1200,
                'stock_subunits' => 0,
                'quantity' => 1200,
                'minimum_stock' => 100,
                'type' => 'Galvanized',
            ],
        ];

        foreach ($items as $item) {
            Inventory::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
