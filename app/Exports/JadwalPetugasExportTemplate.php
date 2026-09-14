<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalPetugasExportTemplate implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithCustomStartCell, WithEvents
{
    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa / Petugas',
            'ID / NIS',
            'Shift',
            'Kelas / Keterangan',
        ];
    }

    public function array(): array
    {
        $today = Carbon::today()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        return [
            [
                $today,
                'Ahmad Fauzi',
                'NIS-10291',
                'Pagi',
                'HOTEL-1',
            ],
            [
                $today,
                'Siti Aminah',
                'NIS-10292',
                'Pagi',
                'HOTEL-1',
            ],
            [
                $today,
                'Budi Santoso',
                'NIS-10293',
                'Pagi',
                'HOTEL-2',
            ],
            [
                $tomorrow,
                'Rian Hidayat',
                'NIS-10294',
                'Siang',
                'HOTEL-1',
            ],
            [
                $tomorrow,
                'Dewi Lestari',
                'NIS-10295',
                'Siang',
                'HOTEL-2',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF2563EB'], // Primary blue
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Kop Judul
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'JADWAL PIKET HARIAN MURID');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'PROGRAM KEAHLIAN PERHOTELAN');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:E3');
                $sheet->setCellValue('A3', 'TAHUN PELAJARAN 2026/2027');
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Border untuk data jadwal
                $sheet->getStyle('A5:E10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
