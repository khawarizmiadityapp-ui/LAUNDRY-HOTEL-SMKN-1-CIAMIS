<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id_transaksi' => 'EXP-2401',
                'nama'         => 'Stok Sabun Cuci 20L',
                'kategori'     => 'Bahan Baku',
                'keterangan'   => 'PENGADAAN BULANAN',
                'tanggal'      => '2023-10-15',
                'nominal'      => 850000,
                'status'       => 'lunas',
            ],
            [
                'id_transaksi' => 'EXP-2402',
                'nama'         => 'Service Mesin Cuci 2',
                'kategori'     => 'Perawatan',
                'keterangan'   => 'PEMELIHARAAN RUTIN',
                'tanggal'      => '2023-10-12',
                'nominal'      => 450000,
                'status'       => 'lunas',
            ],
            [
                'id_transaksi' => 'EXP-2403',
                'nama'         => 'Listrik & Air (Sept)',
                'kategori'     => 'Utilitas',
                'keterangan'   => 'BIAYA TETAP',
                'tanggal'      => '2023-10-05',
                'nominal'      => 1200000,
                'status'       => 'lunas',
            ],
            [
                'id_transaksi' => 'EXP-2404',
                'nama'         => 'Sewa Ruko Tahunan',
                'kategori'     => 'Sewa',
                'keterangan'   => 'ANGSURAN KE-3',
                'tanggal'      => '2023-10-02',
                'nominal'      => 5500000,
                'status'       => 'urgent',
            ],
            [
                'id_transaksi' => 'EXP-2405',
                'nama'         => 'Parfum Laundry 5L',
                'kategori'     => 'Bahan Baku',
                'keterangan'   => 'RESTOCK BULANAN',
                'tanggal'      => '2023-09-28',
                'nominal'      => 320000,
                'status'       => 'lunas',
            ],
            [
                'id_transaksi' => 'EXP-2406',
                'nama'         => 'Gaji Karyawan Oktober',
                'kategori'     => 'SDM',
                'keterangan'   => 'GAJI BULANAN',
                'tanggal'      => '2023-09-30',
                'nominal'      => 3500000,
                'status'       => 'lunas',
            ],
            [
                'id_transaksi' => 'EXP-2407',
                'nama'         => 'Plastik Kemas 500pcs',
                'kategori'     => 'Bahan Baku',
                'keterangan'   => 'OPERASIONAL',
                'tanggal'      => '2023-09-20',
                'nominal'      => 175000,
                'status'       => 'lunas',
            ],
            [
                'id_transaksi' => 'EXP-2408',
                'nama'         => 'Servis AC Ruangan',
                'kategori'     => 'Perawatan',
                'keterangan'   => 'PEMELIHARAAN',
                'tanggal'      => '2023-09-18',
                'nominal'      => 250000,
                'status'       => 'pending',
            ],
        ];

        foreach ($data as $item) {
            Pengeluaran::create($item);
        }
    }
}