<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    // ─── INDEX ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // Base query with filters
        $query = Pengeluaran::query()
            ->when($request->kategori, fn($q) => $q->kategori($request->kategori))
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

        // Target anggaran diambil dari seluruh total penjualan jasa (transaksi lunas)
        $targetAnggaran = (int) Transaksi::where('payment_status', 'lunas')
                                    ->sum('total_price');
        $terpakaiBulanIni = $totalBulanIni;
        $sisaAnggaran   = max(0, $targetAnggaran - $terpakaiBulanIni);

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
        $kategoriList = collect(Pengeluaran::KATEGORI_DIIZINKAN);

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
        $kategoriList = collect(Pengeluaran::KATEGORI_DIIZINKAN);

        return view('admin.pengeluaran.create', compact('idTransaksi', 'kategoriList'));
    }

    // ─── STORE ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'required|in:' . implode(',', Pengeluaran::KATEGORI_DIIZINKAN),
            'keterangan' => 'nullable|string|max:255',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'bon_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['id_transaksi'] = Pengeluaran::generateIdTransaksi();

        if ($request->hasFile('bon_file')) {
            $validated['bon_file'] = $request->file('bon_file')->store('bon-pengeluaran', 'public');
        } else {
            unset($validated['bon_file']);
        }

        Pengeluaran::create($validated);

        return redirect()->route('admin.pengeluaran.index')
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
        $kategoriList = collect(Pengeluaran::KATEGORI_DIIZINKAN);
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    // ─── UPDATE ─────────────────────────────────────────────────────────
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'required|in:' . implode(',', Pengeluaran::KATEGORI_DIIZINKAN),
            'keterangan' => 'nullable|string|max:255',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'bon_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('bon_file')) {
            if ($pengeluaran->bon_file) {
                Storage::disk('public')->delete($pengeluaran->bon_file);
            }
            $validated['bon_file'] = $request->file('bon_file')->store('bon-pengeluaran', 'public');
        } else {
            unset($validated['bon_file']);
        }

        $pengeluaran->update($validated);

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    // ─── DESTROY ────────────────────────────────────────────────────────
    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->bon_file) {
            Storage::disk('public')->delete($pengeluaran->bon_file);
        }

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
            fputcsv($handle, ['ID Transaksi', 'Nama', 'Kategori', 'Keterangan', 'Tanggal', 'Nominal']);
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->id_transaksi,
                    $row->nama,
                    $row->kategori,
                    $row->keterangan,
                    $row->tanggal->format('d/m/Y'),
                    $row->nominal,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
