<?php

namespace App\Http\Controllers;

use App\Http\Auth\LoginController;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    // 1. Dashboard Utama (Statistik)
    public function dashboard()
    {
        $today = Carbon::today();
        
        // Statistik Ringkasan
        $stats = [
            'orders_today' => Transaksi::whereDate('created_at', $today)->count(),
            'processing' => Transaksi::whereIn('status', ['dicuci', 'disortir', 'dikeringkan', 'disetrika'])->count(),
            'completed' => Transaksi::where('status', 'selesai')->count(),
            'income_today' => Transaksi::whereDate('created_at', $today)
                                ->where('payment_status', 'lunas')
                                ->sum('total_price'),
            'income_month' => Transaksi::whereMonth('created_at', $today->month)
                                ->whereYear('created_at', $today->year)
                                ->where('payment_status', 'lunas')
                                ->sum('total_price'),
        ];

        // Transaksi Terbaru (Limit 5)
        $recentTransactions = Transaksi::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions'));
    }

    // 2. Manajemen Transaksi (Index & Search)
    public function transactions(Request $request)
    {
        $query = Transaksi::with('user');

        // Fitur Search
        if ($request->has('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('transaction_code', 'like', '%' . $request->search . '%');
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

    // 3. Simpan Transaksi Baru
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string',
            'service_type' => 'required|in:regular,express',
            'weight' => 'required|numeric|min:0.1',
            'notes' => 'nullable|string',
        ]);

        // Ambil Harga dari Database
        $price = ServicePrice::where('service_type', $request->service_type)->first();
        $pricePerKg = $price ? $price->price_per_kg : 6000; // Fallback harga
        
        $totalPrice = $request->weight * $pricePerKg;

        // Generate Kode Transaksi Unik
        $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        Transaksi::create([
            'transaction_code' => $transactionCode,
            'user_id' => auth()->id(), // Petugas yang input
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'service_type' => $request->service_type,
            'weight' => $request->weight,
            'price_per_kg' => $pricePerKg,
            'total_price' => $totalPrice,
            'status' => 'diterima',
            'payment_status' => 'belum_bayar',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil dibuat!');
    }

    // 4. Update Status Proses (Timeline)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,disortir,dicuci,dikeringkan,disetrika,dipacking,selesai,diambil'
        ]);

        $transaction = Transaksi::findOrFail($id);
        $transaction->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        // Opsional: Tambahkan log history status jika punya tabel history
        // TransactionStatusHistory::create([...]);

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }

    // 5. Update Pembayaran
    public function updatePayment(Request $request, $id)
    {
        $transaction = Transaksi::findOrFail($id);
        $transaction->update([
            'payment_status' => $request->payment_status // lunas / belum_bayar
        ]);

        return redirect()->back()->with('success', 'Status pembayaran diperbarui!');
    }

    // 6. Laporan Keuangan
    public function reports(Request $request)
    {
        $today = Carbon::today();
        
        // Filter Tanggal (Opsional)
        $startDate = $request->start_date ?? $today->copy()->startOfMonth();
        $endDate = $request->end_date ?? $today;

        $query = Transaksi::where('payment_status', 'lunas');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $transactions = $query->latest()->paginate(20);
        $totalIncome = $query->sum('total_price');

        // Data untuk Chart (Pendapatan 7 hari terakhir)
        $chartData = Transaksi::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(total_price) as total')
            )
            ->where('payment_status', 'lunas')
            ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports', compact('transactions', 'totalIncome', 'chartData', 'startDate', 'endDate'));
    }

    // 7. Manajemen Harga Layanan
    public function prices()
    {
        $prices = ServicePrice::all();
        return view('admin.prices', compact('prices'));
    }

    public function updatePrices(Request $request)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*.id' => 'required|exists:service_prices,id',
            'prices.*.price_per_kg' => 'required|numeric|min:0',
        ]);

        foreach ($request->prices as $priceData) {
            ServicePrice::where('id', $priceData['id'])->update([
                'price_per_kg' => $priceData['price_per_kg']
            ]);
        }

        return redirect()->back()->with('success', 'Harga berhasil diperbarui!');
    }

    // 8. Manajemen Pengguna (Admin & Petugas)
    public function users()
    {
        $users = User::where('role', '!=', 'customer')->latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,staff',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function destroyTransaction($id)
    {
        Transaction::findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus!');
    }

}