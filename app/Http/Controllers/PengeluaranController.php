<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Models\Setting;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    // ─── INDEX ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // Base query with filters
        $query = Pengeluaran::with('kategoriPengeluaran')
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

        // Target anggaran diambil dari pengaturan yang disetel pengguna
        $targetAnggaran = (int) Setting::getValue('anggaran_bulanan', 0);
        $terpakaiBulanIni = $totalBulanIni;
        $sisaAnggaran   = max(0, $targetAnggaran - $terpakaiBulanIni);

        // Kategori terbesar berdasarkan total nominal
        $raw = Pengeluaran::selectRaw('kategori_id, SUM(nominal) as total')
                ->groupBy('kategori_id')
                ->orderByDesc('total')
                ->first();

        $totalSemua     = Pengeluaran::sum('nominal') ?: 1;
        $kategoriTerbesar = [
            'nama'   => $raw ? KategoriPengeluaran::find($raw->kategori_id)?->nama ?? '-' : '-',
            'persen' => $raw ? round(($raw->total / $totalSemua) * 100) : 0,
        ];

        // Daftar kategori untuk filter
        $kategoriList = KategoriPengeluaran::active()->orderBy('nama')->get();

        return view('admin.pengeluaran.index', compact(
            'pengeluarans',
            'totalBulanIni',
            'sisaAnggaran',
            'targetAnggaran',
            'kategoriTerbesar',
            'kategoriList'
        ));
    }

    // ─── UPDATE ANGGARAN ────────────────────────────────────────────────
    public function updateAnggaran(Request $request)
    {
        $request->validate([
            'anggaran_bulanan' => 'required|numeric|min:0',
        ]);

        Setting::setValue('anggaran_bulanan', $request->anggaran_bulanan);

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Anggaran operasional bulanan berhasil diperbarui.');
    }

    // ─── CREATE ─────────────────────────────────────────────────────────
    public function create()
    {
        $idTransaksi  = Pengeluaran::generateIdTransaksi();
        $kategoriList = KategoriPengeluaran::active()->orderBy('nama')->get();

        return view('admin.pengeluaran.create', compact('idTransaksi', 'kategoriList'));
    }

    // ─── STORE ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'keterangan' => 'nullable|string|max:255',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'bon_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['id_transaksi'] = Pengeluaran::generateIdTransaksi();

        // Set legacy kategori field for backward compatibility
        $kategori = KategoriPengeluaran::find($validated['kategori_id']);
        $validated['kategori'] = $kategori->nama;

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
        $kategoriList = KategoriPengeluaran::active()->orderBy('nama')->get();
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    // ─── UPDATE ─────────────────────────────────────────────────────────
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'keterangan' => 'nullable|string|max:255',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'bon_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Set legacy kategori field for backward compatibility
        $kategori = KategoriPengeluaran::find($validated['kategori_id']);
        $validated['kategori'] = $kategori->nama;

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
