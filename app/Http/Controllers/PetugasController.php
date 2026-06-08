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
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use App\Services\InventoryService;

class PetugasController extends Controller
{
    protected $notificationService;
    protected $inventoryService;

    public function __construct(NotificationService $notificationService, InventoryService $inventoryService)
    {
        $this->notificationService = $notificationService;
        $this->inventoryService = $inventoryService;
    }

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

        // Ambil semua task yang sudah selesai dan kelompokkan berdasarkan nama petugas dan stage
        $completedTasks = \App\Models\LaundryTask::where('status', 'completed')
            ->select('petugas_name', 'stage', \DB::raw('count(*) as total'))
            ->groupBy('petugas_name', 'stage')
            ->get();

        $petugasData = $petugasList->map(function (Petugas $item) use ($completedTasks) {
            $completedWashing = $completedTasks->where('petugas_name', $item->nama)->where('stage', 'washing')->first()->total ?? 0;
            $completedIroning = $completedTasks->where('petugas_name', $item->nama)->where('stage', 'ironing')->first()->total ?? 0;
            $completedPacking = $completedTasks->where('petugas_name', $item->nama)->where('stage', 'packing')->first()->total ?? 0;

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
            'role' => 'required|in:Washing,Setrika,Packing,Kasir',
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
            'status' => 'Aktif',
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
            'role' => 'sometimes|in:Washing,Setrika,Packing,Kasir',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $petugas->update($request->only(['nama', 'role']));
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

        $petugasList = Petugas::where('role', 'Washing')->orderBy('nama')->get();
        $inventories = \App\Models\Inventory::select('id', 'name', 'category')->orderBy('name')->get();

        return view('petugas_piket.washing.index', compact('transactions', 'petugasList', 'inventories'));
    }

    // Halaman Setrika
    public function setrika()
    {
        $this->ensureStaffDivisionAccess(['ironing', 'setrika']);

        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'ironing')->where('status', 'pending');
        })->whereDoesntHave('tasks', function ($q) {
            $q->where('stage', 'washing')->where('status', '!=', 'completed');
        })
        ->with(['details.layanan'])->get();

        $petugasList = Petugas::where('role', 'Setrika')->orderBy('nama')->get();

        return view('petugas_piket.setrika.index', compact('transactions', 'petugasList'));
    }

    // Halaman Packing
    public function packing()
    {
        $this->ensureStaffDivisionAccess(['packing']);

        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'packing')->where('status', 'pending');
        })->whereDoesntHave('tasks', function ($q) {
            $q->whereIn('stage', ['washing', 'ironing'])->where('status', '!=', 'completed');
        })
        ->with(['details.layanan'])->get();

        $petugasList = Petugas::where('role', 'Packing')->orderBy('nama')->get();

        return view('petugas_piket.packing.index', compact('transactions', 'petugasList'));
    }


    /**
     * Selesaikan task tertentu (washing, ironing, packing)
     */
    public function completeTask(Request $request, $transaksiId)
    {
        $request->validate([
            'stage' => 'required|in:washing,ironing,packing',
            'petugas_name' => 'nullable|string|max:255',
            'materials' => 'nullable|array',
            'materials.*.id' => 'required_with:materials|exists:inventories,id',
            'materials.*.quantity' => 'required_with:materials|numeric|min:0.1'
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
                if ($request->has('materials') && is_array($request->materials)) {
                    $this->inventoryService->deductCustomWashingSupplies($transaksi->id, $stage, $request->materials);
                } else {
                    $this->inventoryService->deductWashingSupplies($transaksi->id, $stage);
                }
            }

            // Update overall transaction status
            $statusMap = [
                'washing' => 'dicuci',
                'ironing' => 'disetrika',
                'packing' => 'dipacking',
            ];

            if (isset($statusMap[$stage])) {
                $transaksi->update(['status' => $statusMap[$stage]]);
            }

            // If packing is completed, mark transaction as 'selesai' (ready for pickup)
            if ($stage === 'packing') {
                // Check if all tasks are completed
                $allTasksCompleted = $transaksi->tasks()->where('status', '!=', 'completed')->count() === 0;

                if ($allTasksCompleted) {
                    $transaksi->update(['status' => 'selesai']);
                }
            }

            DB::commit();

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

            return redirect()->back()->with('error', 'Gagal menyelesaikan tugas: ' . $e->getMessage());
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

    // Tambah barang baru ke inventory
    public function storeInventory(Request $request)
    {
        $this->ensureStaffDivisionAccess(['inventory']);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        try {
            \App\Models\Inventory::create($request->only(['name', 'category', 'quantity', 'unit', 'minimum_stock']));
            return redirect()->back()->with('success', 'Barang baru berhasil ditambahkan ke inventory.');
        } catch (\Exception $e) {
            \Log::error('Create Inventory Failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menambahkan barang. Silakan coba lagi.');
        }
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
            $completedTasks = new \Illuminate\Pagination\LengthAwarePaginator(collect(), 0, 15);
        }

        return view('petugas_piket.history.index', compact('completedTasks', 'division'));
    }

    // Halaman Transaksi untuk Petugas Kasir (Customer Service)
    public function transactions(Request $request)
    {
        $this->ensureStaffDivisionAccess(['customer_service']);

        $query = Transaksi::with(['user', 'details.layanan']);

        // Fitur Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('transaksi_code', 'like', '%' . $request->search . '%');
            });
        }

        // Fitur Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Fitur Filter Pembayaran
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transaksi.index', compact('transactions'));
    }

}

