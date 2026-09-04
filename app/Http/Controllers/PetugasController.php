<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\JadwalPetugas;
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

        if ($user->isAdmin()) {
            return;
        }

        if (!$user->isStaff()) {
            abort(403, 'Akses ditolak');
        }

        $division = strtolower((string) $user->division);
        
        if ($division === 'all_roles') {
            return;
        }

        if (!in_array($division, $allowedDivisions, true)) {
            abort(403, 'Akun Anda tidak memiliki akses ke modul ini.');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Allow super admin, admin, and staff
        if (!$user->isAdmin() && !$user->isStaff()) {
            abort(403, 'Akses ditolak. Hanya administrator dan petugas yang dapat mengakses halaman ini.');
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

        // Data Jadwal Piket Hari Ini untuk Siswa (Model 1)
        $jadwalHariIni = JadwalPetugas::hariIni()->orderBy('shift')->orderBy('nama')->get();
        $activePiket = session('active_piket_nama') ? JadwalPetugas::hariIni()->where('nama', session('active_piket_nama'))->first() : null;

        return view('petugas_piket.dashboard', compact('pendingTasks', 'completedToday', 'division', 'jadwalHariIni', 'activePiket'));
    }
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

    public function apiIndex()
    {
        $petugas = Petugas::orderBy('nama')->get();
        return response()->json($petugas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'role' => 'required|in:Washing,Setrika,Ironing,Packing,Kasir,Kurir,Admin,Operasional',
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

    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:Washing,Setrika,Ironing,Packing,Kasir,Kurir,Admin,Operasional',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $petugas->update($request->only(['nama', 'role']));
        return response()->json($petugas);
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();
        return response()->json(['message' => 'Petugas berhasil dihapus']);
    }

    /**
     * Check-in stasiun tugas mandiri oleh murid / petugas (Model 1)
     */
    public function checkInStation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwal_petugas,id',
            'station' => 'required|in:washing,setrika,packing,kasir',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->with('error', 'Silakan pilih nama dan stasiun tugas yang valid.');
        }

        $jadwal = JadwalPetugas::findOrFail($request->jadwal_id);
        $jadwal->update([
            'selected_station' => $request->station,
            'checked_in_at' => now(),
            'status' => 'hadir',
        ]);

        // Simpan sesi aktif browser untuk autofill
        session([
            'active_piket_id' => $jadwal->id,
            'active_piket_nama' => $jadwal->nama,
            'active_piket_station' => $jadwal->selected_station,
        ]);

        $stationRouteMap = [
            'washing' => 'petugas_piket.washing.index',
            'setrika' => 'petugas_piket.setrika.index',
            'packing' => 'petugas_piket.packing.index',
            'kasir' => 'petugas.pos.index',
        ];

        $redirectRoute = $stationRouteMap[$request->station] ?? 'petugas_piket.dashboard';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Selamat bertugas, {$jadwal->nama}! Anda telah memilih bagian " . ucfirst($request->station),
                'redirect_url' => route($redirectRoute),
                'jadwal' => $jadwal,
            ]);
        }

        return redirect()->route($redirectRoute)->with('success', "Selamat bertugas, {$jadwal->nama}! Anda telah memilih bagian " . ucfirst($request->station));
    }

    /**
     * API untuk mendapatkan status duty aktif hari ini
     */
    public function getActiveDuty()
    {
        $activePiketNama = session('active_piket_nama');
        $activeDuty = null;

        if ($activePiketNama) {
            $activeDuty = JadwalPetugas::hariIni()->where('nama', $activePiketNama)->first();
        }

        return response()->json([
            'active_duty' => $activeDuty,
            'session_nama' => $activePiketNama,
            'today_roster' => JadwalPetugas::hariIni()->get(),
        ]);
    }

    public function washing()
    {
        $this->ensureStaffDivisionAccess(['washing']);

        // Ambil transaksi yang memiliki task 'washing' dengan status 'pending'
        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'washing')->where('status', 'pending');
        })->with(['details.layanan'])->orderBy('created_at', 'asc')->get();

        // Ambil daftar petugas dari jadwal hari ini jika ada, jika tidak fallback ke master Petugas
        $todaySchedules = JadwalPetugas::hariIni()->get();
        if ($todaySchedules->isNotEmpty()) {
            $petugasList = $todaySchedules->sortBy(function ($item) {
                return $item->selected_station === 'washing' ? 0 : 1;
            })->values();
        } else {
            $petugasList = Petugas::orderBy('nama')->get();
        }

        $activePiketNama = session('active_piket_nama');
        $inventories = \App\Models\Inventory::select('id', 'name', 'category', 'unit_of_measurement')->orderBy('name')->get();

        return view('petugas_piket.washing.index', compact('transactions', 'petugasList', 'inventories', 'activePiketNama'));
    }

    public function setrika()
    {
        $this->ensureStaffDivisionAccess(['ironing', 'setrika']);

        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'ironing')->where('status', 'pending');
        })->whereDoesntHave('tasks', function ($q) {
            $q->where('stage', 'washing')->where('status', '!=', 'completed');
        })
        ->with(['details.layanan'])->get();

        $todaySchedules = JadwalPetugas::hariIni()->get();
        if ($todaySchedules->isNotEmpty()) {
            $petugasList = $todaySchedules->sortBy(function ($item) {
                return $item->selected_station === 'setrika' ? 0 : 1;
            })->values();
        } else {
            $petugasList = Petugas::orderBy('nama')->get();
        }

        $activePiketNama = session('active_piket_nama');

        return view('petugas_piket.setrika.index', compact('transactions', 'petugasList', 'activePiketNama'));
    }

    public function packing()
    {
        $this->ensureStaffDivisionAccess(['packing']);

        $transactions = Transaksi::whereHas('tasks', function ($q) {
            $q->where('stage', 'packing')->where('status', 'pending');
        })->whereDoesntHave('tasks', function ($q) {
            $q->whereIn('stage', ['washing', 'ironing'])->where('status', '!=', 'completed');
        })
        ->with(['details.layanan'])->get();

        $todaySchedules = JadwalPetugas::hariIni()->get();
        if ($todaySchedules->isNotEmpty()) {
            $petugasList = $todaySchedules->sortBy(function ($item) {
                return $item->selected_station === 'packing' ? 0 : 1;
            })->values();
        } else {
            $petugasList = Petugas::orderBy('nama')->get();
        }

        $activePiketNama = session('active_piket_nama');

        return view('petugas_piket.packing.index', compact('transactions', 'petugasList', 'activePiketNama'));
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

        $this->ensureStaffDivisionAccess([$request->stage]);

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

            // Update overall transaction status based on next step
            // When a task is completed, set status to the NEXT stage (sedang processing next step)
            $statusMap = [
                'washing' => 'disetrika',   // After washing done → now ironing
                'ironing' => 'dipacking',   // After ironing done → now packing
                'packing' => 'selesai',     // After packing done → ready for pickup
            ];

            if (isset($statusMap[$stage])) {
                $transaksi->update(['status' => $statusMap[$stage]]);
            }

            DB::commit();

            $stageNames = [
                'washing' => 'Pencucian',
                'ironing' => 'Setrika',
                'packing' => 'Packing',
            ];
            
            $stageName = $stageNames[$stage] ?? ucfirst($stage);
            $customerName = $transaksi->customer_name;
            
            return redirect()->back()->with('success', 'Tugas ' . $stageName . ' untuk pelanggan *' . $customerName . '* berhasil diselesaikan!');

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

    public function completeTaskBulk(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transaksi,id',
            'stage' => 'required|in:washing,ironing,packing',
            'petugas_name' => 'required|string|max:100',
        ]);

        $this->ensureStaffDivisionAccess([$request->stage]);

        DB::beginTransaction();

        try {
            $stage = $request->stage;
            // Update status to NEXT stage (sedang processing next step)
            $statusMap = [
                'washing' => 'disetrika',   // After washing done → now ironing
                'ironing' => 'dipacking',   // After ironing done → now packing
                'packing' => 'selesai',     // After packing done → ready for pickup
            ];
            $stageNames = [
                'washing' => 'Pencucian',
                'ironing' => 'Setrika',
                'packing' => 'Packing',
            ];
            $stageName = $stageNames[$stage] ?? ucfirst($stage);
            $count = 0;

            foreach ($request->transaction_ids as $transaksiId) {
                $transaksi = Transaksi::find($transaksiId);
                
                if (!$transaksi) continue;

                $task = $transaksi->tasks()->where('stage', $stage)->first();

                if ($task && $task->status !== 'completed') {
                    $task->update([
                        'status' => 'completed',
                        'petugas_id' => Auth::id(),
                        'petugas_name' => $request->petugas_name,
                        'completed_at' => now(),
                    ]);

                    if ($stage === 'washing') {
                        // For bulk we only auto-deduct standard supplies
                        $this->inventoryService->deductWashingSupplies($transaksi->id, $stage);
                    }

                    if (isset($statusMap[$stage])) {
                        $transaksi->update(['status' => $statusMap[$stage]]);
                    }

                    $count++;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Sebanyak $count tugas $stageName berhasil diselesaikan secara bersamaan!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Complete Task Bulk Failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menyelesaikan tugas secara massal: ' . $e->getMessage());
        }
    }

    // Update status transaksi via Operations Hub (Lama - Masih dipertahankan untuk kompatibilitas jika perlu)
    public function updateTaskStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,disortir,dicuci,dikeringkan,disetrika,dipacking,selesai,diambil'
        ]);

        $transaksi = Transaksi::with(['tasks'])->findOrFail($id);
        
        if ($request->status == 'diambil' && $transaksi->payment_status !== 'lunas') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa diambil karena belum dibayar lunas.');
        }

        $transaksi->status = $request->status;
        $transaksi->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function inventory()
    {
        $this->ensureStaffDivisionAccess(['inventory']);

        $inventory = \App\Models\Inventory::all()->groupBy('category');
        return view('petugas_piket.inventory.index', compact('inventory'));
    }

    public function storeInventory(Request $request)
    {
        $this->ensureStaffDivisionAccess(['inventory']);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit_type' => 'required|in:botol,sachet,pcs',
            'capacity_per_unit' => 'required|integer|min:1',
            'stock_subunits' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        try {
            $unitOfMeasurement = ($request->unit_type === 'pcs') ? 'pcs' : 'ml';

            \App\Models\Inventory::create([
                'name' => $request->name,
                'category' => strtolower($request->category),
                'unit_type' => $request->unit_type,
                'capacity_per_unit' => $request->capacity_per_unit,
                'unit_of_measurement' => $unitOfMeasurement,
                'stock_units' => $request->quantity,
                'stock_subunits' => $request->stock_subunits,
                'quantity' => $request->quantity,
                'minimum_stock' => $request->minimum_stock,
            ]);
            return redirect()->back()->with('success', 'Barang baru berhasil ditambahkan ke inventory.');
        } catch (\Exception $e) {
            \Log::error('Create Inventory Failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
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

            if (Auth::user()->isAdmin()) {
                $item->adjustStockUnits($adjustment);
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

    public function history()
    {
        $user = Auth::user();

        // Allow super admin, admin, and staff
        if (!$user->isAdmin() && !$user->isStaff()) {
            abort(403, 'Akses ditolak. Hanya administrator dan petugas yang dapat mengakses halaman ini.');
        }

        $division = strtolower((string) $user->division);

        // Admin, Super Admin, and All Roles can see all history
        if ($user->isAdmin() || $division === 'all_roles') {
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

