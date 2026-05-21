<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreLayananRequest;
use App\Http\Requests\UpdateLayananRequest;

class LayananController extends Controller
{
    /**
     * Tampilkan semua layanan dengan filter kategori.
     */
    public function index(Request $request)
    {
        $query = Layanan::query();

        // Filter kategori dari tab
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        // Fitur Search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $layanans = $query->orderBy('kategori')->orderBy('nama')->get();

        // Statistik
        $totalLayanan   = Layanan::count();
        $layananTerlaris = Layanan::where('badge', 'Populer')->first()?->nama ?? 'Kiloan Regular';
        $semuaAktif     = Layanan::where('status', false)->doesntExist();

        return view('admin.layanan.index', compact(
            'layanans',
            'totalLayanan',
            'layananTerlaris',
            'semuaAktif'
        ));
    }

    /**
     * Simpan layanan baru.
     */
    public function store(StoreLayananRequest $request)
    {
        // Validation already handled by FormRequest
        $validated = $request->validated();
        $validated['status'] = true;

        $layanan = Layanan::create($validated);
        
        // Clear layanan cache when new service is created
        Cache::forget('layanan_list');
        Cache::forget('layanan_aktif');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil ditambahkan.',
                'layanan' => [
                    'id'       => $layanan->id,
                    'nama'     => $layanan->nama,
                    'kategori' => $layanan->kategori,
                    'harga'    => (float) $layanan->harga,
                    'satuan'   => $layanan->satuan,
                ]
            ], 201);
        }

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Update harga atau status layanan.
     */
    public function update(UpdateLayananRequest $request, Layanan $layanan)
    {
        // Validation already handled by FormRequest
        $validated = $request->validated();

        $layanan->update($validated);
        
        // Clear layanan cache when service is updated
        Cache::forget('layanan_list');
        Cache::forget('layanan_aktif');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil diperbarui.',
                'layanan' => [
                    'id'       => $layanan->id,
                    'nama'     => $layanan->nama,
                    'kategori' => $layanan->kategori,
                    'harga'    => (float) $layanan->harga,
                    'satuan'   => $layanan->satuan,
                ]
            ]);
        }

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif via AJAX.
     */
    public function toggleStatus(Layanan $layanan)
    {
        $layanan->update(['status' => !$layanan->status]);

        return response()->json([
            'success' => true,
            'status'  => $layanan->status,
            'message' => $layanan->status ? 'Layanan diaktifkan.' : 'Layanan dinonaktifkan.',
        ]);
    }

    /**
     * Hapus layanan.
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
