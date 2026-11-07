<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    /**
     * Tampilkan Halaman Manajemen Pembayaran & Rekapan (Harian/Bulanan).
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'daftar');

        // ═══════════════════════════════════════════════════════════════════
        // TAB 1: DAFTAR TRANSAKSI & PEMBAYARAN
        // ═══════════════════════════════════════════════════════════════════
        $query = Transaksi::with(['user', 'details.layanan'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('metode')) {
            $metodeFilter = strtolower($request->metode);
            if ($metodeFilter === 'tunai') {
                $query->where(function ($q) {
                    $q->whereIn('payment_method', ['tunai', 'cash', ''])->orWhereNull('payment_method');
                });
            } elseif ($metodeFilter === 'qris') {
                $query->where('payment_method', 'like', '%qris%');
            } elseif ($metodeFilter === 'transfer') {
                $query->whereNotIn('payment_method', ['tunai', 'cash', 'qris', ''])
                      ->whereNotNull('payment_method');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('transaksi_code', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(10)->appends($request->query());

        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();

        $hariIniTrx = Transaksi::where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$startOfDay, $endOfDay])
            ->get();
        $semuaBelumLunasTrx = Transaksi::where('payment_status', 'belum_bayar')->get();

        $rekapHariIni = calculate_payment_recap($hariIniTrx, $semuaBelumLunasTrx);
        $totalPendapatanHariIni = $rekapHariIni['total_pendapatan'];
        $transaksiBelumLunas = $rekapHariIni['piutang_count'];
        $nominalBelumLunas = $rekapHariIni['piutang_nominal'];
        $qrisConfig = get_qris_config();

        // ═══════════════════════════════════════════════════════════════════
        // TAB 2: REKAPAN HARIAN
        // ═══════════════════════════════════════════════════════════════════
        $tanggalHarian = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $harianStart = Carbon::parse($tanggalHarian)->startOfDay();
        $harianEnd = Carbon::parse($tanggalHarian)->endOfDay();

        $harianTrx = Transaksi::with(['details.layanan', 'customer'])
            ->where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$harianStart, $harianEnd])
            ->get();

        $harianBelumLunasTrx = Transaksi::where('payment_status', 'belum_bayar')
            ->whereBetween('created_at', [$harianStart, $harianEnd])
            ->get();

        $harianRekapMetode = calculate_payment_recap($harianTrx, $harianBelumLunasTrx);
        $harianTotalPendapatan = $harianRekapMetode['total_pendapatan'];
        $harianTotalTransaksi = $harianRekapMetode['total_transaksi'];
        $harianTotalTunai = $harianRekapMetode['tunai_nominal'];
        $harianTotalQris = $harianRekapMetode['qris_nominal'];
        $harianTotalTransfer = $harianRekapMetode['transfer_nominal'];
        $harianTotalNonTunai = $harianRekapMetode['non_tunai_nominal'];

        // Breakdown Pelayanan Harian
        $harianServiceBreakdown = $this->calculateServiceBreakdown($harianTrx);

        // ═══════════════════════════════════════════════════════════════════
        // TAB 3: REKAPAN BULANAN
        // ═══════════════════════════════════════════════════════════════════
        $bulanBulanan = (int) $request->get('bulan', now()->month);
        $tahunBulanan = (int) $request->get('tahun', now()->year);

        $bulananStart = Carbon::create($tahunBulanan, $bulanBulanan, 1)->startOfMonth();
        $bulananEnd = Carbon::create($tahunBulanan, $bulanBulanan, 1)->endOfMonth();

        $bulananTrx = Transaksi::with(['details.layanan', 'customer'])
            ->where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$bulananStart, $bulananEnd])
            ->get();

        $bulananBelumLunasTrx = Transaksi::where('payment_status', 'belum_bayar')
            ->whereBetween('created_at', [$bulananStart, $bulananEnd])
            ->get();

        $bulananRekapMetode = calculate_payment_recap($bulananTrx, $bulananBelumLunasTrx);
        $bulananTotalPendapatan = $bulananRekapMetode['total_pendapatan'];
        $bulananTotalTransaksi = $bulananRekapMetode['total_transaksi'];
        $bulananTotalTunai = $bulananRekapMetode['tunai_nominal'];
        $bulananTotalQris = $bulananRekapMetode['qris_nominal'];
        $bulananTotalTransfer = $bulananRekapMetode['transfer_nominal'];
        $bulananTotalNonTunai = $bulananRekapMetode['non_tunai_nominal'];

        $daysInMonth = $bulananStart->daysInMonth;
        $rataRataHarian = $daysInMonth > 0 ? (int) ($bulananTotalPendapatan / $daysInMonth) : 0;

        // Breakdown Pelayanan Bulanan
        $bulananServiceBreakdown = $this->calculateServiceBreakdown($bulananTrx);
        $layananTerlaris = !empty($bulananServiceBreakdown) ? $bulananServiceBreakdown[0]['nama'] : '-';

        // Breakdown Harian dalam 1 Bulan
        $dailyBreakdown = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $currentDate = Carbon::create($tahunBulanan, $bulanBulanan, $d);
            $dayTrx = $bulananTrx->filter(function($t) use ($currentDate) {
                return Carbon::parse($t->updated_at)->isSameDay($currentDate);
            });

            $dayRecap = calculate_payment_recap($dayTrx);

            $dailyBreakdown[] = [
                'tanggal' => $currentDate->format('d/m/Y'),
                'hari' => $currentDate->translatedFormat('l'),
                'count' => $dayRecap['total_transaksi'],
                'tunai' => $dayRecap['tunai_nominal'],
                'qris' => $dayRecap['qris_nominal'],
                'transfer' => $dayRecap['transfer_nominal'],
                'non_tunai' => $dayRecap['non_tunai_nominal'],
                'total' => $dayRecap['total_pendapatan'],
            ];
        }

        return view('admin.pembayaran.index', compact(
            'tab',
            'transactions',
            'rekapHariIni',
            'totalPendapatanHariIni',
            'transaksiBelumLunas',
            'nominalBelumLunas',
            'qrisConfig',
            // Harian
            'tanggalHarian',
            'harianTrx',
            'harianRekapMetode',
            'harianTotalPendapatan',
            'harianTotalTransaksi',
            'harianTotalTunai',
            'harianTotalQris',
            'harianTotalTransfer',
            'harianTotalNonTunai',
            'harianServiceBreakdown',
            // Bulanan
            'bulanBulanan',
            'tahunBulanan',
            'bulananTrx',
            'bulananRekapMetode',
            'bulananTotalPendapatan',
            'bulananTotalTransaksi',
            'bulananTotalTunai',
            'bulananTotalQris',
            'bulananTotalTransfer',
            'bulananTotalNonTunai',
            'rataRataHarian',
            'layananTerlaris',
            'bulananServiceBreakdown',
            'dailyBreakdown'
        ));
    }

    /**
     * Helper: Hitung rincian pelayanan dari koleksi transaksi.
     */
    private function calculateServiceBreakdown($transaksis): array
    {
        $breakdown = [];

        foreach ($transaksis as $trx) {
            if ($trx->details && $trx->details->count() > 0) {
                foreach ($trx->details as $detail) {
                    $layananNama = $detail->layanan?->nama ?? 'Layanan Laundry';
                    $kategori = $detail->layanan?->kategori ?? 'kiloan';

                    if (!isset($breakdown[$layananNama])) {
                        $breakdown[$layananNama] = [
                            'nama' => $layananNama,
                            'kategori' => $kategori,
                            'qty' => 0,
                            'count' => 0,
                            'total' => 0,
                        ];
                    }

                    $breakdown[$layananNama]['qty'] += (float) $detail->qty;
                    $breakdown[$layananNama]['count'] += 1;
                    $breakdown[$layananNama]['total'] += (int) $detail->subtotal;
                }
            } else {
                // Legacy single service transaction
                $layananNama = ucfirst($trx->service_type ?: 'Regular');
                if (!isset($breakdown[$layananNama])) {
                    $breakdown[$layananNama] = [
                        'nama' => $layananNama,
                        'kategori' => 'kiloan',
                        'qty' => 0,
                        'count' => 0,
                        'total' => 0,
                    ];
                }

                $breakdown[$layananNama]['qty'] += (float) $trx->weight;
                $breakdown[$layananNama]['count'] += 1;
                $breakdown[$layananNama]['total'] += (int) $trx->total_price;
            }
        }

        // Sort by total revenue descending
        usort($breakdown, fn($a, $b) => $b['total'] <=> $a['total']);

        return $breakdown;
    }

    /**
     * Form Pembayaran Baru.
     */
    public function create()
    {
        $transaksiBelumLunas = Transaksi::where('payment_status', 'belum_bayar')
            ->orderBy('created_at', 'desc')
            ->get();
        $qrisConfig = get_qris_config();

        return view('admin.pembayaran.create', compact('transaksiBelumLunas', 'qrisConfig'));
    }

    /**
     * Simpan proses pembayaran transaksi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|string|exists:transaksi,transaksi_code',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:Tunai,QRIS,Transfer,Transfer BCA,Transfer Mandiri,Transfer BRI,E-Wallet',
            'tanggal_bayar' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::where('transaksi_code', $validated['transaksi_id'])->firstOrFail();

            $user = Auth::user();
            if (!$user->isAdmin() && !$user->isStaff()) {
                DB::rollBack();
                abort(403, 'Unauthorized. Hanya admin dan staff yang dapat memproses pembayaran.');
            }

            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
            }

            $jumlahBayar = $validated['jumlah_bayar'];
            $totalPrice = $transaksi->total_price;
            $kembalian = max(0, $jumlahBayar - $totalPrice);

            if ($jumlahBayar >= $totalPrice) {
                $statusPembayaran = 'Lunas';
                $paymentStatus = 'lunas';
            } elseif ($jumlahBayar > 0 && $jumlahBayar < $totalPrice) {
                $statusPembayaran = 'Cicilan';
                $paymentStatus = 'belum_bayar';
            } else {
                DB::rollBack();
                if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                    Storage::disk('public')->delete($buktiPath);
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah pembayaran harus lebih dari 0.');
            }

            // Normalisasi metode pembayaran agar kompatibel dengan kolom DB (tunai, qris, transfer)
            $normalizedMethod = classify_payment_method($validated['metode_pembayaran']);

            $updateData = [
                'payment_status' => $paymentStatus,
                'payment_method' => $normalizedMethod,
                'dibayar'        => $jumlahBayar,
                'kembalian'      => $kembalian,
                'updated_at'     => now(),
            ];

            if ($buktiPath) {
                $updateData['bukti_pembayaran'] = $buktiPath;
            }

            $transaksi->update($updateData);

            DB::commit();

            if ($paymentStatus === 'lunas') {
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($transaksi)
                    ->withProperties(['status' => 'lunas', 'jumlah' => $jumlahBayar])
                    ->log('Pembayaran lunas untuk transaksi ' . $transaksi->transaksi_code);
            }

            $msg = $paymentStatus === 'lunas'
                ? "Pembayaran LUNAS berhasil dicatat untuk transaksi {$transaksi->transaksi_code}. Kembalian: Rp " . number_format($kembalian, 0, ',', '.')
                : "Pembayaran sebagian dicatat. Sisa tagihan: Rp " . number_format($totalPrice - $jumlahBayar, 0, ',', '.');

            return redirect()->route('admin.pembayaran.index')
                ->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($buktiPath) && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            Log::error('Pembayaran store failed', [
                'error' => $e->getMessage(),
                'transaksi_id' => $validated['transaksi_id'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Cetak PDF Rekapan Pembayaran Harian.
     */
    public function exportRekapHarianPdf(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $start = Carbon::parse($tanggal)->startOfDay();
        $end = Carbon::parse($tanggal)->endOfDay();

        $transactions = Transaksi::with(['details.layanan', 'customer'])
            ->where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$start, $end])
            ->get();

        $belumBayarTrx = Transaksi::where('payment_status', 'belum_bayar')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $rekapMetode = calculate_payment_recap($transactions, $belumBayarTrx);
        $totalPendapatan = $rekapMetode['total_pendapatan'];
        $totalTransaksi = $rekapMetode['total_transaksi'];
        $totalTunai = $rekapMetode['tunai_nominal'];
        $totalQris = $rekapMetode['qris_nominal'];
        $totalTransfer = $rekapMetode['transfer_nominal'];
        $totalNonTunai = $rekapMetode['non_tunai_nominal'];

        $serviceBreakdown = $this->calculateServiceBreakdown($transactions);
        $tanggalFormatted = Carbon::parse($tanggal)->translatedFormat('l, d F Y');

        $pdf = Pdf::loadView('admin.pdf.rekap_pembayaran_harian', compact(
            'tanggalFormatted',
            'transactions',
            'rekapMetode',
            'totalPendapatan',
            'totalTransaksi',
            'totalTunai',
            'totalQris',
            'totalTransfer',
            'totalNonTunai',
            'serviceBreakdown'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("Rekap-Harian-{$tanggal}.pdf");
    }

    /**
     * Cetak PDF Rekapan Pembayaran Bulanan.
     */
    public function exportRekapBulananPdf(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $end = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $transactions = Transaksi::with(['details.layanan', 'customer'])
            ->where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$start, $end])
            ->get();

        $belumBayarTrx = Transaksi::where('payment_status', 'belum_bayar')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $rekapMetode = calculate_payment_recap($transactions, $belumBayarTrx);
        $totalPendapatan = $rekapMetode['total_pendapatan'];
        $totalTransaksi = $rekapMetode['total_transaksi'];
        $totalTunai = $rekapMetode['tunai_nominal'];
        $totalQris = $rekapMetode['qris_nominal'];
        $totalTransfer = $rekapMetode['transfer_nominal'];
        $totalNonTunai = $rekapMetode['non_tunai_nominal'];

        $daysInMonth = $start->daysInMonth;
        $rataRataHarian = $daysInMonth > 0 ? (int) ($totalPendapatan / $daysInMonth) : 0;

        $serviceBreakdown = $this->calculateServiceBreakdown($transactions);
        $layananTerlaris = !empty($serviceBreakdown) ? $serviceBreakdown[0]['nama'] : '-';

        $dailyBreakdown = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $currentDate = Carbon::create($tahun, $bulan, $d);
            $dayTrx = $transactions->filter(fn($t) => Carbon::parse($t->updated_at)->isSameDay($currentDate));
            $dayRecap = calculate_payment_recap($dayTrx);

            $dailyBreakdown[] = [
                'tanggal' => $currentDate->format('d/m/Y'),
                'hari' => $currentDate->translatedFormat('l'),
                'count' => $dayRecap['total_transaksi'],
                'tunai' => $dayRecap['tunai_nominal'],
                'qris' => $dayRecap['qris_nominal'],
                'transfer' => $dayRecap['transfer_nominal'],
                'non_tunai' => $dayRecap['non_tunai_nominal'],
                'total' => $dayRecap['total_pendapatan'],
            ];
        }

        $periodeLabel = Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.pdf.rekap_pembayaran_bulanan', compact(
            'periodeLabel',
            'rekapMetode',
            'totalPendapatan',
            'totalTransaksi',
            'totalTunai',
            'totalQris',
            'totalTransfer',
            'totalNonTunai',
            'rataRataHarian',
            'layananTerlaris',
            'serviceBreakdown',
            'dailyBreakdown'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("Rekap-Bulanan-{$tahun}-" . sprintf('%02d', $bulan) . ".pdf");
    }
}
