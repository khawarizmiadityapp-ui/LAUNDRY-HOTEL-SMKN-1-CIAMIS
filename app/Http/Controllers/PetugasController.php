<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Transaksi;
use App\Models\InventoryAdjustmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    private function ensureStaffDivisionAccess(array $allowedDivisions): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Akses ditolak');
        }

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role !== 'staff') {
            abort(403, 'Akses ditolak');
        }

        $division = strtolower((string) $user->division);
        if (!in_array($division, $allowedDivisions, true)) {
            abort(403, 'Akun Anda tidak memiliki akses ke modul ini.');
        }
    }

    // Dashboard untuk Petugas Piket
    public function dashboard()
    {
        if (Auth::user()->role !== 'staff') {
            abort(403, 'Akses ditolak');
        }

        $user = Auth::user();
        $division = strtolower((string) $user->division);

        // Fetch dynamic stats based on division
        $pendingTasks = 0;
        $completedToday = 0;
        
        if (in_array($division, ['washing', 'ironing', 'setrika', 'packing'])) {
            $taskStage = $division === 'setrika' ? 'ironing' : $division;

            $pendingTasks = \App\Models\LaundryTask::where('stage', $taskStage)
                ->where('status', 'pending')
                ->count();

            $completedToday = \App\Models\LaundryTask::where('stage', $taskStage)
                ->where('status', 'completed')
                ->whereDate('completed_at', \Carbon\Carbon::today())
                ->count();
        }

        return view('petugas_piket.dashboard', compact('pendingTasks', 'completedToday', 'division'));
    }
    // Menampilkan halaman Blade
    public function index()
    {
        $petugas = Petugas::orderBy('nama')->get();

        $petugasData = $petugas->map(function (Petugas $item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'idPetugas' => $item->id_petugas,
                'role' => $item->role,
                'status' => $item->status,
                'shift' => $item->shift,
            ];
        })->values();

        return view('admin.petugas.index', [
            'petugasData' => $petugasData,
        ]);
    }

    // API: ambil semua data petugas
    public function apiIndex()
    {
        $petugas = Petugas::orderBy('nama')->get();
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

        $nextId = ((int) Petugas::max('id')) + 1;
        $idPetugas = 'STF-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);

        $petugas = Petugas::create([
            'nama' => $request->nama,
            'id_petugas' => $idPetugas,
            'role' => $request->role,
            'status' => $request->status,
            'shift' => $request->shift,
        ]);

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
        $this->ensureStaffDivisionAccess(['washing']);

        // Ambil transaksi yang memiliki task 'washing' dengan status 'pending'
        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'washing')->where('status', 'pending');
        })->with(['details.layanan'])->get();

        return view('petugas_piket.washing.index', compact('transactions'));
    }

    // Halaman Setrika
    public function setrika()
    {
        $this->ensureStaffDivisionAccess(['ironing', 'setrika']);

        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'ironing')->where('status', 'pending');
        })->whereHas('tasks', function ($q) {
            $q->where(function ($q) {
                $q->where('stage', 'washing');
            })->orWhere(function ($q) {
                $q->where('stage', 'washing')->where('status', 'completed');
            });
        })
        ->with(['details.layanan'])->get();

        return view('petugas_piket.setrika.index', compact('transactions'));
    }

    // Halaman Packing
    public function packing()
    {
        $this->ensureStaffDivisionAccess(['packing']);

        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'packing')->where('status', 'pending');
        })->whereHas('tasks', function ($q) {
            $q->where(function ($q) {
                $q->where('stage', 'ironing');
            })->orWhere(function ($q) {
                $q->where('stage', 'ironing')->where('status', 'completed');
            });
        })
        ->with(['details.layanan'])->get();

        return view('petugas_piket.packing.index', compact('transactions'));
    }


    /**
     * Selesaikan task tertentu (washing, ironing, packing)
     */
    public function completeTask(Request $request, $transaksiId)
    {
        $request->validate([
            'stage' => 'required|in:washing,ironing,packing',
            'petugas_name' => 'nullable|string|max:255'
        ]);

        $transaksi = Transaksi::findOrFail($transaksiId);
        $stage = $request->stage;

        // Cari task yang sesuai
        $task = $transaksi->tasks()->where('stage', $stage)->first();

        if ($task) {
            $task->update([
                'status' => 'completed',
                'petugas_id' => Auth::id(),
                'petugas_name' => $request->petugas_name,
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
        $this->ensureStaffDivisionAccess(['inventory']);

        $inventory = \App\Models\Inventory::all()->groupBy('category');
        return view('petugas_piket.inventory.index', compact('inventory'));
    }

    // Update stok inventory dari portal petugas
    public function adjustInventory(Request $request, $id)
    {
        $this->ensureStaffDivisionAccess(['inventory']);

        $request->validate([
            'adjustment' => 'required|integer|not_in:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $item = \App\Models\Inventory::findOrFail($id);
        $adjustment = (int) $request->adjustment;

        if (Auth::user()->role === 'admin') {
            $item->quantity = max(0, $item->quantity + $adjustment);
            $item->save();

            return redirect()->back()->with('success', "Stok {$item->name} berhasil diperbarui.");
        }

        InventoryAdjustmentRequest::create([
            'inventory_id' => $item->id,
            'requested_by' => Auth::id(),
            'adjustment' => $adjustment,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', "Permintaan perubahan stok {$item->name} dikirim ke admin/guru piket untuk konfirmasi.");
    }

    // Halaman History
    public function history()
    {
        $user = Auth::user();
        if ($user->role !== 'staff') {
            abort(403, 'Akses ditolak');
        }

        $division = strtolower((string) $user->division);
        // Only fetch history if the user belongs to a specific processing division
        if (in_array($division, ['washing', 'ironing', 'setrika', 'packing'])) {
            $taskStage = $division === 'setrika' ? 'ironing' : $division;

            $completedTasks = \App\Models\LaundryTask::where('stage', $taskStage)
                ->where('status', 'completed')
                ->with(['transaksi'])
                ->orderBy('completed_at', 'desc')
                ->paginate(15);
        } else {
            // Default empty if they don't have a valid division
            $completedTasks = collect();
        }

        return view('petugas_piket.history.index', compact('completedTasks', 'division'));
    }

}
