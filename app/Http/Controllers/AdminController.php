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
use App\Models\ActivityLog;
use App\Services\Google2FAService;
use Illuminate\Support\Facades\Hash;
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

        if (!$user->isAdmin()) {
            // Log for debugging
            Log::warning('Unauthorized dashboard access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'expected_role' => 'admin/super_admin',
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

        // Cache service statistics - most ordered services
        $serviceStats = Cache::remember('dashboard_service_stats', 300, function () {
            return DB::table('transaksi_details')
                ->join('layanans', 'transaksi_details.layanan_id', '=', 'layanans.id')
                ->select(
                    'layanans.nama as service_name',
                    DB::raw('SUM(transaksi_details.qty) as total_qty'),
                    DB::raw('COUNT(transaksi_details.id) as order_count'),
                    DB::raw('SUM(transaksi_details.subtotal) as total_revenue')
                )
                ->groupBy('layanans.id', 'layanans.nama')
                ->orderByDesc('order_count')
                ->limit(10)
                ->get();
        });

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'chartData', 'serviceStats'));
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

    /**
     * Endpoint API JSON Detail Transaksi Lengkap
     */
    public function getTransactionDetail($id)
    {
        $transaksi = Transaksi::with(['user', 'customer', 'details.layanan', 'tasks'])->findOrFail($id);

        $phone = $transaksi->customer_phone ?: ($transaksi->customer?->no_hp ?? '');
        $waNumber = format_whatsapp_number($phone);
        
        $pesanWa = urlencode("Halo Kak {$transaksi->customer_name}, kami dari Laundry Hotel SMKN 1 Ciamis ingin menginformasikan status pesanan laundry Anda dengan No. Transaksi *{$transaksi->transaksi_code}*. Status saat ini: *" . strtoupper($transaksi->status) . "*. Total tagihan: *Rp " . number_format($transaksi->total_price, 0, ',', '.') . " (" . strtoupper($transaksi->payment_status) . ")*. Terima kasih!");
        $waUrl = $waNumber ? "https://wa.me/{$waNumber}?text={$pesanWa}" : null;

        $items = [];
        if ($transaksi->details && $transaksi->details->count() > 0) {
            foreach ($transaksi->details as $d) {
                $items[] = [
                    'nama' => $d->layanan?->nama ?? 'Layanan Laundry',
                    'kategori' => $d->layanan?->kategori ?? 'kiloan',
                    'qty' => $d->qty,
                    'satuan' => ($d->layanan?->kategori ?? 'kiloan') === 'kiloan' ? 'kg' : 'pcs',
                    'harga' => (int) $d->price,
                    'subtotal' => (int) $d->subtotal,
                ];
            }
        } else {
            $items[] = [
                'nama' => 'Layanan ' . ucfirst($transaksi->service_type ?: 'Regular'),
                'kategori' => 'kiloan',
                'qty' => $transaksi->weight,
                'satuan' => 'kg',
                'harga' => (int) $transaksi->price_per_kg,
                'subtotal' => (int) $transaksi->total_price,
            ];
        }

        $tasks = $transaksi->tasks->map(function($t) {
            return [
                'stage' => $t->stage,
                'status' => $t->status,
                'petugas' => $t->petugas_name ?: '-',
                'updated_at' => $t->updated_at ? $t->updated_at->format('d/m/Y H:i') : '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaksi->id,
                'transaksi_code' => $transaksi->transaksi_code,
                'created_at' => $transaksi->created_at->format('d M Y, H:i'),
                'status' => $transaksi->status,
                'payment_status' => $transaksi->payment_status,
                'payment_method' => strtoupper(str_replace('_', ' ', $transaksi->payment_method ?: 'Tunai')),
                'customer_name' => $transaksi->customer_name,
                'customer_phone' => $phone,
                'customer_address' => $transaksi->customer?->alamat ?: '-',
                'wa_url' => $waUrl,
                'weight' => $transaksi->weight,
                'total_price' => (int) $transaksi->total_price,
                'dibayar' => (int) ($transaksi->dibayar ?? 0),
                'kembalian' => (int) ($transaksi->kembalian ?? 0),
                'notes' => $transaksi->notes,
                'kasir_name' => $transaksi->user?->name ?? 'Kasir',
                'bukti_pembayaran_url' => $transaksi->bukti_pembayaran ? asset('storage/' . $transaksi->bukti_pembayaran) : null,
                'items' => $items,
                'tasks' => $tasks,
                'nota_url' => route('pos.nota', $transaksi->id),
            ]
        ]);
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

            if ($request->status == 'diambil' && $transaction->payment_status !== 'lunas') {
                return redirect()->back()->with('error', 'Pesanan tidak bisa diambil karena belum dibayar lunas.');
            }

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

            if ($request->status == 'diambil' && $request->payment_status !== 'lunas') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pesanan tidak bisa diambil karena belum dibayar lunas.');
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

    // 8. Manajemen Pengguna (Super Admin & Admin)
    public function users()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }

        $users = User::where('role', '!=', 'customer')->latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang berhak menambahkan pengguna baru.');
        }

        if (!Auth::user()->isSuperAdmin() && $request->role === 'super_admin') {
            abort(403, 'Akses ditolak. Admin tidak memiliki izin untuk membuat akun Super Admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,admin,staff',
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
            'target' => 'required|numeric|min:0',
            'target_type' => 'nullable|in:bulanan,tahunan,bulan_spesifik',
            'target_month' => 'nullable|string',
            'workdays_mode' => 'nullable|in:senin_jumat,senin_sabtu,setiap_hari,custom',
            'custom_days' => 'nullable|numeric|min:1|max:31',
            'holidays_count' => 'nullable|numeric|min:0|max:31',
            'holiday_dates' => 'nullable|string',
        ]);

        try {
            $type = $request->target_type ?? 'bulanan';
            $targetValue = (int) $request->target;

            $targetDate = $request->target_month ? Carbon::parse($request->target_month . '-01') : Carbon::now();
            $targetYear = $targetDate->year;
            $targetMonth = str_pad((string) $targetDate->month, 2, '0', STR_PAD_LEFT);

            if ($type === 'bulan_spesifik') {
                $monthKey = "target_monthly_{$targetYear}_{$targetMonth}";
                \App\Models\Setting::setValue($monthKey, $targetValue);
                \App\Models\DailyTarget::recalculateMonthTargets($targetYear, (int) $targetMonth);
                $msg = "Target khusus untuk bulan " . $targetDate->translatedFormat('F Y') . " berhasil disimpan: Rp " . number_format($targetValue, 0, ',', '.');
            } elseif ($type === 'tahunan') {
                $annualTarget = $targetValue;
                $monthlyTarget = (int) ceil($annualTarget / 12);
                \App\Models\Setting::setValue('target_annual', $annualTarget);
                \App\Models\Setting::setValue('target_monthly', $monthlyTarget);
                \App\Models\DailyTarget::recalculateMonthTargets();
                $msg = "Target tahunan berhasil diperbarui: Rp " . number_format($annualTarget, 0, ',', '.');
            } else {
                $monthlyTarget = $targetValue;
                $annualTarget = $monthlyTarget * 12;
                \App\Models\Setting::setValue('target_monthly', $monthlyTarget);
                \App\Models\Setting::setValue('target_annual', $annualTarget);
                \App\Models\DailyTarget::recalculateMonthTargets();
                $msg = "Target bulanan standar berhasil diperbarui: Rp " . number_format($monthlyTarget, 0, ',', '.');
            }

            // Simpan pengaturan hari kerja jika ada
            if ($request->has('workdays_mode')) {
                \App\Models\Setting::setValue('target_workdays_mode', $request->workdays_mode ?? 'senin_jumat');
            }
            if ($request->has('custom_days')) {
                \App\Models\Setting::setValue('target_custom_days', (int) $request->custom_days);
            }
            if ($request->has('holidays_count')) {
                \App\Models\Setting::setValue('target_holidays_count', (int) $request->holidays_count);
            }
            if ($request->has('holiday_dates')) {
                \App\Models\Setting::setValue('target_holiday_dates', trim((string) $request->holiday_dates));
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui target: ' . $e->getMessage());
        }
    }

    public function settings()
    {
        $adminWA = \App\Models\Setting::getValue('admin_wa', '6282116035029');
        $serviceWA = \App\Models\Setting::getValue('service_wa', '6282116035029');
        $heroImage = \App\Models\Setting::getValue('hero_image', 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?q=80&w=800&auto=format&fit=crop');
        $logoImage = \App\Models\Setting::getValue('logo_image', asset('images/logobening.jpeg'));
        
        // Load kategori pengeluaran dengan jumlah penggunaan
        $kategoriList = \App\Models\KategoriPengeluaran::withCount('pengeluarans')
            ->orderBy('nama')
            ->get();
        
        // Load seluruh akun pengguna sistem (Super Admin, Admin & Petugas/Staff)
        $userList = User::orderByRaw("CASE WHEN role IN ('super_admin', 'super admin', 'superadmin') THEN 0 WHEN role = 'admin' THEN 1 ELSE 2 END")
            ->orderBy('name')
            ->get();

        // Siapkan info 2FA Google Authenticator untuk Admin yang sedang login
        $currentAdmin = Auth::user();
        if ($currentAdmin && (empty($currentAdmin->google2fa_secret) || strlen($currentAdmin->google2fa_secret) > 16)) {
            $currentAdmin->google2fa_secret = Google2FAService::generateSecretKey(10);
            $currentAdmin->save();
        }
        $adminQrCodeUrl = $currentAdmin ? Google2FAService::getQrCodeImageUrl('Bening Laundry', $currentAdmin->email, $currentAdmin->google2fa_secret) : '';
        $adminFormattedSecret = $currentAdmin ? Google2FAService::formatSecretKey($currentAdmin->google2fa_secret) : '';
        $adminRawSecret = $currentAdmin ? $currentAdmin->google2fa_secret : '';
        
        return view('admin.settings', compact('adminWA', 'serviceWA', 'heroImage', 'logoImage', 'kategoriList', 'userList', 'adminQrCodeUrl', 'adminFormattedSecret', 'adminRawSecret'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'admin_wa' => 'required|string',
            'service_wa' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        try {
            // Update WhatsApp number in settings table
            $phone = format_whatsapp_number($request->admin_wa);
            \App\Models\Setting::setValue('admin_wa', $phone);

            $servicePhone = format_whatsapp_number($request->service_wa);
            \App\Models\Setting::setValue('service_wa', $servicePhone);

            // Handle hero_image upload
            if ($request->hasFile('hero_image')) {
                $file = $request->file('hero_image');
                $filename = 'hero_' . time() . '.' . $file->extension();
                $file->move(public_path('images'), $filename);
                \App\Models\Setting::setValue('hero_image', asset('images/' . $filename));
            }

            // Handle logo_image upload
            if ($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                $filename = 'logo_' . time() . '.' . $file->extension();
                $file->move(public_path('images'), $filename);
                \App\Models\Setting::setValue('logo_image', asset('images/' . $filename));
            }

            return back()->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    public function updateUserPassword(Request $request, $id)
    {
        // Pastikan Admin atau Super Admin yang bisa melakukan aksi ini
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang berhak mengubah kata sandi akun pengguna.');
        }

        $currentAdmin = Auth::user();
        $user = User::findOrFail($id);

        // Keamanan: Admin biasa TIDAK BISA mengubah kata sandi Super Admin
        if ($user->isSuperAdmin() && !$currentAdmin->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Admin tidak memiliki izin untuk mengubah kata sandi akun Super Admin.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'otp.required' => 'Kode OTP Google Authenticator wajib diisi.',
            'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        // Pastikan Admin yang login memiliki secret key
        if (empty($currentAdmin->google2fa_secret)) {
            $currentAdmin->google2fa_secret = Google2FAService::generateSecretKey();
            $currentAdmin->save();
        }

        // Verifikasi kode OTP Google Authenticator Admin/Super Admin yang sedang login
        if (!Google2FAService::verifyKey($currentAdmin->google2fa_secret, $request->otp)) {
            return back()->with('error', 'Kode OTP Google Authenticator salah atau telah kedaluwarsa. Silakan periksa aplikasi Authenticator Anda.');
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            // Log activity audit trail
            $roleTitle = $currentAdmin->isSuperAdmin() ? 'Super Admin' : 'Admin';
            ActivityLog::create([
                'log_name' => 'security',
                'description' => "{$roleTitle} {$currentAdmin->name} mengubah password akun {$user->name} ({$user->email}) dengan verifikasi 2FA",
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'event' => 'password_updated_with_2fa',
                'causer_type' => User::class,
                'causer_id' => $currentAdmin->id,
                'properties' => [
                    'target_user_id' => $user->id,
                    'target_user_email' => $user->email,
                    'target_user_role' => $user->role,
                ],
            ]);

            return back()->with('success', "Password untuk akun {$user->name} ({$user->email}) berhasil diperbarui dengan verifikasi 2FA!");
        } catch (\Exception $e) {
            Log::error('Update User Password Failed', [
                'operation' => 'admin.updateUserPassword',
                'admin_id' => Auth::id(),
                'target_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui password: ' . $e->getMessage());
        }
    }
}
