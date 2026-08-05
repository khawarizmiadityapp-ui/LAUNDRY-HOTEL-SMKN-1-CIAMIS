<?php

namespace App\Exports\Sheets;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class PemasukanSheet implements FromView, WithTitle, ShouldAutoSize
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

    public function view(): View
    {
        $query = Transaksi::with(['user', 'customer', 'details.layanan'])
            ->where('payment_status', 'lunas');

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

        $pemasukanData = $query->latest()->get();

        return view('admin.exports.pemasukan_excel', [
            'filter' => $this->filter,
            'dari' => $this->dari,
            'sampai' => $this->sampai,
            'pemasukanData' => $pemasukanData,
        ]);
    }

    public function title(): string
    {
        return 'Rincian Pemasukan';
    }
}
