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
        // FILTER
        $filter = $request->filter ?? 'bulanan';

        $query = Transaksi::query();

        if ($filter == 'bulanan') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($filter == 'tahunan') {
            $query->whereYear('created_at', now()->year);
        }

        // DATA UTAMA (SEMUA = PEMASUKAN)
        $pemasukan = (clone $query)->sum('total_price');
        $pengeluaran = 0;
        $laba = $pemasukan;

        //target anggaran (misal 50 juta per bulan)
        $targetAnggaran = 50000000;

        // DATA CHART
        $months = [];
        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $months[] = $date->format('M');

            $dataMasuk[] = Transaksi::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');

            $dataKeluar[] = 0;
        }

        // KATEGORI PENGELUARAN TERBESAR
        $kategoriTerbesar = [
            'nama' => '-',
            'persen' => 0
        ];

        //mengambil daftar kategori pengeluaran
        $kategoriList = Pengeluaran::distinct()
        ->orderBy('kategori')
        ->pluck('kategori');

        //menghitung persentase laba terhadap pemasukan
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

            //target anggaran bisa dikirim juga jika ingin ditampilkan di view
            'targetAnggaran' => $targetAnggaran,

            'kategoriTerbesar' => $kategoriTerbesar,
            'kategoriList' => $kategoriList,
            'persenLaba' => $persenLaba
        ]);
    }
}