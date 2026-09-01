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

        $totalPendapatanHariIni = Transaksi::where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$startOfDay, $endOfDay])
            ->sum('total_price');

        $transaksiBelumLunas = Transaksi::where('payment_status', 'belum_bayar')->count();

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

        $harianTotalPendapatan = $harianTrx->sum('total_price');
        $harianTotalTransaksi = $harianTrx->count();
        $harianTotalTunai = $harianTrx->whereIn('payment_method', ['tunai', 'cash'])->sum('total_price');
        $harianTotalNonTunai = $harianTotalPendapatan - $harianTotalTunai;

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

        $bulananTotalPendapatan = $bulananTrx->sum('total_price');
        $bulananTotalTransaksi = $bulananTrx->count();
        $bulananTotalTunai = $bulananTrx->whereIn('payment_method', ['tunai', 'cash'])->sum('total_price');
        $bulananTotalNonTunai = $bulananTotalPendapatan - $bulananTotalTunai;

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

            $dailyBreakdown[] = [
                'tanggal' => $currentDate->format('d/m/Y'),
                'hari' => $currentDate->translatedFormat('l'),
                'count' => $dayTrx->count(),
                'tunai' => $dayTrx->whereIn('payment_method', ['tunai', 'cash'])->sum('total_price'),
                'non_tunai' => $dayTrx->whereNotIn('payment_method', ['tunai', 'cash'])->sum('total_price'),
                'total' => $dayTrx->sum('total_price'),
            ];
        }

        return view('admin.pembayaran.index', compact(
            'tab',
            'transactions',
            'totalPendapatanHariIni',
            'transaksiBelumLunas',
            // Harian
            'tanggalHarian',
            'harianTrx',
            'harianTotalPendapatan',
            'harianTotalTransaksi',
            'harianTotalTunai',
            'harianTotalNonTunai',
            'harianServiceBreakdown',
            // Bulanan
            'bulanBulanan',
            'tahunBulanan',
            'bulananTrx',
            'bulananTotalPendapatan',
            'bulananTotalTransaksi',
            'bulananTotalTunai',
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

        return view('admin.pembayaran.create', compact('transaksiBelumLunas'));
    }

    /**
     * Simpan proses pembayaran transaksi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|string|exists:transaksi,transaksi_code',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:Tunai,QRIS,Transfer BCA,Transfer Mandiri,Transfer BRI,E-Wallet',
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
                $paymentStatus = 'cicilan';
            } else {
                DB::rollBack();
                if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                    Storage::disk('public')->delete($buktiPath);
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah pembayaran harus lebih dari 0.');
            }

            $updateData = [
                'payment_status' => $paymentStatus,
                'payment_method' => strtolower(str_replace(' ', '_', $validated['metode_pembayaran'])),
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

        $totalPendapatan = $transactions->sum('total_price');
        $totalTransaksi = $transactions->count();
        $totalTunai = $transactions->whereIn('payment_method', ['tunai', 'cash'])->sum('total_price');
        $totalNonTunai = $totalPendapatan - $totalTunai;

        $serviceBreakdown = $this->calculateServiceBreakdown($transactions);
        $tanggalFormatted = Carbon::parse($tanggal)->translatedFormat('l, d F Y');

        $pdf = Pdf::loadView('admin.pdf.rekap_pembayaran_harian', compact(
            'tanggalFormatted',
            'transactions',
            'totalPendapatan',
            'totalTransaksi',
            'totalTunai',
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

        $totalPendapatan = $transactions->sum('total_price');
        $totalTransaksi = $transactions->count();
        $totalTunai = $transactions->whereIn('payment_method', ['tunai', 'cash'])->sum('total_price');
        $totalNonTunai = $totalPendapatan - $totalTunai;

        $daysInMonth = $start->daysInMonth;
        $rataRataHarian = $daysInMonth > 0 ? (int) ($totalPendapatan / $daysInMonth) : 0;

        $serviceBreakdown = $this->calculateServiceBreakdown($transactions);
        $layananTerlaris = !empty($serviceBreakdown) ? $serviceBreakdown[0]['nama'] : '-';

        $dailyBreakdown = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $currentDate = Carbon::create($tahun, $bulan, $d);
            $dayTrx = $transactions->filter(fn($t) => Carbon::parse($t->updated_at)->isSameDay($currentDate));

            $dailyBreakdown[] = [
                'tanggal' => $currentDate->format('d/m/Y'),
                'hari' => $currentDate->translatedFormat('l'),
                'count' => $dayTrx->count(),
                'tunai' => $dayTrx->whereIn('payment_method', ['tunai', 'cash'])->sum('total_price'),
                'non_tunai' => $dayTrx->whereNotIn('payment_method', ['tunai', 'cash'])->sum('total_price'),
                'total' => $dayTrx->sum('total_price'),
            ];
        }

        $periodeLabel = Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.pdf.rekap_pembayaran_bulanan', compact(
            'periodeLabel',
            'totalPendapatan',
            'totalTransaksi',
            'totalTunai',
            'totalNonTunai',
            'rataRataHarian',
            'layananTerlaris',
            'serviceBreakdown',
            'dailyBreakdown'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("Rekap-Bulanan-{$tahun}-" . sprintf('%02d', $bulan) . ".pdf");
    }
}
