<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'nama'     => 'Cuci Kiloan Regular',
                'kategori' => 'kiloan',
                'harga'    => 7000,
                'estimasi' => '2-3 hari pengerjaan',
                'status'   => true,
                'badge'    => 'Lunas',
                'icon'     => 'hourglass',
            ],
            [
                'nama'     => 'Cuci Kiloan Express',
                'kategori' => 'kiloan',
                'harga'    => 12000,
                'estimasi' => '1 hari pengerjaan',
                'status'   => true,
                'badge'    => 'Populer',
                'icon'     => 'bolt',
            ],
            [
                'nama'     => 'Cuci Satuan - Jas',
                'kategori' => 'satuan',
                'harga'    => 25000,
                'estimasi' => 'Specialist handling',
                'status'   => true,
                'badge'    => null,
                'icon'     => 'shirt',
            ],
            [
                'nama'     => 'Cuci Satuan - Bed Cover',
                'kategori' => 'satuan',
                'harga'    => 45000,
                'estimasi' => 'Standard drying time',
                'status'   => true,
                'badge'    => null,
                'icon'     => 'bed',
            ],
            [
                'nama'     => 'Cuci Sepatu',
                'kategori' => 'satuan',
                'harga'    => 35000,
                'estimasi' => '2 hari pengerjaan',
                'status'   => true,
                'badge'    => null,
                'icon'     => 'shoe',
            ],
            [
                'nama'     => 'Dry Cleaning',
                'kategori' => 'satuan',
                'harga'    => 55000,
                'estimasi' => '3 hari pengerjaan',
                'status'   => false,
                'badge'    => null,
                'icon'     => 'droplet',
            ],
        ];

        foreach ($layanans as $data) {
            Layanan::create($data);
        }
    }
}
