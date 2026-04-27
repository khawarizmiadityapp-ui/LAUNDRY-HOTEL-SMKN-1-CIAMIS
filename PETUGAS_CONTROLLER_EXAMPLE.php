<?php

/**
 * Contoh Implementasi Controller Methods untuk Petugas
 *
 * File: app/Http/Controllers/PetugasController.php
 *
 * Tambahkan methods ini ke PetugasController yang sudah ada
 */

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\LaundryTask;
use App\Models\Inventory;
use App\Models\InventoryAdjustmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    /**
     * Middleware untuk memastikan akses berdasarkan division
     *
     * @param array $allowedDivisions
     * @return void
     */
    private function ensureStaffDivisionAccess(array $allowedDivisions): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Akses ditolak');
        }

        // Admin bisa akses semua
        if ($user->role === 'admin') {
            return;
        }

        // Staff hanya bisa akses sesuai division
        if ($user->role !== 'staff') {
            abort(403, 'Akses ditolak');
        }

        $division = strtolower((string) $user->division);
        if (!in_array($division, $allowedDivisions, true)) {
            abort(403, 'Akun Anda tidak memiliki akses ke modul ini.');
        }
    }

    // ====================================================================
    // DASHBOARD
    // ====================================================================

    /**
     * Dashboard Petugas - menampilkan statistik dan task overview
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        if (Auth::user()->role !== 'staff' && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = Auth::user();
        $division = strtolower((string) ($user->division ?? ''));

        // Ambil data statistik berdasarkan division
        $stats = [
            'total_tasks' => LaundryTask::when($user->role === 'staff', fn($q) => $q->where('division', $division))->count(),
            'pending_tasks' => LaundryTask::when($user->role === 'staff', fn($q) => $q->where('division', $division))->where('status', 'pending')->count(),
            'in_progress' => LaundryTask::when($user->role === 'staff', fn($q) => $q->where('division', $division))->where('status', 'in_progress')->count(),
            'completed' => LaundryTask::when($user->role === 'staff', fn($q) => $q->where('division', $division))->where('status', 'completed')->count(),
        ];

        return view('petugas_piket.dashboard', [
            'stats' => $stats,
            'division' => $division,
        ]);
    }

    // ====================================================================
    // WASHING
    // ====================================================================

    /**
     * Display halaman Washing Tasks
     */
    public function washing(Request $request)
    {
        $this->ensureStaffDivisionAccess(['washing']);

        $user = Auth::user();

        $tasks = LaundryTask::where('division', 'washing')
            ->when($user->role === 'staff', fn($q) => $q->where('assigned_to', $user->id))
            ->with('transaksi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas_piket.washing', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Update task status (untuk semua divisi)
     */
    public function updateTaskStatus(Request $request, $taskId)
    {
        $task = LaundryTask::findOrFail($taskId);

        // Cek akses
        $user = Auth::user();
        if ($user->role === 'staff' && $task->assigned_to !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update([
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status task berhasil diperbarui',
            'data' => $task,
        ]);
    }

    /**
     * Mark task as complete
     */
    public function completeTask(Request $request, $taskId)
    {
        $task = LaundryTask::findOrFail($taskId);

        $user = Auth::user();
        if ($user->role === 'staff' && $task->assigned_to !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diselesaikan',
        ]);
    }

    // ====================================================================
    // SETRIKA (IRONING)
    // ====================================================================

    /**
     * Display halaman Setrika Tasks
     */
    public function setrika(Request $request)
    {
        $this->ensureStaffDivisionAccess(['setrika', 'ironing']);

        $user = Auth::user();

        $tasks = LaundryTask::where('division', 'setrika')
            ->when($user->role === 'staff', fn($q) => $q->where('assigned_to', $user->id))
            ->with('transaksi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas_piket.setrika', [
            'tasks' => $tasks,
        ]);
    }

    // ====================================================================
    // PACKING
    // ====================================================================

    /**
     * Display halaman Packing Tasks
     */
    public function packing(Request $request)
    {
        $this->ensureStaffDivisionAccess(['packing']);

        $user = Auth::user();

        $tasks = LaundryTask::where('division', 'packing')
            ->when($user->role === 'staff', fn($q) => $q->where('assigned_to', $user->id))
            ->with('transaksi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas_piket.packing', [
            'tasks' => $tasks,
        ]);
    }

    // ====================================================================
    // INVENTORY
    // ====================================================================

    /**
     * Display halaman Inventory
     */
    public function inventory(Request $request)
    {
        $this->ensureStaffDivisionAccess(['inventory']);

        $inventories = Inventory::with('product')
            ->orderBy('product_name', 'asc')
            ->paginate(15);

        return view('petugas_piket.inventory', [
            'inventories' => $inventories,
        ]);
    }

    /**
     * Adjust inventory stock
     */
    public function adjustInventory(Request $request, $inventoryId)
    {
        $this->ensureStaffDivisionAccess(['inventory']);

        $validated = $request->validate([
            'quantity' => 'required|integer',
            'notes' => 'nullable|string|max:255',
            'adjustment_type' => 'required|in:add,subtract,set', // add, subtract, or set to specific amount
        ]);

        try {
            DB::beginTransaction();

            $inventory = Inventory::findOrFail($inventoryId);
            $oldQuantity = $inventory->quantity;

            // Update berdasarkan tipe adjustment
            if ($validated['adjustment_type'] === 'add') {
                $inventory->quantity += $validated['quantity'];
            } elseif ($validated['adjustment_type'] === 'subtract') {
                $inventory->quantity -= $validated['quantity'];
            } else {
                $inventory->quantity = $validated['quantity'];
            }

            $inventory->save();

            // Log adjustment
            InventoryAdjustmentRequest::create([
                'inventory_id' => $inventory->id,
                'user_id' => Auth::id(),
                'old_quantity' => $oldQuantity,
                'new_quantity' => $inventory->quantity,
                'adjustment_type' => $validated['adjustment_type'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'approved',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory berhasil diperbarui',
                'data' => $inventory,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui inventory: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ====================================================================
    // HISTORY
    // ====================================================================

    /**
     * Display halaman History (completed tasks)
     */
    public function history(Request $request)
    {
        if (Auth::user()->role !== 'staff' && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = Auth::user();

        $completedTasks = LaundryTask::where('status', 'completed')
            ->when($user->role === 'staff', function ($query) use ($user) {
                $division = strtolower((string) ($user->division ?? ''));
                return $query->where('division', $division)
                    ->where('assigned_to', $user->id);
            })
            ->with('transaksi')
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return view('petugas_piket.history', [
            'tasks' => $completedTasks,
        ]);
    }
}
