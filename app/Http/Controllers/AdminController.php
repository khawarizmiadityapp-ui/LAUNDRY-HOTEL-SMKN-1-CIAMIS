<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Pengeluaran;
use App\Services\TransactionService;
use App\Services\ErrorLoggingService;

class AdminController extends Controller
{
    protected $transactionService;
    protected $errorLogger;

    public function __construct(TransactionService $transactionService, ErrorLoggingService $errorLogger)
    {
        $this->transactionService = $transactionService;
        $this->errorLogger = $errorLogger;
    }
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

        // Cache dashboard statistics for 5 minutes
        $stats = Cache::remember('dashboard_stats', 300, function () {
            $today = Carbon::today();
            $thisMonth = Carbon::now();

            return [
                'total_orders' => Transaksi::count(),
                'orders_today' => Transaksi::whereDate('created_at', $today)->count(),
                'processing' => Transaksi::whereIn('status', ['diterima', 'disortir', 'dicuci', 'dikeringkan', 'disetrika', 'dipacking'])->count(),
                'completed' => Transaksi::where('status', 'selesai')->count(),
                'total_income' => Transaksi::where('payment_status', 'lunas')->sum('total_price'),
                // BUG FIX 4: Filter pengeluaran per bulan ini
                'total_expense' => Pengeluaran::whereMonth('tanggal', $thisMonth->month)
                    ->whereYear('tanggal', $thisMonth->year)
                    ->sum('nominal'),
            ];
        });

        // Cache chart data for 5 minutes
        $chartData = Cache::remember('dashboard_chart_data_v2', 300, function () {
            
            // --- WEEKLY DATA (Last 7 Days) ---
            $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
            
            $weeklyIncome = Transaksi::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
                ->where('payment_status', 'lunas')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->groupBy('date')->pluck('total', 'date');
                
            $weeklyExpense = Pengeluaran::select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(nominal) as total'))
                ->where('tanggal', '>=', $sevenDaysAgo)
                ->groupBy('date')->pluck('total', 'date');

            $weeklyTransactions = Transaksi::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->where('created_at', '>=', $sevenDaysAgo)
                ->groupBy('date')->pluck('total', 'date');

            $weekly = ['labels' => [], 'income' => [], 'expense' => [], 'transactions' => []];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $weekly['labels'][] = Carbon::now()->subDays($i)->format('D');
                $weekly['income'][] = $weeklyIncome->get($date, 0);
                $weekly['expense'][] = $weeklyExpense->get($date, 0);
                $weekly['transactions'][] = $weeklyTransactions->get($date, 0);
            }

            $isSqlite = DB::connection()->getDriverName() === 'sqlite';

            // --- DAILY DATA (Today, 06:00 to 22:00 in 2-hour intervals) ---
            $today = Carbon::today();
            if ($isSqlite) {
                $dailyIncome = Transaksi::select(DB::raw("CAST(strftime('%H', created_at) AS INTEGER) as hour"), DB::raw('SUM(total_price) as total'))
                    ->where('payment_status', 'lunas')->whereDate('created_at', $today)
                    ->groupBy('hour')->pluck('total', 'hour');
                    
                $dailyExpense = Pengeluaran::select(DB::raw("CAST(strftime('%H', tanggal) AS INTEGER) as hour"), DB::raw('SUM(nominal) as total'))
                    ->whereDate('tanggal', $today)
                    ->groupBy('hour')->pluck('total', 'hour');

                $dailyTransactions = Transaksi::select(DB::raw("CAST(strftime('%H', created_at) AS INTEGER) as hour"), DB::raw('COUNT(*) as total'))
                    ->whereDate('created_at', $today)
                    ->groupBy('hour')->pluck('total', 'hour');
            } else {
                $dailyIncome = Transaksi::select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total_price) as total'))
                    ->where('payment_status', 'lunas')->whereDate('created_at', $today)
                    ->groupBy('hour')->pluck('total', 'hour');
                    
                $dailyExpense = Pengeluaran::select(DB::raw('HOUR(tanggal) as hour'), DB::raw('SUM(nominal) as total'))
                    ->whereDate('tanggal', $today)
                    ->groupBy('hour')->pluck('total', 'hour');

                $dailyTransactions = Transaksi::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as total'))
                    ->whereDate('created_at', $today)
                    ->groupBy('hour')->pluck('total', 'hour');
            }

