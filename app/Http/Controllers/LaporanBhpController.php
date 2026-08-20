<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Pengeluaran;
use App\Models\InventoryAdjustmentRequest;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBhpController extends Controller
{
    /**
     * Tampilkan Laporan Barang Habis Pakai (BHP).
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'kritis') {
                $query->whereRaw('stock_units <= minimum_stock');
            } elseif ($request->status === 'menipis') {
                $query->whereRaw('stock_units > minimum_stock AND stock_units <= (minimum_stock * 2)');
            } elseif ($request->status === 'aman') {
                $query->whereRaw('stock_units > (minimum_stock * 2)');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->orderBy('category')->orderBy('name')->paginate(15)->withQueryString();

        // ─── Stat Cards ─────────────────────────────────────────────────
        $totalJenisBhp = Inventory::count();
        $totalStokUnits = Inventory::sum('stock_units');
        $itemKritisCount = Inventory::whereRaw('stock_units <= minimum_stock')->count();
        $itemMenipisCount = Inventory::whereRaw('stock_units > minimum_stock AND stock_units <= (minimum_stock * 2)')->count();

        // Total pengeluaran bahan habis pakai / deterjen bulan ini
        $pengeluaranBhpBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where(function($q) {
                $q->where('nama', 'like', '%deterjen%')
                  ->orWhere('nama', 'like', '%pewangi%')
                  ->orWhere('nama', 'like', '%softener%')
                  ->orWhere('nama', 'like', '%plastik%')
                  ->orWhere('nama', 'like', '%hanger%')
                  ->orWhere('nama', 'like', '%bhp%')
                  ->orWhere('nama', 'like', '%sabun%')
                  ->orWhere('kategori', 'like', '%bahan%')
                  ->orWhere('kategori', 'like', '%operasional%');
            })
            ->sum('nominal');

        // Categories list for filter
        $categories = Inventory::distinct()->pluck('category')->filter()->values();

        // Recent stock adjustment history
        $recentAdjustments = InventoryAdjustmentRequest::with(['inventory', 'requester', 'approver'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.pengeluaran.bhp', compact(
            'items',
            'totalJenisBhp',
            'totalStokUnits',
            'itemKritisCount',
            'itemMenipisCount',
            'pengeluaranBhpBulanIni',
            'categories',
            'recentAdjustments'
        ));
    }

    /**
     * Ekspor Laporan BHP ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->orderBy('category')->orderBy('name')->get();

        $totalJenis = $items->count();
        $totalUnits = $items->sum('stock_units');
        $itemKritis = $items->filter(fn($i) => $i->stock_units <= ($i->minimum_stock ?? 5))->count();

        $pdf = Pdf::loadView('admin.pdf.bhp', compact(
            'items',
            'totalJenis',
            'totalUnits',
            'itemKritis'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-BHP-Laundry-Hotel-' . now()->format('Ymd_His') . '.pdf');
    }
}
