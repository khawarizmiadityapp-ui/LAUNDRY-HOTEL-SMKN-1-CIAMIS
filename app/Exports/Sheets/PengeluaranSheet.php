<?php

namespace App\Exports\Sheets;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class PengeluaranSheet implements FromView, WithTitle, ShouldAutoSize
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
        $query = Pengeluaran::with('kategoriPengeluaran');

        if ($this->filter == 'bulanan') {
            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
        } elseif ($this->filter == 'tahunan') {
            $query->whereYear('tanggal', now()->year);
        } elseif ($this->filter == 'custom') {
            if ($this->dari && $this->sampai) {
                $start = Carbon::parse($this->dari)->startOfDay();
                $end = Carbon::parse($this->sampai)->endOfDay();
                $query->whereBetween('tanggal', [$start, $end]);
            }
        }

        $pengeluaranData = $query->latest('tanggal')->get();

        return view('admin.exports.pengeluaran_excel', [
            'filter' => $this->filter,
            'dari' => $this->dari,
            'sampai' => $this->sampai,
            'pengeluaranData' => $pengeluaranData,
        ]);
    }

    public function title(): string
    {
        return 'Rincian Pengeluaran';
    }
}
