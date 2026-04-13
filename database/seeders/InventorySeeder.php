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
                'name' => 'Bio-Enzyme Blue',
                'category' => 'detergent',
                'quantity' => 12,
                'type' => 'Heavy Duty',
            ],
            [
                'name' => 'Silk & Wool Mist',
                'category' => 'detergent',
                'quantity' => 48,
                'type' => 'Delicate',
            ],
            // Fragrances
            [
                'name' => 'Morning Meadow',
                'category' => 'fragrance',
                'quantity' => 8,
                'type' => 'Concentrated Oil',
            ],
            [
                'name' => 'Arctic Breeze',
                'category' => 'fragrance',
                'quantity' => 14,
                'type' => 'Refreshing Tone',
            ],
            [
                'name' => 'Sunset Vanilla',
                'category' => 'fragrance',
                'quantity' => 4,
                'type' => 'Warm Finish',
            ],
            [
                'name' => 'Pure Unscented',
                'category' => 'fragrance',
                'quantity' => 22,
                'type' => 'Hypoallergenic',
            ],
            // Hangers
            [
                'name' => 'Wooden Suit Hanger',
                'category' => 'hanger',
                'quantity' => 240,
                'type' => 'Premium Wood',
            ],
            [
                'name' => 'Standard Wire',
                'category' => 'hanger',
                'quantity' => 1200,
                'type' => 'Galvanized',
            ],
        ];

        foreach ($items as $item) {
            Inventory::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
