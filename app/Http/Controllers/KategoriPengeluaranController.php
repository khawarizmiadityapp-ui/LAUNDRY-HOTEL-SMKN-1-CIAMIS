<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    // ─── INDEX ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        
        $kategoris = KategoriPengeluaran::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->withCount('pengeluarans')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => KategoriPengeluaran::count(),
            'aktif' => KategoriPengeluaran::where('is_active', true)->count(),
            'tidak_aktif' => KategoriPengeluaran::where('is_active', false)->count(),
        ];

        return view('admin.kategori_pengeluaran.index', compact('kategoris', 'stats'));
    }

    // ─── CREATE ─────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.kategori_pengeluaran.create');
    }

    // ─── STORE ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_pengeluaran,nama',
            'deskripsi' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        KategoriPengeluaran::create($validated);

        return redirect()->route('admin.kategori-pengeluaran.index')
                         ->with('success', 'Kategori pengeluaran berhasil ditambahkan.');
    }

    // ─── EDIT ───────────────────────────────────────────────────────────
    public function edit(KategoriPengeluaran $kategoriPengeluaran)
    {
        return view('admin.kategori_pengeluaran.edit', compact('kategoriPengeluaran'));
    }

    // ─── UPDATE ─────────────────────────────────────────────────────────
    public function update(Request $request, KategoriPengeluaran $kategoriPengeluaran)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_pengeluaran,nama,' . $kategoriPengeluaran->id,
            'deskripsi' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $kategoriPengeluaran->update($validated);

        return redirect()->route('admin.kategori-pengeluaran.index')
                         ->with('success', 'Kategori pengeluaran berhasil diperbarui.');
    }

    // ─── DESTROY ────────────────────────────────────────────────────────
    public function destroy(KategoriPengeluaran $kategoriPengeluaran)
    {
        // Cek apakah kategori masih digunakan
        if ($kategoriPengeluaran->isUsed()) {
            return redirect()->route('admin.kategori-pengeluaran.index')
                             ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $kategoriPengeluaran->pengeluarans()->count() . ' pengeluaran.');
        }

        $kategoriPengeluaran->delete();

        return redirect()->route('admin.kategori-pengeluaran.index')
                         ->with('success', 'Kategori pengeluaran berhasil dihapus.');
    }

    // ─── TOGGLE STATUS ──────────────────────────────────────────────────
    public function toggleStatus(KategoriPengeluaran $kategoriPengeluaran)
    {
        $kategoriPengeluaran->update([
            'is_active' => !$kategoriPengeluaran->is_active
        ]);

        $status = $kategoriPengeluaran->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.kategori-pengeluaran.index')
                         ->with('success', "Kategori berhasil {$status}.");
    }
}
