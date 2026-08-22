<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalPetugasExportTemplate implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa / Petugas',
            'ID / NIS',
            'Shift',
            'Keterangan',
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
                'Piket shift pagi',
            ],
            [
                $today,
                'Siti Aminah',
                'NIS-10292',
                'Pagi',
                'Piket shift pagi',
            ],
            [
                $today,
                'Budi Santoso',
                'NIS-10293',
                'Pagi',
                'Piket shift pagi',
            ],
            [
                $tomorrow,
                'Rian Hidayat',
                'NIS-10294',
                'Siang',
                'Piket shift siang',
            ],
            [
                $tomorrow,
                'Dewi Lestari',
                'NIS-10295',
                'Siang',
                'Piket shift siang',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF2563EB'], // Primary blue
                ],
            ],
        ];
    }
}
