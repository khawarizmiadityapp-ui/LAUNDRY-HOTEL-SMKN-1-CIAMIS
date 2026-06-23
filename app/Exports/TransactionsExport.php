<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filter;
    protected $dari;
    protected $sampai;

    public function __construct($filter = null, $dari = null, $sampai = null)
    {
        $this->filter = $filter ?? 'bulanan';
        $this->dari = $dari;
        $this->sampai = $sampai;
    }

    public function collection()
    {
        $query = Transaksi::with(['user', 'customer', 'details.layanan']);
        
        if ($this->filter == 'bulanan') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($this->filter == 'tahunan') {
            $query->whereYear('created_at', now()->year);
        } elseif ($this->filter == 'custom') {
            if ($this->dari && $this->sampai) {
                $start = Carbon::parse($this->dari)->startOfDay();
                $end = Carbon::parse($this->sampai)->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }
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