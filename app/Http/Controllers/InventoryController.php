<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryAdjustmentRequest;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
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
            $item->quantity += 1;
        } else {
            $item->quantity = max(0, $item->quantity - 1);
        }

        $item->save();

        return response()->json([
            'success' => true,
            'qty' => $item->quantity
        ]);
    }

    public function approveAdjustment($id)
    {
        $requestAdjust = InventoryAdjustmentRequest::where('status', 'pending')->findOrFail($id);
        $item = Inventory::findOrFail($requestAdjust->inventory_id);

        $item->quantity = max(0, $item->quantity + $requestAdjust->adjustment);
        $item->save();

        $requestAdjust->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan stok disetujui dan stok sudah diperbarui.');
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
