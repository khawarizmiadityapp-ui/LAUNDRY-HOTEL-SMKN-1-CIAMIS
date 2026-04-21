<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaksi::all();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Layanan',
            'Berat',
            'Total',
            'Status',
            'Pembayaran'
        ];
    }
}