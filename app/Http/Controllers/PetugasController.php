<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Transaksi;
use App\Models\InventoryAdjustmentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();
        
        // ✅ FIX: Allow both admin and staff
        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403, 'Akses ditolak. Hanya admin dan staff yang dapat mengakses halaman ini.');
        }

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
        $petugasList = Petugas::orderBy('nama')->get();

        $petugasData = $petugasList->map(function (Petugas $item) {
            $completedWashing = \App\Models\LaundryTask::where('stage', 'washing')
                ->where('status', 'completed')
                ->where('petugas_name', $item->nama)
                ->count();
                
            $completedIroning = \App\Models\LaundryTask::where('stage', 'ironing')
                ->where('status', 'completed')
                ->where('petugas_name', $item->nama)
                ->count();
                
            $completedPacking = \App\Models\LaundryTask::where('stage', 'packing')
                ->where('status', 'completed')
                ->where('petugas_name', $item->nama)
                ->count();

            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'idPetugas' => $item->id_petugas,
                'role' => $item->role,
                'status' => $item->status,
                'shift' => $item->shift,
                'completed_washing' => $completedWashing,
                'completed_ironing' => $completedIroning,
                'completed_packing' => $completedPacking,
                'total_completed' => $completedWashing + $completedIroning + $completedPacking,
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
            'shift' => '-',
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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $petugas->update($request->only(['nama', 'role', 'status']));
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

        $petugasList = Petugas::where('status', 'Aktif')->orderBy('nama')->get();

        return view('petugas_piket.washing.index', compact('transactions', 'petugasList'));
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

        $petugasList = Petugas::where('status', 'Aktif')->orderBy('nama')->get();

        return view('petugas_piket.setrika.index', compact('transactions', 'petugasList'));
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

        $petugasList = Petugas::where('status', 'Aktif')->orderBy('nama')->get();

        return view('petugas_piket.packing.index', compact('transactions', 'petugasList'));
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

        DB::beginTransaction();
        
        try {
            $transaksi = Transaksi::with(['details.layanan', 'tasks'])->findOrFail($transaksiId);
            $stage = $request->stage;

            // Cari task yang sesuai
            $task = $transaksi->tasks()->where('stage', $stage)->first();

            if (!$task) {
                DB::rollBack();
                return redirect()->back()->with('error', "Tugas tidak ditemukan.");
            }

            $task->update([
                'status' => 'completed',
                'petugas_id' => Auth::id(),
                'petugas_name' => $request->petugas_name,
                'completed_at' => now(),
            ]);

            // Auto-deduct Inventory if stage is washing
            if ($stage === 'washing') {
                // Deduct 1 unit of detergent
                $detergent = \App\Models\Inventory::where('category', 'detergent')
                    ->where('quantity', '>', 0)
                    ->first();
                
                if ($detergent) {
                    $detergent->decrement('quantity', 1);
                }

                // Deduct 1 unit of fragrance
                $fragrance = \App\Models\Inventory::where('category', 'fragrance')
                    ->where('quantity', '>', 0)
                    ->first();
                
                if ($fragrance) {
                    $fragrance->decrement('quantity', 1);
                }
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

            DB::commit();

            // Generate WhatsApp Notification Link
            $phone = preg_replace('/^0/', '62', $transaksi->customer_phone);
            $msg = "Halo " . $transaksi->customer_name . ", pesanan Anda #" . $transaksi->transaksi_code . " saat ini telah selesai pada tahap " . ucfirst($stage) . ". \n\nCek progress lengkapnya di: " . route('track.status', ['nota_number' => $transaksi->transaksi_code]);
            $waLink = "https://wa.me/" . $phone . "?text=" . urlencode($msg);

            session()->flash('notification_link', $waLink);

            return redirect()->back()->with('success', 'Tugas ' . ucfirst($stage) . ' berhasil diselesaikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Complete Task Failed', [
                'operation' => 'petugas.completeTask',
                'user_id' => Auth::id(),
                'transaksi_id' => $transaksiId,
                'stage' => $request->stage ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Gagal menyelesaikan tugas. Silakan coba lagi atau hubungi administrator.');
        }
    }

    // Update status transaksi via Operations Hub (Lama - Masih dipertahankan untuk kompatibilitas jika perlu)
    public function updateTaskStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,disortir,dicuci,dikeringkan,disetrika,dipacking,selesai,diambil'
        ]);

        $transaksi = Transaksi::with(['tasks'])->findOrFail($id);
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

        try {
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

        } catch (\Exception $e) {
            \Log::error('Inventory Adjustment Failed', [
                'operation' => 'petugas.adjustInventory',
                'user_id' => Auth::id(),
                'inventory_id' => $id,
                'adjustment' => $request->adjustment ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui stok. Silakan coba lagi.');
        }
    }

    // Halaman History
    public function history()
    {
        $user = Auth::user();
        
        // ✅ FIX: Allow both admin and staff
        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403, 'Akses ditolak. Hanya admin dan staff yang dapat mengakses halaman ini.');
        }

        $division = strtolower((string) $user->division);
        
        // ✅ FIX: Admin can see all history
        if ($user->role === 'admin') {
            $completedTasks = \App\Models\LaundryTask::where('status', 'completed')
                ->with(['transaksi'])
                ->orderBy('completed_at', 'desc')
                ->paginate(15);
        } elseif (in_array($division, ['washing', 'ironing', 'setrika', 'packing'])) {
            // Staff only see their division's history
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
