<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    // Dashboard untuk Petugas Piket
    public function dashboard()
    {
        if (auth()->user()->role !== 'staff') {
            abort(403, 'Akses ditolak');
        }

        return view('petugas_piket.dashboard');
    }
    // Menampilkan halaman Blade
    public function index()
    {
        return view('admin.petugas.index');
    }

    // API: ambil semua data petugas
    public function apiIndex()
    {
        $petugas = Petugas::all();
        return response()->json($petugas);
    }

    // API: simpan petugas baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'role' => 'required|in:Admin,Operasional,Kurir',
            'status' => 'required|in:Aktif,Off Duty',
            'shift' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $petugas = Petugas::create($request->only(['nama', 'role', 'status', 'shift']));
        return response()->json($petugas, 201);
    }

    // API: update petugas
    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:Admin,Operasional,Kurir',
            'status' => 'sometimes|in:Aktif,Off Duty',
            'shift' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $petugas->update($request->only(['nama', 'role', 'status', 'shift']));
        return response()->json($petugas);
    }

    // API: hapus petugas
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();
        return response()->json(['message' => 'Petugas berhasil dihapus']);
    }

    // Halaman Washing
    public function washing()
    {
        $transactions = Transaksi::whereIn('status', ['diterima', 'disortir', 'dicuci'])->get();
        return view('petugas_piket.washing.index', compact('transactions'));
    }

    // Halaman Setrika
    public function setrika()
    {
        $transactions = Transaksi::whereIn('status', ['dikeringkan', 'disetrika'])->get();
        return view('petugas_piket.setrika.index', compact('transactions'));
    }

    // Halaman Packing
    public function packing()
    {
        $transactions = Transaksi::whereIn('status', ['dipacking'])->get();
        return view('petugas_piket.packing.index', compact('transactions'));
    }

    // Halaman Delivery
    public function delivery()
    {
        $transactions = Transaksi::whereIn('status', ['selesai'])->get();
        return view('petugas_piket.delivery.index', compact('transactions'));
    }

    // Update status transaksi via Operations Hub
    public function updateTaskStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,disortir,dicuci,dikeringkan,disetrika,dipacking,selesai,diambil'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    // Halaman Inventory
    public function inventory()
    {
        return view('petugas_piket.inventory.index');
    }

    // Halaman History
    public function history()
    {
        return view('petugas_piket.history.index');
    }

}