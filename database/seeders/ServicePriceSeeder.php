<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\ServicePrice::create([
        'service_name' => 'Cuci Komplit Regular',
        'service_type' => 'regular',
        'price_per_kg' => 6000,
        'is_active' => true
    ]);

    \App\Models\ServicePrice::create([
        'service_name' => 'Cuci Komplit Express',
        'service_type' => 'express',
        'price_per_kg' => 10000,
        'is_active' => true
    ]);
}
}
