<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class LaporanPekerjaanPetugasExport implements WithMultipleSheets
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function sheets(): array
    {
        return [
            new RekapKinerjaPetugasSheet($this->reportData),
            new LogTugasPetugasSheet($this->reportData),
        ];
    }
}

class RekapKinerjaPetugasSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function collection()
    {
        $rekap = $this->reportData['rekapPetugas'] ?? collect();

        return collect($rekap)->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'nama' => $item['nama'],
                'id_petugas' => $item['id_petugas'],
                'shift' => $item['shift'],
                'status_kehadiran' => ucfirst($item['status_kehadiran']),
                'jam_hadir' => $item['checked_in_at'] ? Carbon::parse($item['checked_in_at'])->format('H:i') : '-',
                'washing_count' => $item['washing_count'],
                'ironing_count' => $item['ironing_count'],
                'packing_count' => $item['packing_count'],
                'kasir_count' => $item['kasir_count'],
                'total_output' => $item['total_output'],
                'total_weight' => number_format($item['total_weight'], 1) . ' kg',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Petugas / Siswa',
            'ID Petugas',
            'Shift',
            'Status Kehadiran',
            'Jam Hadir',
            'Washing (Cuci)',
            'Ironing (Setrika)',
            'Packing',
            'Kasir (POS)',
            'Total Tugas Selesai',
            'Total Berat Cucian',
        ];
    }

    public function title(): string
    {
        return 'Rekap Kinerja Petugas';
    }
}

class LogTugasPetugasSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function collection()
    {
        $logs = $this->reportData['taskLogs'] ?? collect();

        return collect($logs)->map(function ($log, $index) {
            return [
                'no' => $index + 1,
                'completed_at' => $log['completed_at'] ? Carbon::parse($log['completed_at'])->format('d/m/Y H:i') : '-',
                'petugas_name' => $log['petugas_name'] ?? 'Petugas',
                'stage' => strtoupper($log['stage']),
                'transaksi_code' => $log['transaksi_code'],
                'customer_name' => $log['customer_name'],
                'layanan' => $log['layanan'],
                'weight' => ($log['weight'] ?? 0) . ' kg',
                'status' => ucfirst($log['status']),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Waktu Selesai',
            'Nama Petugas',
            'Stasiun / Bagian',
            'Kode Transaksi',
            'Nama Pelanggan',
            'Layanan',
            'Berat',
            'Status Pesanan',
        ];
    }

    public function title(): string
    {
        return 'Log Rincian Pekerjaan';
    }
}
