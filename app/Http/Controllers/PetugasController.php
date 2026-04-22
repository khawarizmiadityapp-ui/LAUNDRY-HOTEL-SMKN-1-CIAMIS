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
        // Ambil transaksi yang memiliki task 'washing' dengan status 'pending'
        $transactions = Transaksi::whereHas('tasks', function($q) {
            $q->where('stage', 'washing')->where('status', 'pending');
        })->with(['tasks', 'details.layanan'])->get();

        return view('petugas_piket.washing.index', compact('transactions'));
    }

    // Halaman Setrika
    public function setrika()
    {
        $transactions = Transaksi::whereHas('tasks', function($q) {
            $q->where('stage', 'ironing')->where('status', 'pending');
        })
        ->where(function($query) {
            $query->whereDoesntHave('tasks', function($q) {
                $q->where('stage', 'washing');
            })
            ->orWhereHas('tasks', function($q) {
                $q->where('stage', 'washing')->where('status', 'completed');
            });
        })
        ->with(['tasks', 'details.layanan'])->get();

        return view('petugas_piket.setrika.index', compact('transactions'));
    }

    // Halaman Packing
    public function packing()
    {
        $transactions = Transaksi::whereHas('tasks', function($q) {
            $q->where('stage', 'packing')->where('status', 'pending');
        })
        ->where(function($query) {
            $query->whereDoesntHave('tasks', function($q) {
                $q->where('stage', 'ironing');
            })
            ->orWhereHas('tasks', function($q) {
                $q->where('stage', 'ironing')->where('status', 'completed');
            });
        })
        ->with(['tasks', 'details.layanan'])->get();

        return view('petugas_piket.packing.index', compact('transactions'));
    }


    /**
     * Selesaikan task tertentu (washing, ironing, packing)
     */
    public function completeTask(Request $request, $transaksiId)
    {
        $request->validate([
            'stage' => 'required|in:washing,ironing,packing'
        ]);

        $transaksi = Transaksi::findOrFail($transaksiId);
        $stage = $request->stage;
        
        // Cari task yang sesuai
        $task = $transaksi->tasks()->where('stage', $stage)->first();
        
        if ($task) {
            $task->update([
                'status' => 'completed',
                'petugas_id' => auth()->id(),
                'completed_at' => now(),
            ]);

            // Auto-deduct Inventory if stage is washing
            if ($stage === 'washing') {
                // Deduct 1 unit of detergent
                \App\Models\Inventory::where('category', 'detergent')
                    ->where('quantity', '>', 0)
                    ->first()
                    ?->decrement('quantity', 1);

                // Deduct 1 unit of fragrance
                \App\Models\Inventory::where('category', 'fragrance')
                    ->where('quantity', '>', 0)
                    ->first()
                    ?->decrement('quantity', 1);
            }

            // Update overall transaction status
            $statusMap = [
                'washing' => 'dicuci',
                'ironing' => 'disetrika',
                'packing' => 'selesai',
            ];

            if (isset($statusMap[$stage])) {
                $transaksi->update(['status' => $statusMap[$stage]]);
            }

            // Generate WhatsApp Notification Link
            $phone = preg_replace('/^0/', '62', $transaksi->customer_phone);
            $msg = "Halo " . $transaksi->customer_name . ", pesanan Anda #" . $transaksi->transaksi_code . " saat ini telah selesai pada tahap " . ucfirst($stage) . ". \n\nCek progress lengkapnya di: " . route('track.status', ['nota_number' => $transaksi->transaksi_code]);
            $waLink = "https://wa.me/" . $phone . "?text=" . urlencode($msg);

            session()->flash('notification_link', $waLink);

            return redirect()->back()->with('success', 'Tugas ' . ucfirst($stage) . ' berhasil diselesaikan!');
        }

        return redirect()->back()->with('error', "Tugas tidak ditemukan.");
    }

    // Update status transaksi via Operations Hub (Lama - Masih dipertahankan untuk kompatibilitas jika perlu)
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
        $inventory = \App\Models\Inventory::all()->groupBy('category');
        return view('petugas_piket.inventory.index', compact('inventory'));
    }

    // Update stok inventory dari portal petugas
    public function adjustInventory(Request $request, $id)
    {
        $item = \App\Models\Inventory::findOrFail($id);
        $adjustment = (int) $request->adjustment;
        
        $item->quantity = max(0, $item->quantity + $adjustment);
        $item->save();

        return redirect()->back()->with('success', "Stok {$item->name} berhasil diperbarui.");
    }

    // Halaman History
    public function history()
    {
        return view('petugas_piket.history.index');
    }

}