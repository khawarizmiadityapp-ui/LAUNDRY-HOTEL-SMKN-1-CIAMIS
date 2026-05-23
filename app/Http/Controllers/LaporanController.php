<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // BUG FIX 3: Add input validation for date filters
        $request->validate([
            'filter' => 'nullable|in:bulanan,tahunan,custom',
            'dari' => 'required_if:filter,custom|date',
            'sampai' => 'required_if:filter,custom|date|after_or_equal:dari',
        ]);

        $filter = $request->filter ?? 'bulanan';

        $query = Transaksi::query();
        $pengeluaranQuery = Pengeluaran::query();

        if ($filter == 'bulanan') {
            // BUG FIX 1: Gunakan bulan sebelumnya untuk laporan bulanan
            $prevMonth = now()->subMonth();
            $query->whereMonth('created_at', $prevMonth->month)
                ->whereYear('created_at', $prevMonth->year);

            // BUG FIX 1: Pengeluaran juga harus terfilter per bulan
            $pengeluaranQuery->whereMonth('tanggal', $prevMonth->month)
                ->whereYear('tanggal', $prevMonth->year);
        } elseif ($filter == 'tahunan') {
            $query->whereYear('created_at', now()->year);
            // BUG FIX 1: Pengeluaran juga harus terfilter per tahun
            $pengeluaranQuery->whereYear('tanggal', now()->year);
        } elseif ($filter == 'custom') {
            if ($request->dari && $request->sampai) {
                // BUG FIX 2: Gunakan startOfDay() dan endOfDay() agar presisi
                $start = Carbon::parse($request->dari)->startOfDay();
                $end = Carbon::parse($request->sampai)->endOfDay();
                $query->whereBetween('created_at', [
                    $start,
                    $end
                ]);
                // BUG FIX 2: Pengeluaran juga gunakan rentang yang sama
                $pengeluaranQuery->whereBetween('tanggal', [
                    $start,
                    $end
                ]);
            }
        }

        $pemasukan = (clone $query)->sum('total_price');
        $pengeluaran = (clone $pengeluaranQuery)->sum('nominal');
        $laba = $pemasukan - $pengeluaran;

        $targetAnggaran = 50000000;
        $limitPemasukanBulanan = (int) env('MONTHLY_INCOME_LIMIT', 50000000);
        $realisasiBulanIni = Transaksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $persenTargetBulanIni = $limitPemasukanBulanan > 0
            ? min(100, round(($realisasiBulanIni / $limitPemasukanBulanan) * 100, 2))
            : 0;

        // DATA CHART
        $months = [];
        $dataMasuk = [];
        $dataKeluar = [];
        $laporanBulanan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $months[] = $date->format('M');

            $totalMasuk = Transaksi::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');

            $totalKeluar = Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('nominal');

            $dataMasuk[] = $totalMasuk;
            $dataKeluar[] = $totalKeluar;

            $laporanBulanan[] = [
                'bulan' => $date->translatedFormat('F'),
                'tahun' => $date->year,
                'pemasukan' => $totalMasuk,
                'pengeluaran' => $totalKeluar,
                'laba' => $totalMasuk - $totalKeluar,
            ];
        }

        $kategoriTerbesar = [
            'nama' => '-',
            'persen' => 0
        ];

        $kategoriList = Pengeluaran::distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $persenLaba = $pemasukan > 0
            ? ($laba / $pemasukan) * 100
            : 0;

        return view('admin.laporan_keuangan.index', [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'laba' => $laba,
            'months' => $months,
            'dataMasuk' => $dataMasuk,
            'dataKeluar' => $dataKeluar,
            'filter' => $filter,
            'targetAnggaran' => $targetAnggaran,
            'kategoriTerbesar' => $kategoriTerbesar,
            'kategoriList' => $kategoriList,
            'persenLaba' => $persenLaba,
            'laporanBulanan' => $laporanBulanan,
            'limitPemasukanBulanan' => $limitPemasukanBulanan,
            'realisasiBulanIni' => $realisasiBulanIni,
            'persenTargetBulanIni' => $persenTargetBulanIni,
        ]);
    }
}
