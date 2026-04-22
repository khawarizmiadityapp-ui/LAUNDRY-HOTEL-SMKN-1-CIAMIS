<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'kategori' => ['required', Rule::in(['kiloan', 'satuan'])],
            'harga'    => 'required|numeric|min:0',
            'satuan'   => 'required|string|max:20',
            'estimasi' => 'nullable|string|max:100',
            'badge'    => 'nullable|string|max:50',
            'icon'     => 'nullable|string|max:50',
            'needs_washing' => 'sometimes|boolean',
            'needs_ironing' => 'sometimes|boolean',
            'needs_packing' => 'sometimes|boolean',
        ]);

        $validated['status'] = true;

        $layanan = Layanan::create($validated);

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
    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'nama'     => 'sometimes|string|max:100',
            'kategori' => ['sometimes', Rule::in(['kiloan', 'satuan'])],
            'harga'    => 'sometimes|numeric|min:0',
            'satuan'   => 'sometimes|string|max:20',
            'estimasi' => 'nullable|string|max:100',
            'badge'    => 'nullable|string|max:50',
            'icon'     => 'nullable|string|max:50',
            'status'   => 'sometimes|boolean',
            'needs_washing' => 'sometimes|boolean',
            'needs_ironing' => 'sometimes|boolean',
            'needs_packing' => 'sometimes|boolean',
        ]);

        $layanan->update($validated);

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
