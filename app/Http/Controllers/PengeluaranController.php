<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    // ─── INDEX ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // Base query with filters
        $query = Pengeluaran::query()
            ->when($request->kategori, fn($q) => $q->kategori($request->kategori))
            ->when($request->status,   fn($q) => $q->status($request->status))
            ->when(
                $request->dari || $request->sampai,
                fn($q) => $q->dateRange($request->dari, $request->sampai)
            )
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        $pengeluarans = $query->paginate(10)->withQueryString();

        // ── Stat cards ─────────────────────────────────────────────────
        $bulanIni      = Carbon::now()->startOfMonth();
        $totalBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
                                    ->whereYear('tanggal', now()->year)
                                    ->sum('nominal');

        $targetAnggaran = 7_000_000;
        $terpakai       = Pengeluaran::sum('nominal');
        $sisaAnggaran   = max(0, $targetAnggaran - $terpakai);

        // Kategori terbesar berdasarkan total nominal
        $raw = Pengeluaran::selectRaw('kategori, SUM(nominal) as total')
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->first();

        $totalSemua     = Pengeluaran::sum('nominal') ?: 1;
        $kategoriTerbesar = [
            'nama'   => $raw?->kategori ?? '-',
            'persen' => $raw ? round(($raw->total / $totalSemua) * 100) : 0,
        ];

        // Daftar kategori unik untuk filter
        $kategoriList = Pengeluaran::distinct()->orderBy('kategori')->pluck('kategori');

        return view('admin.pengeluaran.index', compact(
            'pengeluarans',
            'totalBulanIni',
            'sisaAnggaran',
            'targetAnggaran',
            'kategoriTerbesar',
            'kategoriList'
        ));
    }

    // ─── CREATE ─────────────────────────────────────────────────────────
    public function create()
    {
        $idTransaksi  = Pengeluaran::generateIdTransaksi();
        $kategoriList = Pengeluaran::distinct()->orderBy('kategori')->pluck('kategori');

        return view('admin.pengeluaran.create', compact('idTransaksi', 'kategoriList'));
    }

    // ─── STORE ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'status'     => 'required|in:lunas,pending,urgent',
        ]);

        $validated['id_transaksi'] = Pengeluaran::generateIdTransaksi();

        Pengeluaran::create($validated);

        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // ─── SHOW ───────────────────────────────────────────────────────────
    public function show(Pengeluaran $pengeluaran)
    {
        return view('admin.pengeluaran.show', compact('pengeluaran'));
    }

    // ─── EDIT ───────────────────────────────────────────────────────────
    public function edit(Pengeluaran $pengeluaran)
    {
        $kategoriList = Pengeluaran::distinct()->orderBy('kategori')->pluck('kategori');
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    // ─── UPDATE ─────────────────────────────────────────────────────────
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'status'     => 'required|in:lunas,pending,urgent',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    // ─── DESTROY ────────────────────────────────────────────────────────
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    // ─── EXPORT (CSV) ───────────────────────────────────────────────────
    public function export()
    {
        $data = Pengeluaran::orderByDesc('tanggal')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pengeluaran_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID Transaksi', 'Nama', 'Kategori', 'Keterangan', 'Tanggal', 'Nominal', 'Status']);
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->id_transaksi,
                    $row->nama,
                    $row->kategori,
                    $row->keterangan,
                    $row->tanggal->format('d/m/Y'),
                    $row->nominal,
                    $row->status,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}