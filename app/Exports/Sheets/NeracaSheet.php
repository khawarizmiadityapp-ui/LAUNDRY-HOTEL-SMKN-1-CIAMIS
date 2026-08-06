<?php

namespace App\Exports\Sheets;

use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class NeracaSheet implements FromView, WithTitle, ShouldAutoSize
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
        $queryPemasukan = Transaksi::where('payment_status', 'lunas');
        $queryPiutang = Transaksi::where('payment_status', 'belum_bayar');
        $queryPengeluaran = Pengeluaran::with('kategoriPengeluaran');

        if ($this->filter == 'bulanan') {
            $queryPemasukan->whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year);

            $queryPiutang->whereMonth('created_at', now()->month)
                         ->whereYear('created_at', now()->year);

            $queryPengeluaran->whereMonth('tanggal', now()->month)
                             ->whereYear('tanggal', now()->year);
        } elseif ($this->filter == 'tahunan') {
            $queryPemasukan->whereYear('created_at', now()->year);
            $queryPiutang->whereYear('created_at', now()->year);
            $queryPengeluaran->whereYear('tanggal', now()->year);
        } elseif ($this->filter == 'custom') {
            if ($this->dari && $this->sampai) {
                $start = Carbon::parse($this->dari)->startOfDay();
                $end = Carbon::parse($this->sampai)->endOfDay();

                $queryPemasukan->whereBetween('created_at', [$start, $end]);
                $queryPiutang->whereBetween('created_at', [$start, $end]);
                $queryPengeluaran->whereBetween('tanggal', [$start, $end]);
            }
        }

        $totalPemasukan = (clone $queryPemasukan)->sum('total_price');
        $jumlahPemasukan = (clone $queryPemasukan)->count();
        $rataRataPemasukan = $jumlahPemasukan > 0 ? $totalPemasukan / $jumlahPemasukan : 0;

        $totalPiutang = (clone $queryPiutang)->sum('total_price');
        $jumlahPiutang = (clone $queryPiutang)->count();
        $totalUtang = 0;
        $jumlahUtang = 0;

        $totalPengeluaran = (clone $queryPengeluaran)->sum('nominal');
        $jumlahPengeluaran = (clone $queryPengeluaran)->count();

        $labaBersih = $totalPemasukan - $totalPengeluaran;
        $marginLaba = $totalPemasukan > 0 ? ($labaBersih / $totalPemasukan) * 100 : 0;

        // Breakdown pengeluaran per kategori
        $allPengeluaran = (clone $queryPengeluaran)->get();
        $distribusiPengeluaran = $allPengeluaran->groupBy(function($item) {
            return $item->kategori_nama ?? 'Lain-lain';
        })->map(function($items, $key) use ($totalPengeluaran) {
            $total = $items->sum('nominal');
            return [
                'kategori' => $key,
                'total' => $total,
                'persen' => $totalPengeluaran > 0 ? round(($total / $totalPengeluaran) * 100, 2) : 0
            ];
        })->sortByDesc('total');

        return view('admin.exports.neraca_excel', [
            'filter' => $this->filter,
            'dari' => $this->dari,
            'sampai' => $this->sampai,
            'totalPemasukan' => $totalPemasukan,
            'jumlahPemasukan' => $jumlahPemasukan,
            'rataRataPemasukan' => $rataRataPemasukan,
            'totalPiutang' => $totalPiutang,
            'jumlahPiutang' => $jumlahPiutang,
            'totalUtang' => $totalUtang,
            'jumlahUtang' => $jumlahUtang,
            'totalPengeluaran' => $totalPengeluaran,
            'jumlahPengeluaran' => $jumlahPengeluaran,
            'labaBersih' => $labaBersih,
            'marginLaba' => $marginLaba,
            'distribusiPengeluaran' => $distribusiPengeluaran,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Neraca & Keuangan';
    }
}