            $daily = ['labels' => [], 'income' => [], 'expense' => [], 'transactions' => []];
            for ($h = 6; $h <= 22; $h += 2) {
                $daily['labels'][] = sprintf('%02d:00', $h);
                $daily['income'][] = $dailyIncome->get($h, 0) + $dailyIncome->get($h+1, 0);
                $daily['expense'][] = $dailyExpense->get($h, 0) + $dailyExpense->get($h+1, 0);
                $daily['transactions'][] = $dailyTransactions->get($h, 0) + $dailyTransactions->get($h+1, 0);
            }

            // --- MONTHLY DATA (Last 6 Months) ---
            $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
            
            if ($isSqlite) {
                $monthlyIncome = Transaksi::select(
                        DB::raw("CAST(strftime('%m', created_at) AS INTEGER) as month"), 
                        DB::raw("strftime('%Y', created_at) as year"), 
                        DB::raw('SUM(total_price) as total')
                    )
                    ->where('payment_status', 'lunas')->where('created_at', '>=', $sixMonthsAgo)
                    ->groupBy('year', 'month')->get()->keyBy(function($item) {
                        return $item->year . '-' . sprintf('%02d', $item->month);
                    })->map->total;
                    
                $monthlyExpense = Pengeluaran::select(
                        DB::raw("CAST(strftime('%m', tanggal) AS INTEGER) as month"), 
                        DB::raw("strftime('%Y', tanggal) as year"), 
                        DB::raw('SUM(nominal) as total')
                    )
                    ->where('tanggal', '>=', $sixMonthsAgo)
                    ->groupBy('year', 'month')->get()->keyBy(function($item) {
                        return $item->year . '-' . sprintf('%02d', $item->month);
                    })->map->total;

                $monthlyTransactions = Transaksi::select(
                        DB::raw("CAST(strftime('%m', created_at) AS INTEGER) as month"),
                        DB::raw("strftime('%Y', created_at) as year"),
                        DB::raw('COUNT(*) as total')
                    )
                    ->where('created_at', '>=', $sixMonthsAgo)
                    ->groupBy('year', 'month')->get()->keyBy(function($item) {
                        return $item->year . '-' . sprintf('%02d', $item->month);
                    })->map->total;
            } else {
                $monthlyIncome = Transaksi::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total_price) as total'))
                    ->where('payment_status', 'lunas')->where('created_at', '>=', $sixMonthsAgo)
                    ->groupBy('year', 'month')->get()->keyBy(function($item) {
                        return $item->year . '-' . sprintf('%02d', $item->month);
                    })->map->total;
                    
                $monthlyExpense = Pengeluaran::select(DB::raw('MONTH(tanggal) as month'), DB::raw('YEAR(tanggal) as year'), DB::raw('SUM(nominal) as total'))
                    ->where('tanggal', '>=', $sixMonthsAgo)
                    ->groupBy('year', 'month')->get()->keyBy(function($item) {
                        return $item->year . '-' . sprintf('%02d', $item->month);
                    })->map->total;

