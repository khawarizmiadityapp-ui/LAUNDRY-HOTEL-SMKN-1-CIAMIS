<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Transaksi::with(['user', 'customer', 'details.layanan']);
        
        if ($this->filter == 'bulan_ini') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($this->filter == 'target') {
            $query->where('payment_status', 'lunas');
        } elseif ($this->filter == 'tahun_ini') {
            $query->whereYear('created_at', now()->year);
        }

        return $query->get();
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->transaksi_code,
            $transaksi->customer_name,
            ucfirst($transaksi->service_type),
            $transaksi->weight . ' kg',
            $transaksi->total_price,
            ucfirst($transaksi->status),
            str_replace('_', ' ', ucfirst($transaksi->payment_status)),
            $transaksi->created_at->format('d/m/Y H:i')
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Nama Pelanggan',
            'Tipe Layanan',
            'Berat',
            'Total Harga',
            'Status Pengerjaan',
            'Status Pembayaran',
            'Tanggal'
        ];
    }
}