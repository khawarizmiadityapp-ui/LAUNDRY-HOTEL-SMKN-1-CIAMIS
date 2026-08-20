<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // BUG FIX 3: Add input validation for date filters
        $request->validate([
            'filter' => 'nullable|in:bulanan,tahunan,custom',
            'dari' => 'required_if:filter,custom|date',
            'sampai' => 'required_if:filter,custom|date|after_or_equal:dari',
        ]);

        $filter = $request->filter ?? 'bulanan';

        $query = Transaksi::where('payment_status', 'lunas');
        $piutangQuery = Transaksi::where('payment_status', 'belum_bayar');
        $pengeluaranQuery = Pengeluaran::query();

        if ($filter == 'bulanan') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);

            $piutangQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);

            $pengeluaranQuery->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year);
        } elseif ($filter == 'tahunan') {
            $query->whereYear('created_at', now()->year);
            $piutangQuery->whereYear('created_at', now()->year);
            // BUG FIX 1: Pengeluaran juga harus terfilter per tahun
            $pengeluaranQuery->whereYear('tanggal', now()->year);
        } elseif ($filter == 'custom') {
            if ($request->dari && $request->sampai) {
                // BUG FIX 2: Gunakan startOfDay() dan endOfDay() agar presisi
                $start = Carbon::parse($request->dari)->startOfDay();
                $end = Carbon::parse($request->sampai)->endOfDay();
                $query->whereBetween('created_at', [
                    $start,
                    $end
                ]);
                $piutangQuery->whereBetween('created_at', [
                    $start,
                    $end
                ]);
                // BUG FIX 2: Pengeluaran juga gunakan rentang yang sama
                $pengeluaranQuery->whereBetween('tanggal', [
                    $start,
                    $end
                ]);
            }
        }

        $pemasukan = (clone $query)->sum('total_price');
        $pengeluaran = (clone $pengeluaranQuery)->sum('nominal');
        $laba = $pemasukan - $pengeluaran;
        
        $piutang = (clone $piutangQuery)->sum('total_price');
        $utang = 0; // Tidak ada data hutang pada sistem saat ini

        $persentaseMasuk = 0;
        $persentaseKeluar = 0;

        if ($filter == 'bulanan') {
            $prevMonth = now()->subMonth();
            $pemasukanLalu = Transaksi::where('payment_status', 'lunas')
                ->whereMonth('created_at', $prevMonth->month)
                ->whereYear('created_at', $prevMonth->year)
                ->sum('total_price');
                
            $pengeluaranLalu = Pengeluaran::whereMonth('tanggal', $prevMonth->month)
                ->whereYear('tanggal', $prevMonth->year)
                ->sum('nominal');

            if ($pemasukanLalu > 0) {
                $persentaseMasuk = (($pemasukan - $pemasukanLalu) / $pemasukanLalu) * 100;
            } elseif ($pemasukan > 0) {
                $persentaseMasuk = 100;
            }

            if ($pengeluaranLalu > 0) {
                $persentaseKeluar = (($pengeluaran - $pengeluaranLalu) / $pengeluaranLalu) * 100;
            } elseif ($pengeluaran > 0) {
                $persentaseKeluar = 100;
            }
        }

        $annualTarget = \App\Models\DailyTarget::getAnnualTarget();
        $limitPemasukanBulanan = \App\Models\DailyTarget::getMonthlyTarget();
        $targetAnggaran = $limitPemasukanBulanan;

        $realisasiBulanIni = Transaksi::where('payment_status', 'lunas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $persenTargetBulanIni = $limitPemasukanBulanan > 0
            ? min(100, round(($realisasiBulanIni / $limitPemasukanBulanan) * 100, 2))
            : 0;

        // DATA CHART
        $months = [];
        $dataMasuk = [];
        $dataKeluar = [];
        $laporanBulanan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $months[] = $date->format('M');

            $totalMasuk = Transaksi::where('payment_status', 'lunas')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');

            $totalKeluar = Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('nominal');

            $dataMasuk[] = $totalMasuk;
            $dataKeluar[] = $totalKeluar;

            $laporanBulanan[] = [
                'bulan' => $date->translatedFormat('F'),
                'tahun' => $date->year,
                'pemasukan' => $totalMasuk,
                'pengeluaran' => $totalKeluar,
                'laba' => $totalMasuk - $totalKeluar,
            ];
        }

        $kategoriTerbesar = [
            'nama' => '-',
            'persen' => 0
        ];

        $kategoriList = Pengeluaran::distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $distribusiPengeluaran = (clone $pengeluaranQuery)
            ->selectRaw('kategori, SUM(nominal) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) use ($pengeluaran) {
                return [
                    'kategori' => $item->kategori,
                    'total' => $item->total,
                    'persen' => $pengeluaran > 0 ? round(($item->total / $pengeluaran) * 100, 1) : 0
                ];
            });

        $persenLaba = $pemasukan > 0
            ? ($laba / $pemasukan) * 100
            : 0;

        // Fetch recent transactions for the ledger
        $recentTransactions = Transaksi::with(['user', 'customer'])
            ->where('payment_status', 'lunas')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->latest()
            ->take(10)
            ->get();

        // Fetch recent expenses for the ledger
        $recentExpenses = Pengeluaran::latest()
            ->where('tanggal', '>=', Carbon::now()->subDays(30))
            ->take(10)
            ->get();

        // ═══════════ DAILY TARGET TRACKING & CARRY FORWARD ═══════════
        // Recalculate daily targets sequentially for current month
        $monthTargets = \App\Models\DailyTarget::recalculateMonthTargets(now()->year, now()->month);
        
        $today = Carbon::today();
        $todayTarget = $monthTargets->firstWhere('date', $today) ?? \App\Models\DailyTarget::getOrCreateForDate($today);

        // Daily targets list for display (all days of month up to today, or all days of month)
        $dailyTargets = \App\Models\DailyTarget::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->orderBy('date', 'desc')
            ->get();

        // Calculate summary stats
        $weeklyTargetSum = $dailyTargets->sum('adjusted_target');
        $weeklyActualSum = $dailyTargets->sum('net_income');
        $weeklyAchievementRate = $weeklyTargetSum > 0 
            ? round(($weeklyActualSum / $weeklyTargetSum) * 100, 1) 
            : 0;

        $workdaysMode = \App\Models\DailyTarget::getWorkdaysMode();
        $activeWorkDaysCount = \App\Models\DailyTarget::getTargetDaysInMonth();
        $baseDailyTarget = \App\Models\DailyTarget::calculateBaseTarget();
        $customDays = (int) \App\Models\Setting::getValue('target_custom_days', 22);
        $holidaysCount = (int) \App\Models\Setting::getValue('target_holidays_count', 0);
        $holidayDatesString = \App\Models\Setting::getValue('target_holiday_dates', '');

        return view('admin.laporan_keuangan.index', [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'laba' => $laba,
            'piutang' => $piutang,
            'utang' => $utang,
            'months' => $months,
            'dataMasuk' => $dataMasuk,
            'dataKeluar' => $dataKeluar,
            'filter' => $filter,
            'annualTarget' => $annualTarget,
            'targetAnggaran' => $targetAnggaran,
            'kategoriTerbesar' => $kategoriTerbesar,
            'kategoriList' => $kategoriList,
            'distribusiPengeluaran' => $distribusiPengeluaran,
            'persenLaba' => $persenLaba,
            'laporanBulanan' => $laporanBulanan,
            'limitPemasukanBulanan' => $limitPemasukanBulanan,
            'realisasiBulanIni' => $realisasiBulanIni,
            'persenTargetBulanIni' => $persenTargetBulanIni,
            'recentTransactions' => $recentTransactions,
            'recentExpenses' => $recentExpenses,
            'persentaseMasuk' => $persentaseMasuk,
            'persentaseKeluar' => $persentaseKeluar,
            // Daily Targets
            'todayTarget' => $todayTarget,
            'dailyTargets' => $dailyTargets,
            'weeklyTargetSum' => $weeklyTargetSum,
            'weeklyActualSum' => $weeklyActualSum,
            'weeklyAchievementRate' => $weeklyAchievementRate,
            // Workdays & Holidays
            'workdaysMode' => $workdaysMode,
            'activeWorkDaysCount' => $activeWorkDaysCount,
            'baseDailyTarget' => $baseDailyTarget,
            'customDays' => $customDays,
            'holidaysCount' => $holidaysCount,
            'holidayDatesString' => $holidayDatesString,
        ]);
    }

    /**
     * Ekspor Laporan Keuangan BKU (Buku Kas Umum) berbentuk PDF resmi.
     */
    public function exportBkuPdf(Request $request)
    {
        $filter = $request->filter ?? 'bulanan';

        if ($filter === 'bulanan') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $periodeLabel = 'Bulan ' . now()->translatedFormat('F Y');
        } elseif ($filter === 'tahunan') {
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
            $periodeLabel = 'Tahun ' . now()->year;
        } elseif ($filter === 'custom' && $request->dari && $request->sampai) {
            $start = Carbon::parse($request->dari)->startOfDay();
            $end = Carbon::parse($request->sampai)->endOfDay();
            $periodeLabel = Carbon::parse($request->dari)->translatedFormat('d F Y') . ' s.d. ' . Carbon::parse($request->sampai)->translatedFormat('d F Y');
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $periodeLabel = 'Bulan ' . now()->translatedFormat('F Y');
        }

        // 1. Hitung Saldo Awal (semua penerimaan - pengeluaran sebelum tanggal start)
        $pemasukanSebelumnya = Transaksi::where('payment_status', 'lunas')
            ->where('created_at', '<', $start)
            ->sum('total_price');

        $pengeluaranSebelumnya = Pengeluaran::where('tanggal', '<', $start->format('Y-m-d'))
            ->sum('nominal');

        $saldoAwal = (int) ($pemasukanSebelumnya - $pengeluaranSebelumnya);

        // 2. Ambil Transaksi Pemasukan (Lunas) dalam periode
        $transaksis = Transaksi::with(['customer', 'details.layanan'])
            ->where('payment_status', 'lunas')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->map(function ($trx) {
                $detailNama = $trx->details->pluck('layanan.nama')->filter()->join(', ');
                $subInfo = $detailNama ?: ucfirst($trx->service_type) . " ({$trx->weight} kg)";

                return [
                    'timestamp' => $trx->created_at->timestamp,
                    'tanggal' => $trx->created_at->format('d/m/Y'),
                    'no_bukti' => $trx->transaksi_code,
                    'uraian' => 'Penerimaan Jasa Laundry - ' . ($trx->customer_name ?: 'Pelanggan'),
                    'sub_info' => $subInfo . ' [Metode: ' . strtoupper($trx->payment_method ?: 'Tunai') . ']',
                    'debet' => (int) $trx->total_price,
                    'kredit' => 0,
                    'type' => 'masuk',
                ];
            });

        // 3. Ambil Pengeluaran dalam periode
        $pengeluarans = Pengeluaran::with(['kategoriPengeluaran'])
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get()
            ->map(function ($exp) {
                $kategoriNama = $exp->kategoriPengeluaran?->nama ?? $exp->kategori;

                return [
                    'timestamp' => $exp->tanggal->startOfDay()->timestamp + 3600, // slightly offset for sequential ordering
                    'tanggal' => $exp->tanggal->format('d/m/Y'),
                    'no_bukti' => $exp->id_transaksi,
                    'uraian' => 'Pengeluaran: ' . $exp->nama,
                    'sub_info' => 'Kategori: ' . $kategoriNama . ($exp->keterangan ? ' | ' . $exp->keterangan : ''),
                    'debet' => 0,
                    'kredit' => (int) $exp->nominal,
                    'type' => 'keluar',
                ];
            });

        // 4. Gabungkan dan Urutkan Kronologis Ascending
        $allEntries = $transaksis->concat($pengeluarans)->sortBy('timestamp')->values();

        // 5. Kalkulasi Saldo Berjalan (Running Balance)
        $runningSaldo = $saldoAwal;
        $totalDebet = 0;
        $totalKredit = 0;
        $ledgerItems = [];

        foreach ($allEntries as $entry) {
            $totalDebet += $entry['debet'];
            $totalKredit += $entry['kredit'];
            $runningSaldo += ($entry['debet'] - $entry['kredit']);

            $entry['saldo'] = $runningSaldo;
            $ledgerItems[] = $entry;
        }

        $saldoAkhir = $runningSaldo;
        $tanggalAwalFormatted = $start->format('d/m/Y');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.bku', compact(
            'periodeLabel',
            'saldoAwal',
            'ledgerItems',
            'totalDebet',
            'totalKredit',
            'saldoAkhir',
            'tanggalAwalFormatted'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('BKU-Laundry-Hotel-' . now()->format('Ymd_His') . '.pdf');
    }
}