                $monthlyTransactions = Transaksi::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as total'))
                    ->where('created_at', '>=', $sixMonthsAgo)
                    ->groupBy('year', 'month')->get()->keyBy(function($item) {
                        return $item->year . '-' . sprintf('%02d', $item->month);
                    })->map->total;
            }

            $monthly = ['labels' => [], 'income' => [], 'expense' => [], 'transactions' => []];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $key = $date->format('Y-m');
                $monthly['labels'][] = $date->format('M');
                $monthly['income'][] = $monthlyIncome->get($key, 0);
                $monthly['expense'][] = $monthlyExpense->get($key, 0);
                $monthly['transactions'][] = $monthlyTransactions->get($key, 0);
            }

            return [
                'daily' => $daily,
                'weekly' => $weekly,
                'monthly' => $monthly
            ];
        });

        // Clear old cache to prevent data corruption
        Cache::forget('dashboard_recent_transactions');

        // Cache recent transactions for 2 minutes
        $recentTransactions = Cache::remember('dashboard_recent_transactions', 120, function () {
            return Transaksi::with(['user', 'details.layanan', 'customer'])
                ->latest()
                ->take(10)
                ->get();
        });

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'chartData'));
    }

    // 2. Manajemen Transaksi (Index & Search)
    public function transactions(Request $request)
    {
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
            $pricePerKg = $this->transactionService->getPricePerKg($request->service_type);
            $totalPrice = $request->weight * $pricePerKg;

            $transactionCode = $this->transactionService->generateTransactionCode();

            // BUG FIX 3: Cari atau buat customer, isi customer_id agar transaksi masuk riwayat
            $customer = \App\Models\Customer::firstOrCreate(
                ['no_hp' => $request->customer_phone],
                ['nama' => $request->customer_name, 'alamat' => '']
            );

            $transaksi = Transaksi::create([
                'transaksi_code' => $transactionCode,
                'user_id' => Auth::id(), // Petugas yang input
                'customer_id' => $customer->id, // BUG FIX: Tambahkan customer_id
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

            // BUG FIX: Generate LaundryTasks automatically for Admin-created transactions
            try {
                $transaksi->tasks()->create(['stage' => 'washing', 'status' => 'pending']);
                $transaksi->tasks()->create(['stage' => 'ironing', 'status' => 'pending']);
                $transaksi->tasks()->create(['stage' => 'packing', 'status' => 'pending']);
            } catch (\Exception $taskError) {
                $this->errorLogger->logError($taskError, 'Failed to create laundry tasks', [
                    'transaksi_id' => $transaksi->id,
                    'transaksi_code' => $transaksi->transaksi_code,
                ]);
                throw $taskError; // Re-throw to trigger rollback
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->errorLogger->logError($e, 'Admin Transaction Creation Failed', [
                'operation' => 'admin.storeTransaction',
                'user_id' => Auth::id(),
                'input' => $request->except(['_token', 'password']),
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
            $this->errorLogger->logError($e, 'Update Status Failed', [
                'operation' => 'admin.updateStatus',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'status' => $request->status ?? null,
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui status. Silakan coba lagi.');
        }
    }

    // 5. Update Pembayaran
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:lunas,belum_bayar'
        ]);

        try {
            $transaction = Transaksi::with(['customer'])->findOrFail($id);
            $transaction->update([
                'payment_status' => $request->payment_status
            ]);

            return redirect()->back()->with('success', 'Status pembayaran diperbarui!');

        } catch (\Exception $e) {
            $this->errorLogger->logError($e, 'Update Payment Failed', [
                'operation' => 'admin.updatePayment',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'payment_status' => $request->payment_status ?? null,
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

            // Validasi & Kalkulasi Ulang Harga
            $calculatedTotalPrice = 0;
            // Jika transaksi asal dari POS (multi detail), biarkan price_per_kg tetap 0
            if ($transaction->price_per_kg == 0) {
                $pricePerKg = 0;
                $calculatedTotalPrice = $transaction->details->sum('subtotal');
            } else {
                $calculatedTotalPrice = $request->weight * $pricePerKg;
            }

            $transaction->update([
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'weight'         => $request->weight,
                'service_type'   => $request->service_type,
                'price_per_kg'   => $pricePerKg,
                'total_price'    => $calculatedTotalPrice, // Dihitung di backend agar tidak bisa dimanipulasi client
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'status'         => $request->status,
                'notes'          => $request->notes,
                'updated_at'     => now()
            ]);

            return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');

        } catch (\Exception $e) {
            $this->errorLogger->logError($e, 'Update Transaction Failed', [
                'operation' => 'admin.updateTransaction',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
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
            $this->errorLogger->logError($e, 'Delete Transaction Failed', [
                'operation' => 'admin.destroyTransaction',
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
            ]);

            return back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function updateTarget(Request $request)
    {
        $request->validate([
            'target' => 'required|numeric|min:0'
        ]);

        try {
            $path = base_path('.env');
            if (file_exists($path)) {
                $envContent = file_get_contents($path);
                $oldValue = env('MONTHLY_INCOME_LIMIT', 50000000);
                
                if (str_contains($envContent, 'MONTHLY_INCOME_LIMIT=')) {
                    $envContent = preg_replace('/^MONTHLY_INCOME_LIMIT=.*/m', 'MONTHLY_INCOME_LIMIT=' . $request->target, $envContent);
                } else {
                    $envContent .= "\nMONTHLY_INCOME_LIMIT=" . $request->target;
                }
                
                file_put_contents($path, $envContent);
            }
            
            return back()->with('success', 'Target pendapatan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui target.');
        }
    }
}
