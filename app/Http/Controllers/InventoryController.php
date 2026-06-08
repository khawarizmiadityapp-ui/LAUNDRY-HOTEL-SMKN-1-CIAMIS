<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryAdjustmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ErrorLoggingService;

class InventoryController extends Controller
{
    protected $errorLogger;

    public function __construct(ErrorLoggingService $errorLogger)
    {
        $this->errorLogger = $errorLogger;
    }

    public function index()
    {
        // ambil berdasarkan kategori
        $detergents = Inventory::where('category', 'detergent')->get();
        $fragrances = Inventory::where('category', 'fragrance')->get();
        $hangers = Inventory::where('category', 'hanger')->get();

        $totalItems = Inventory::sum('quantity');
        $lowStock = Inventory::where('quantity', '<', 20)->count();
        $pendingRequests = InventoryAdjustmentRequest::with(['inventory', 'requester'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.inventory.index', compact(
            'detergents',
            'fragrances',
            'hangers',
            'totalItems',
            'lowStock',
            'pendingRequests'
        ));
    }

    // update qty (optional kalau mau tombol + - jalan)
    public function updateQty(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);

        $request->validate([
            'type' => 'required|in:increment,decrement'
        ]);

        if ($request->type === 'increment') {
            $item->adjustStockUnits(1);
        } else {
            try {
                $item->adjustStockUnits(-1);
                $newQty = $item->stock_units;
                if ($newQty < ($item->minimum_stock ?? 5)) {
                    \App\Models\ActivityLog::create([
                        'description' => "Peringatan Stok Rendah: {$item->name} sisa {$newQty}",
                        'causer_id' => Auth::id(),
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak bisa kurang dari 0',
                    'qty' => $item->stock_units
                ], 400);
            }
        }

        $item->refresh();

        return response()->json([
            'success' => true,
            'qty' => $item->stock_units
        ]);
    }

    public function approveAdjustment($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $requestAdjust = InventoryAdjustmentRequest::where('status', 'pending')->lockForUpdate()->findOrFail($id);
                $item = Inventory::where('id', $requestAdjust->inventory_id)->lockForUpdate()->first();

                if ($item) {
                    $item->adjustStockUnits($requestAdjust->adjustment);

                    if ($item->stock_units < ($item->minimum_stock ?? 5)) {
                        \App\Models\ActivityLog::create([
                            'description' => "Peringatan Stok Rendah: {$item->name} sisa {$item->stock_units}",
                            'causer_id' => Auth::id(),
                        ]);
                    }
                }

                $requestAdjust->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
            });

            return redirect()->back()->with('success', 'Permintaan stok disetujui dan stok sudah diperbarui.');
        } catch (\Exception $e) {
            $this->errorLogger->logError($e, 'Inventory Adjustment Approval Failed', [
                'operation' => 'inventory.approveAdjustment',
                'user_id' => Auth::id(),
                'adjustment_request_id' => $id,
            ]);

            return redirect()->back()->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
        }
    }

    public function rejectAdjustment($id)
    {
        $requestAdjust = InventoryAdjustmentRequest::where('status', 'pending')->findOrFail($id);

        $requestAdjust->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan stok ditolak.');
    }

    public function store(Request $request)
    {
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

            Inventory::create([
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
            $this->errorLogger->logError($e, 'Create Inventory Failed', [
                'operation' => 'inventory.store',
                'user_id' => Auth::id(),
            ]);
            return redirect()->back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }
}
