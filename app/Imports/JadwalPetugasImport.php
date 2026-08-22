<?php

namespace App\Imports;

use App\Models\JadwalPetugas;
use App\Models\Petugas;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class JadwalPetugasImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected int $importedCount = 0;
    protected array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // header at row 1
            
            // Normalize row keys to lowercase / trimmed
            $normalized = [];
            foreach ($row as $key => $val) {
                $normalized[strtolower(trim((string)$key))] = is_string($val) ? trim($val) : $val;
            }

            $nama = $normalized['nama'] 
                ?? $normalized['nama_petugas'] 
                ?? $normalized['nama_siswa'] 
                ?? $normalized['petugas'] 
                ?? null;

            $rawTanggal = $normalized['tanggal'] 
                ?? $normalized['tgl'] 
                ?? $normalized['date'] 
                ?? null;

            if (empty($nama) || empty($rawTanggal)) {
                continue;
            }

            // Parse Tanggal
            $parsedDate = null;
            try {
                if (is_numeric($rawTanggal)) {
                    $parsedDate = Carbon::instance(ExcelDate::excelToDateTimeObject($rawTanggal))->format('Y-m-d');
                } else {
                    $parsedDate = Carbon::parse($rawTanggal)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNum}: Format tanggal '{$rawTanggal}' tidak valid.";
                continue;
            }

            $shift = $normalized['shift'] ?? 'Pagi';
            $keterangan = $normalized['keterangan'] ?? $normalized['catatan'] ?? null;
            $idPetugas = $normalized['id_petugas'] ?? $normalized['nis'] ?? null;

            // Ensure Petugas exists in master table or create
            $petugas = Petugas::where('nama', $nama)->first();
            if (!$petugas) {
                $nextId = ((int) Petugas::max('id')) + 1;
                $genId = $idPetugas ?: 'STF-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
                $petugas = Petugas::create([
                    'nama' => $nama,
                    'id_petugas' => $genId,
                    'role' => 'Washing',
                    'status' => 'Aktif',
                    'shift' => $shift,
                ]);
            }

            // Create or update JadwalPetugas
            JadwalPetugas::updateOrCreate(
                [
                    'tanggal' => $parsedDate,
                    'nama' => $nama,
                ],
                [
                    'id_petugas' => $petugas->id_petugas ?? $idPetugas,
                    'shift' => $shift,
                    'keterangan' => $keterangan,
                    'status' => 'terjadwal',
                ]
            );

            $this->importedCount++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
