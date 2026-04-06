<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Generate dummy data
        $allTransactions = collect($this->generateDummyTransactions());

        // Statistik (berdasarkan seluruh data)
        $today = Carbon::today();
        $totalPendapatanHariIni = $allTransactions->filter(function ($item) use ($today) {
            return $item['status'] === 'Lunas' && $item['tanggal_bayar'] && Carbon::parse($item['tanggal_bayar'])->isToday();
        })->sum('jumlah');

        $transaksiBelumLunas = $allTransactions->where('status', 'Belum Lunas')->count();

        // Metode populer
        $metodeCount = $allTransactions->groupBy('metode')->map->count();
        $totalTransaksi = $allTransactions->count();
        $metodePopuler = $metodeCount->sortDesc()->first();
        $metodePopulerNama = $metodeCount->sortDesc()->keys()->first();
        $persentaseMetodePopuler = $totalTransaksi > 0 ? round(($metodePopuler / $totalTransaksi) * 100) : 0;

        // Filter untuk tabel
        $filtered = $allTransactions;
        if ($request->filled('status')) {
            $filtered = $filtered->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $filtered = $filtered->filter(function ($item) use ($search) {
                return stripos($item['pelanggan']['nama'], $search) !== false;
            });
        }

        // Pagination manual (10 per halaman)
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $currentPageItems = $filtered->slice($offset, $perPage)->values();
        $transactions = new LengthAwarePaginator(
            $currentPageItems,
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.pembayaran.index', compact(
            'transactions',
            'totalPendapatanHariIni',
            'transaksiBelumLunas',
            'metodePopulerNama',
            'persentaseMetodePopuler'
        ));
    }

    public function create()
    {
        // Halaman form entri bayar baru (dummy)
        return view('admin.pembayaran.create');
    }

    private function generateDummyTransactions()
    {
        $transactions = [];
        $namaPelanggan = [
            'Aris Danu', 'Siska Putri', 'Reno Kuncoro', 'Nadia Maria', 'Budi Santoso',
            'Dewi Lestari', 'Agus Salim', 'Rina Andriani', 'Eko Prasetyo', 'Maya Sari'
        ];
        $layananList = [
            ['nama' => 'Reguler', 'satuan' => 'kg'],
            ['nama' => 'Express', 'satuan' => 'kg'],
            ['nama' => 'Dry Clean', 'satuan' => 'Item']
        ];
        $metodeList = ['QRIS', 'Tunai', 'Transfer BCA'];

        for ($i = 1; $i <= 124; $i++) {
            $status = rand(1, 100) <= 80 ? 'Lunas' : 'Belum Lunas'; // 80% lunas
            $tanggalBayar = null;
            if ($status === 'Lunas') {
                // 10% transaksi lunas hari ini, sisanya random 1-30 hari lalu
                if ($i <= 12) {
                    $tanggalBayar = Carbon::today()->subDays(rand(0, 1))->format('d M Y, H:i');
                } else {
                    $tanggalBayar = Carbon::today()->subDays(rand(1, 30))->format('d M Y, H:i');
                }
            }
            $pelanggan = $namaPelanggan[array_rand($namaPelanggan)];
            $layanan = $layananList[array_rand($layananList)];
            $berat = $layanan['nama'] === 'Dry Clean' ? rand(1, 3) . ' ' . $layanan['satuan'] : rand(2, 10) . ' ' . $layanan['satuan'];
            $jumlah = $layanan['nama'] === 'Express' ? rand(30000, 80000) : ($layanan['nama'] === 'Dry Clean' ? rand(100000, 200000) : rand(25000, 60000));
            $metode = $metodeList[array_rand($metodeList)];

            $transactions[] = [
                'id_transaksi' => '#BNG-' . (8800 + $i),
                'pelanggan' => [
                    'nama' => $pelanggan,
                    'layanan' => $layanan['nama'],
                    'berat' => $berat,
                ],
                'tanggal_bayar' => $tanggalBayar,
                'metode' => $metode,
                'jumlah' => $jumlah,
                'status' => $status,
            ];
        }
        return $transactions;
    }
}
