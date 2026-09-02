<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaundryTask;
use App\Models\JadwalPetugas;
use App\Models\Petugas;
use App\Models\Transaksi;
use App\Exports\LaporanPekerjaanPetugasExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPekerjaanPetugasController extends Controller
{
    /**
     * Halaman Utama Laporan Pekerjaan Harian Petugas
     */
    public function index(Request $request)
    {
        $reportData = $this->buildReportData($request);

        return view('admin.laporan_petugas.index', $reportData);
    }

    /**
     * Export Laporan ke Format PDF Resmi
     */
    public function exportPdf(Request $request)
    {
        $reportData = $this->buildReportData($request);

        $pdf = Pdf::loadView('admin.pdf.laporan_pekerjaan_petugas', $reportData)
            ->setPaper('a4', 'portrait');

        $fileName = 'Laporan-Pekerjaan-Petugas-' . ($request->filter_mode === 'rentang' ? 'Periode' : $reportData['selectedDate']) . '-' . now()->format('His') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export Laporan ke Format Excel / Spreadsheet
     */
    public function exportExcel(Request $request)
    {
        $reportData = $this->buildReportData($request);

        $fileName = 'Laporan-Pekerjaan-Petugas-' . ($request->filter_mode === 'rentang' ? 'Periode' : $reportData['selectedDate']) . '-' . now()->format('His') . '.xlsx';

        return Excel::download(new LaporanPekerjaanPetugasExport($reportData), $fileName);
    }

    /**
     * Helper terpusat untuk membangun agregasi data laporan pekerjaan petugas
     */
    protected function buildReportData(Request $request): array
    {
        $filterMode = $request->get('filter_mode', 'harian');
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $selectedPetugas = $request->get('petugas', 'all');
        $selectedStation = $request->get('station', 'all');
        $selectedShift = $request->get('shift', 'all');

        // Tentukan rentang waktu
        if ($filterMode === 'rentang' && $dari && $sampai) {
            $start = Carbon::parse($dari)->startOfDay();
            $end = Carbon::parse($sampai)->endOfDay();
            $periodeJudul = $start->translatedFormat('d F Y') . ' s.d. ' . $end->translatedFormat('d F Y');
            $tanggalFormatted = $periodeJudul;
        } else {
            $targetDate = $selectedDate ? Carbon::parse($selectedDate) : Carbon::today();
            $start = $targetDate->copy()->startOfDay();
            $end = $targetDate->copy()->endOfDay();
            $periodeJudul = $targetDate->translatedFormat('l, d F Y');
            $tanggalFormatted = $targetDate->translatedFormat('d F Y');
            $selectedDate = $targetDate->format('Y-m-d');
        }

        // 1. Ambil Tasks yang selesai dalam rentang waktu
        $tasksQuery = LaundryTask::with(['transaksi.customer', 'transaksi.details.layanan', 'petugas'])
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$start, $end]);

        if ($selectedStation && $selectedStation !== 'all' && $selectedStation !== 'kasir') {
            $tasksQuery->where('stage', $selectedStation);
        }

        if ($selectedPetugas && $selectedPetugas !== 'all') {
            $tasksQuery->where('petugas_name', $selectedPetugas);
        }

        $completedTasks = $tasksQuery->orderBy('completed_at', 'desc')->get();

        // 2. Ambil Transaksi yang dibuat / dilayani kasir dalam rentang waktu
        $transaksiKasirQuery = Transaksi::with(['customer', 'details.layanan'])
            ->whereBetween('created_at', [$start, $end]);

        if ($selectedPetugas && $selectedPetugas !== 'all') {
            $transaksiKasirQuery->where('kasir_name', $selectedPetugas);
        }

        $transaksiKasir = $transaksiKasirQuery->orderBy('created_at', 'desc')->get();

        // 3. Ambil Jadwal & Presensi Petugas
        $jadwalQuery = JadwalPetugas::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]);

        if ($selectedShift && $selectedShift !== 'all') {
            $jadwalQuery->where('shift', $selectedShift);
        }

        if ($selectedPetugas && $selectedPetugas !== 'all') {
            $jadwalQuery->where('nama', $selectedPetugas);
        }

        $jadwalList = $jadwalQuery->orderBy('shift')->orderBy('nama')->get();
        $masterPetugas = Petugas::orderBy('nama')->get();

        // 4. Hitung Statistik Keseluruhan (Overall KPIs)
        $washingCount = $completedTasks->where('stage', 'washing')->count();
        $ironingCount = $completedTasks->where('stage', 'ironing')->count();
        $packingCount = $completedTasks->where('stage', 'packing')->count();
        $kasirCount = $transaksiKasir->count();
        $totalTasks = $washingCount + $ironingCount + $packingCount + $kasirCount;

        // Hitung total berat yang diproses
        $totalWeightWashing = $completedTasks->where('stage', 'washing')->sum(fn($t) => (float) ($t->transaksi->weight ?? 0));
        $totalWeightIroning = $completedTasks->where('stage', 'ironing')->sum(fn($t) => (float) ($t->transaksi->weight ?? 0));
        $totalWeightPacking = $completedTasks->where('stage', 'packing')->sum(fn($t) => (float) ($t->transaksi->weight ?? 0));
        $totalWeightTrx = $transaksiKasir->sum(fn($trx) => (float) ($trx->weight ?? 0));
        $totalWeight = max($totalWeightWashing, $totalWeightIroning, $totalWeightPacking, $totalWeightTrx);

        // 5. Matriks Produktivitas Tiap Petugas
        // Himpun semua nama unik petugas yang ada di jadwal, task, kasir, atau master
        $allNames = collect();

        foreach ($jadwalList as $j) {
            $allNames->push($j->nama);
        }
        foreach ($completedTasks as $t) {
            if (!empty($t->petugas_name)) {
                $allNames->push($t->petugas_name);
            }
        }
        foreach ($transaksiKasir as $k) {
            if (!empty($k->kasir_name)) {
                $allNames->push($k->kasir_name);
            }
        }
        if ($allNames->isEmpty()) {
            foreach ($masterPetugas as $p) {
                $allNames->push($p->nama);
            }
        }

        $allNames = $allNames->filter()->unique()->values();

        if ($selectedPetugas && $selectedPetugas !== 'all') {
            $allNames = $allNames->filter(fn($n) => $n === $selectedPetugas)->values();
        }

        $rekapPetugas = $allNames->map(function ($nama) use ($jadwalList, $completedTasks, $transaksiKasir, $masterPetugas) {
            $jadwal = $jadwalList->firstWhere('nama', $nama);
            $master = $masterPetugas->firstWhere('nama', $nama);

            $petugasTasks = $completedTasks->where('petugas_name', $nama);
            $petugasKasirTrx = $transaksiKasir->where('kasir_name', $nama);

            $washingTotal = $petugasTasks->where('stage', 'washing')->count();
            $ironingTotal = $petugasTasks->where('stage', 'ironing')->count();
            $packingTotal = $petugasTasks->where('stage', 'packing')->count();
            $kasirTotal = $petugasKasirTrx->count();
            $totalOutput = $washingTotal + $ironingTotal + $packingTotal + $kasirTotal;

            $totalWeightPetugas = $petugasTasks->sum(fn($t) => (float) ($t->transaksi->weight ?? 0)) + $petugasKasirTrx->sum(fn($trx) => (float) ($trx->weight ?? 0));

            // Ambil daftar detail task untuk modal popup
            $detailList = $petugasTasks->map(function ($task) {
                return [
                    'type' => 'task',
                    'stage' => $task->stage,
                    'transaksi_code' => $task->transaksi->transaksi_code ?? '-',
                    'customer_name' => $task->transaksi->customer_name ?? '-',
                    'weight' => $task->transaksi->weight ?? 0,
                    'layanan' => $task->transaksi->details->pluck('layanan.nama')->filter()->join(', ') ?: ($task->transaksi->service_type ?? 'Laundry'),
                    'completed_at' => $task->completed_at ? $task->completed_at->format('H:i') : '-',
                    'status' => $task->transaksi->status ?? 'selesai',
                ];
            })->concat($petugasKasirTrx->map(function ($trx) {
                return [
                    'type' => 'kasir',
                    'stage' => 'kasir',
                    'transaksi_code' => $trx->transaksi_code ?? '-',
                    'customer_name' => $trx->customer_name ?? '-',
                    'weight' => $trx->weight ?? 0,
                    'layanan' => $trx->details->pluck('layanan.nama')->filter()->join(', ') ?: ($trx->service_type ?? 'Order POS'),
                    'completed_at' => $trx->created_at ? $trx->created_at->format('H:i') : '-',
                    'status' => $trx->status ?? 'diterima',
                ];
            }))->sortByDesc('completed_at')->values();

            return [
                'nama' => $nama,
                'id_petugas' => $jadwal?->id_petugas ?? $master?->id_petugas ?? '-',
                'shift' => $jadwal?->shift ?? $master?->shift ?? 'Pagi',
                'status_kehadiran' => $jadwal?->status ?? ($totalOutput > 0 ? 'hadir' : 'terjadwal'),
                'checked_in_at' => $jadwal?->checked_in_at,
                'selected_station' => $jadwal?->selected_station ?? 'none',
                'washing_count' => $washingTotal,
                'ironing_count' => $ironingTotal,
                'packing_count' => $packingTotal,
                'kasir_count' => $kasirTotal,
                'total_output' => $totalOutput,
                'total_weight' => $totalWeightPetugas,
                'details' => $detailList,
            ];
        })->sortByDesc('total_output')->values();

        // 6. Log Rincian Pekerjaan Kronologis (Flat Activity Log)
        $taskLogs = $completedTasks->map(function ($task) {
            $layananNama = $task->transaksi->details->pluck('layanan.nama')->filter()->join(', ');
            if (empty($layananNama)) {
                $layananNama = $task->transaksi->service_type ? ucfirst($task->transaksi->service_type) : 'Laundry Kiloan';
            }

            return [
                'id' => $task->id,
                'completed_at' => $task->completed_at,
                'petugas_name' => $task->petugas_name ?: ($task->petugas->name ?? 'Petugas'),
                'stage' => $task->stage,
                'transaksi_id' => $task->transaksi_id,
                'transaksi_code' => $task->transaksi->transaksi_code ?? '-',
                'customer_name' => $task->transaksi->customer_name ?? 'Pelanggan',
                'weight' => $task->transaksi->weight ?? 0,
                'layanan' => $layananNama,
                'total_price' => $task->transaksi->total_price ?? 0,
                'status' => $task->transaksi->status ?? 'selesai',
                'notes' => $task->notes,
            ];
        });

        // Tambahkan order kasir ke log jika stasiun kasir / all dipilih
        if ($selectedStation === 'all' || $selectedStation === 'kasir') {
            $kasirLogs = $transaksiKasir->map(function ($trx) {
                $layananNama = $trx->details->pluck('layanan.nama')->filter()->join(', ');
                if (empty($layananNama)) {
                    $layananNama = $trx->service_type ? ucfirst($trx->service_type) : 'Order Kasir';
                }

                return [
                    'id' => 'pos-' . $trx->id,
                    'completed_at' => $trx->created_at,
                    'petugas_name' => $trx->kasir_name ?: 'Kasir POS',
                    'stage' => 'kasir',
                    'transaksi_id' => $trx->id,
                    'transaksi_code' => $trx->transaksi_code ?? '-',
                    'customer_name' => $trx->customer_name ?? 'Pelanggan',
                    'weight' => $trx->weight ?? 0,
                    'layanan' => $layananNama,
                    'total_price' => $trx->total_price ?? 0,
                    'status' => $trx->status ?? 'diterima',
                    'notes' => 'Penerimaan Order Baru (Kasir)',
                ];
            });

            $taskLogs = $taskLogs->concat($kasirLogs);
        }

        $taskLogs = $taskLogs->sortByDesc('completed_at')->values();

        // 7. Daftar opsi untuk dropdown filter
        $petugasDropdown = $masterPetugas->pluck('nama')->concat($allNames)->filter()->unique()->sort()->values();

        $stats = [
            'total_petugas' => $rekapPetugas->count(),
            'petugas_hadir' => $rekapPetugas->where('status_kehadiran', 'hadir')->count(),
            'total_tasks' => $totalTasks,
            'total_weight' => $totalWeight,
            'washing_count' => $washingCount,
            'ironing_count' => $ironingCount,
            'packing_count' => $packingCount,
            'kasir_count' => $kasirCount,
            'avg_tasks_per_petugas' => $rekapPetugas->count() > 0 ? round($totalTasks / $rekapPetugas->count(), 1) : 0,
        ];

        return compact(
            'filterMode',
            'selectedDate',
            'dari',
            'sampai',
            'selectedPetugas',
            'selectedStation',
            'selectedShift',
            'periodeJudul',
            'tanggalFormatted',
            'stats',
            'rekapPetugas',
            'taskLogs',
            'petugasDropdown'
        );
    }
}
