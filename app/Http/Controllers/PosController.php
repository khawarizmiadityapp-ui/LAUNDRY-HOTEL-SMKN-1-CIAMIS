<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $layanans  = Layanan::aktif()->orderBy('kategori')->orderBy('nama')->get();
        $kategoris = Layanan::aktif()->distinct()->pluck('kategori');

        // Fetch transactions ready for pickup (status 'selesai')
        $readyToPickup = Transaksi::where('status', 'selesai')
            ->with(['details.layanan'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Pre-format data for Alpine.js (avoid arrow functions in Blade @json)
        $layanansJson = $layanans->map(function ($l) {
            return [
                'id'       => $l->id,
                'nama'     => $l->nama,
                'kategori' => $l->kategori,
                'harga'    => (float) $l->harga,
                'satuan'   => $l->satuan,
                'needs_washing' => (bool) $l->needs_washing,
                'needs_ironing' => (bool) $l->needs_ironing,
                'needs_packing' => (bool) $l->needs_packing,
            ];
        });

        return view('pos.index', compact('layanans', 'kategoris', 'layanansJson', 'readyToPickup'));
    }

    /**
     * AJAX: Search customers by name or phone.
     */
    public function searchCustomer(Request $request)
    {
        $q = $request->get('q', '');

        $customers = Customer::where('nama', 'like', "%{$q}%")
            ->orWhere('no_hp', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'nama', 'no_hp', 'alamat']);

        return response()->json($customers);
    }

    /**
     * AJAX: Quick-add a new customer.
     */
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        try {
            $customer = Customer::create($request->only(['nama', 'no_hp', 'alamat']));

            return response()->json($customer, 201);

        } catch (\Exception $e) {
            \Log::error('Customer Creation Failed', [
                'operation' => 'pos.storeCustomer',
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'input' => $request->except(['_token']),
            ]);

            return response()->json([
                'error' => 'Gagal membuat customer. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Process a POS order (multi-service).
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'items'            => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanans,id',
            'items.*.qty'      => 'required|numeric|min:0.1',
            'payment_method'   => 'required|in:tunai,qris,transfer',
            'payment_status'   => 'required|in:lunas,belum_bayar',
            'notes'            => 'nullable|string',
        ]);

        try {
            $customer = Customer::findOrFail($request->customer_id);

            // Calculate totals
            $subtotal = 0;
            $detailsData = [];

            foreach ($request->items as $item) {
                $layanan  = Layanan::findOrFail($item['layanan_id']);
                $qty      = (float) $item['qty'];
                $price    = (float) $layanan->harga;
                $itemSub  = $qty * $price;
                $subtotal += $itemSub;

                $detailsData[] = [
                    'layanan_id' => $layanan->id,
                    'qty'        => $qty,
                    'price'      => $price,
                    'subtotal'   => $itemSub,
                ];
            }

            $totalPrice = $subtotal;

            $monthlyIncomeLimit = (int) env('MONTHLY_INCOME_LIMIT', 50000000);
            $currentMonthIncome = Transaksi::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_price');

            if (($currentMonthIncome + $totalPrice) > $monthlyIncomeLimit) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'items' => 'Pesanan melebihi batas pemasukan bulanan. Sisa kuota pemasukan bulan ini: Rp ' . number_format(max(0, $monthlyIncomeLimit - $currentMonthIncome), 0, ',', '.'),
                    ]);
            }

            // Transaction code
            $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            DB::beginTransaction();
            
            $transaksi = Transaksi::create([
                'transaksi_code' => $transactionCode,
                'user_id'        => Auth::id(),
                'customer_id'    => $customer->id,
                'customer_name'  => $customer->nama,
                'customer_phone' => $customer->no_hp ?? '-',
                'service_type'   => 'regular',
                'weight'         => collect($detailsData)->sum('qty'),
                'price_per_kg'   => 0,
                'total_price'    => $totalPrice,
                'status'         => 'diterima',
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
            ]);

            foreach ($detailsData as $detail) {
                $transaksi->details()->create($detail);
            }

            // Inisialisasi Tracking Tasks secara Dinamis
            $items = collect($request->items)->map(function($item) {
                return Layanan::find($item['layanan_id']);
            });

            $needsWashing = $items->filter(fn($l) => $l && ($l->needs_washing === null || $l->needs_washing == true))->isNotEmpty();
            $needsIroning = $items->filter(fn($l) => $l && ($l->needs_ironing === null || $l->needs_ironing == true))->isNotEmpty();
            $needsPacking = $items->filter(fn($l) => $l && ($l->needs_packing === null || $l->needs_packing == true))->isNotEmpty();

            if ($needsWashing) {
                $transaksi->tasks()->create(['stage' => 'washing', 'status' => 'pending']);
            }
            if ($needsIroning) {
                $transaksi->tasks()->create(['stage' => 'ironing', 'status' => 'pending']);
            }
            if ($needsPacking) {
                $transaksi->tasks()->create(['stage' => 'packing', 'status' => 'pending']);
            }

            DB::commit();

            return redirect()->route('pos.nota', $transaksi->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('POS Order Creation Failed', [
                'operation' => 'pos.store',
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['_token']),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat pesanan. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Print receipt / nota.
     */
    public function nota($id)
    {
        $transaksi = Transaksi::with(['details.layanan', 'customer', 'user'])->findOrFail($id);
        $subtotal  = $transaksi->details->sum('subtotal');
        $totalTagihan  = $transaksi->total_price;

        return view('pos.nota', compact('transaksi', 'subtotal', 'totalTagihan'));
    }

    /**
     * Mark transaction as picked up.
     */
    public function pickup(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            if ($transaksi->status !== 'selesai') {
                return back()->with('error', 'Pesanan belum selesai diproses.');
            }

            $transaksi->update([
                'status' => 'diambil',
                'updated_at' => now(),
            ]);

            return back()->with('success', "Pesanan #{$transaksi->transaksi_code} berhasil ditandai sebagai sudah diambil.");

        } catch (\Exception $e) {
            \Log::error('Pickup Update Failed', [
                'operation' => 'pos.pickup',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui status pickup. Silakan coba lagi.');
        }
    }
}
