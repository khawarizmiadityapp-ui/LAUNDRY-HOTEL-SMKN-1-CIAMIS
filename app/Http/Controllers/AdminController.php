<?php

namespace App\Http\Controllers;

use App\Http\Auth\LoginController;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pengeluaran;


class AdminController extends Controller
{
    // 1. Dashboard Utama (Statistik)
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        if ($user->role !== 'admin') {
            // Log for debugging
            Log::warning('Unauthorized dashboard access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'expected_role' => 'admin',
            ]);
            
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator. Role Anda: ' . ($user->role ?? 'unknown'));
        }

        $today = Carbon::today();
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();

        // Statistik Ringkasan
        $stats = [
            'total_orders' => Transaksi::count(), // Semua transaksi terdaftar
            'orders_today' => Transaksi::whereDate('created_at', $today)->count(),
            'processing' => Transaksi::whereIn('status', ['diterima', 'disortir', 'dicuci', 'dikeringkan', 'disetrika', 'dipacking'])->count(),
            'completed' => Transaksi::where('status', 'selesai')->count(),
            'total_income' => Transaksi::where('payment_status', 'lunas')->sum('total_price'),
            'total_expense' => \App\Models\Pengeluaran::sum('nominal'),
        ];

        // Data untuk Chart (Pendapatan & Pengeluaran 7 hari terakhir)
        $incomeData = Transaksi::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('payment_status', 'lunas')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $expenseData = \App\Models\Pengeluaran::select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('SUM(nominal) as total')
            )
            ->where('tanggal', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        // Format chart data for JS
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('D');
            $chartData['labels'][] = $label;
            $chartData['income'][] = $incomeData->get($date, 0);
            $chartData['expense'][] = $expenseData->get($date, 0);
        }

        // Transaksi Terbaru (Limit 10)
        $recentTransactions = Transaksi::with(['user', 'details.layanan'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'chartData'));
    }

    // 2. Manajemen Transaksi (Index & Search)
    public function transactions(Request $request)
    {
        $query = Transaksi::with(['user', 'details.layanan']);

        // Fitur Search
        if ($request->has('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('transaksi_code', 'like', '%' . $request->search . '%');
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
            'payment_method' => 'required|in:tunai,qris,transfer,cash,dana',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Ambil Harga dari Database
            $price = ServicePrice::where('service_type', $request->service_type)->first();
            $pricePerKg = $price ? $price->price_per_kg : 6000; // Fallback harga

            $totalPrice = $request->weight * $pricePerKg;

            $monthlyIncomeLimit = (int) env('MONTHLY_INCOME_LIMIT', 50000000);
            $currentMonthIncome = Transaksi::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_price');

            if (($currentMonthIncome + $totalPrice) > $monthlyIncomeLimit) {
                DB::rollBack();
                return redirect()->back()->withErrors([
                    'weight' => 'Transaksi melebihi batas pemasukan bulanan. Sisa kuota: Rp ' . number_format(max(0, $monthlyIncomeLimit - $currentMonthIncome), 0, ',', '.'),
                ])->withInput();
            }

            // Generate Kode Transaksi Unik
            $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            Transaksi::create([
                'transaksi_code' => $transactionCode,
                'user_id' => Auth::id(), // Petugas yang input
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'service_type' => $request->service_type,
                'weight' => $request->weight,
                'price_per_kg' => $pricePerKg,
                'total_price' => $totalPrice,
                'status' => 'diterima',
                'payment_status' => 'belum_bayar',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Admin Transaction Creation Failed', [
                'operation' => 'admin.storeTransaction',
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['_token']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi. Silakan coba lagi atau hubungi administrator.');
        }
    }

    // 4. Update Status Proses (Timeline)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,disortir,dicuci,dikeringkan,disetrika,dipacking,selesai,diambil'
        ]);

        try {
            $transaction = Transaksi::with(['details.layanan', 'tasks'])->findOrFail($id);
            $transaction->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            // Opsional: Tambahkan log history status jika punya tabel history
            // TransactionStatusHistory::create([...]);

            return redirect()->back()->with('success', 'Status berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Update Status Failed', [
                'operation' => 'admin.updateStatus',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'status' => $request->status ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui status. Silakan coba lagi.');
        }
    }

    // 5. Update Pembayaran
    public function updatePayment(Request $request, $id)
    {
        try {
            $transaction = Transaksi::with(['customer'])->findOrFail($id);
            $transaction->update([
                'payment_status' => $request->payment_status // lunas / belum_bayar
            ]);

            return redirect()->back()->with('success', 'Status pembayaran diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Update Payment Failed', [
                'operation' => 'admin.updatePayment',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'payment_status' => $request->payment_status ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui status pembayaran. Silakan coba lagi.');
        }
    }

    // 5b. Update Keseluruhan Transaksi
    public function updateTransaction(Request $request, $id)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string',
            'weight'         => 'required|numeric|min:0.1',
            'service_type'   => 'required|in:regular,express',
            'total_price'    => 'required|numeric|min:0',
            'payment_status' => 'required|in:lunas,belum_bayar',
            'payment_method' => 'required|in:tunai,qris,transfer,cash,dana',
            'status'         => 'required|in:diterima,disortir,dicuci,dikeringkan,disetrika,dipacking,selesai,diambil',
            'notes'          => 'nullable|string',
        ]);

        try {
            $transaction = Transaksi::with(['details.layanan'])->findOrFail($id);

            // Ambil harga per kg untuk tipe layanan yang baru/dipilih
            $price = ServicePrice::where('service_type', $request->service_type)->first();
            $pricePerKg = $price ? $price->price_per_kg : 6000;

            // Jika transaksi asal dari POS (multi detail), biarkan price_per_kg tetap 0
            if ($transaction->price_per_kg == 0) {
                $pricePerKg = 0;
            }

            $transaction->update([
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'weight'         => $request->weight,
                'service_type'   => $request->service_type,
                'price_per_kg'   => $pricePerKg,
                'total_price'    => $request->total_price,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'status'         => $request->status,
                'notes'          => $request->notes,
                'updated_at'     => now()
            ]);

            return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Update Transaction Failed', [
                'operation' => 'admin.updateTransaction',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['_token']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui transaksi. Silakan coba lagi atau hubungi administrator.');
        }
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

        DB::beginTransaction();

        try {
            foreach ($request->prices as $priceData) {
                ServicePrice::where('id', $priceData['id'])->update([
                    'price_per_kg' => $priceData['price_per_kg']
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Harga berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Update Prices Failed', [
                'operation' => 'admin.updatePrices',
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'input' => $request->except(['_token']),
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui harga. Silakan coba lagi.');
        }
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
            'division' => 'nullable|required_if:role,staff|in:washing,ironing,packing,customer_service,inventory',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'division' => $request->role === 'staff' ? $request->division : null,
            ]);

            return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Create User Failed', [
                'operation' => 'admin.storeUser',
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'input' => $request->except(['_token', 'password']),
            ]);

            return redirect()->back()
                ->withInput($request->except('password'))
                ->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        }
    }

    public function destroyTransaction($id)
    {
        try {
            $transaksi = Transaksi::with(['details', 'tasks'])->findOrFail($id);
            $transaksi->delete();

            return back()->with('success', 'Data berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Delete Transaction Failed', [
                'operation' => 'admin.destroyTransaction',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

}
