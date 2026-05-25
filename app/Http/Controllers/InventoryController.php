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
            $item->increment('quantity');
        } else {
            // Prevent negative stock
            if ($item->quantity > 0) {
                $item->decrement('quantity');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak bisa kurang dari 0',
                    'qty' => $item->quantity
                ], 400);
            }
        }

        $item->refresh();

        return response()->json([
            'success' => true,
            'qty' => $item->quantity
        ]);
    }

    public function approveAdjustment($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $requestAdjust = InventoryAdjustmentRequest::where('status', 'pending')->lockForUpdate()->findOrFail($id);
                $item = Inventory::where('id', $requestAdjust->inventory_id)->lockForUpdate()->first();

                if ($item) {
                    // Validate that adjustment won't cause negative stock
                    $newQuantity = $item->quantity + $requestAdjust->adjustment;
                    if ($newQuantity < 0) {
                        throw new \Exception('Penyesuaian akan menyebabkan stok negatif. Stok saat ini: ' . $item->quantity . ', Penyesuaian: ' . $requestAdjust->adjustment);
                    }
                    
                    $item->quantity = $newQuantity;
                    $item->save();
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
}
