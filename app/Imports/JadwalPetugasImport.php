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
    protected ?string $firstImportedDate = null;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // header at row 1

            // Normalize row keys to lowercase / trimmed
            $normalized = [];
            foreach ($row as $key => $val) {
                $cleanKey = strtolower(trim((string)$key));
                $normalized[$cleanKey] = is_string($val) ? trim($val) : $val;
            }

            // Cari nama siswa / petugas secara fleksibel
            $nama = $this->extractField($normalized, [
                'nama_siswa_petugas',
                'nama_siswa___petugas',
                'nama_siswa',
                'nama_petugas',
                'nama',
                'petugas',
                'siswa',
                'nama_lengkap',
                'name',
            ], ['nama', 'siswa', 'petugas']);

            // Cari tanggal secara fleksibel
            $rawTanggal = $this->extractField($normalized, [
                'tanggal',
                'tgl',
                'date',
                'tanggal_piket',
                'tgl_piket',
            ], ['tang', 'tgl', 'date']);

            // Jika seluruh baris kosong atau tidak ada nama & tanggal, lewati
            if (empty($nama) && empty($rawTanggal)) {
                continue;
            }

            if (empty($nama)) {
                $this->errors[] = "Baris {$rowNum}: Kolom Nama Siswa/Petugas tidak ditemukan atau kosong.";
                continue;
            }

            if (empty($rawTanggal)) {
                $this->errors[] = "Baris {$rowNum}: Kolom Tanggal untuk '{$nama}' kosong.";
                continue;
            }

            // Parse Tanggal (mendukung Excel serial date, Y-m-d, d/m/Y, d-m-Y, dsb.)
            $parsedDate = $this->parseDate($rawTanggal);
            if (!$parsedDate) {
                $this->errors[] = "Baris {$rowNum}: Format tanggal '{$rawTanggal}' tidak valid. Gunakan format YYYY-MM-DD atau DD/MM/YYYY.";
                continue;
            }

            // Cari Shift
            $shift = $this->extractField($normalized, [
                'shift',
                'jam_kerja',
                'waktu',
            ], ['shift']) ?: 'Pagi';

            // Cari Keterangan
            $keterangan = $this->extractField($normalized, [
                'keterangan',
                'catatan',
                'ket',
                'keterangan_tambahan',
                'note',
                'notes',
            ], ['keterangan', 'catat', 'note']);

            // Cari ID / NIS
            $idPetugas = $this->extractField($normalized, [
                'id_nis',
                'id___nis',
                'id_petugas',
                'nis',
                'id',
                'nisn',
                'no_induk',
            ], ['nis']);

            // Pastikan Petugas ada di master table petugas
            $petugas = Petugas::where('nama', $nama)->first();
            if (!$petugas && $idPetugas) {
                $petugas = Petugas::where('id_petugas', $idPetugas)->first();
            }

            if (!$petugas) {
                $nextId = ((int) Petugas::max('id')) + 1;
                $genId = $idPetugas ?: 'STF-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);

                // Pastikan id_petugas unik
                $counter = 1;
                while (Petugas::where('id_petugas', $genId)->exists()) {
                    $genId = 'STF-' . str_pad((string) ($nextId + $counter), 4, '0', STR_PAD_LEFT);
                    $counter++;
                }

                $petugas = Petugas::create([
                    'nama' => $nama,
                    'id_petugas' => $genId,
                    'role' => 'Washing',
                    'status' => 'Aktif',
                    'shift' => $shift,
                ]);
            }

            // Create atau update JadwalPetugas
            JadwalPetugas::updateOrCreate(
                [
                    'tanggal' => $parsedDate,
                    'nama' => $nama,
                ],
                [
                    'id_petugas' => $petugas->id_petugas ?? $idPetugas,
                    'shift' => $shift,
                    'selected_station' => 'none',
                    'keterangan' => $keterangan,
                    'status' => 'terjadwal',
                ]
            );

            if ($this->firstImportedDate === null) {
                $this->firstImportedDate = $parsedDate;
            }

            $this->importedCount++;
        }
    }

    /**
     * Ekstraksi nilai field berdasarkan kandidat nama kolom dan keyword fallback
     */
    protected function extractField(array $normalized, array $candidates, array $keywords = []): mixed
    {
        foreach ($candidates as $cand) {
            if (isset($normalized[$cand]) && $normalized[$cand] !== '' && $normalized[$cand] !== null) {
                return $normalized[$cand];
            }
        }

        if (!empty($keywords)) {
            foreach ($normalized as $k => $v) {
                if ($v === '' || $v === null) continue;
                foreach ($keywords as $kw) {
                    if (str_contains($k, $kw)) {
                        // Hindari mapping 'id_nis' sebagai 'nama' jika ada keyword pencocokan
                        if ($kw !== 'nis' && (str_contains($k, 'id') || str_contains($k, 'nis'))) {
                            continue;
                        }
                        return $v;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Parse tanggal secara fleksibel (Excel date serial, d/m/Y, d-m-Y, Y-m-d)
     */
    protected function parseDate(mixed $raw): ?string
    {
        if (empty($raw)) {
            return null;
        }

        try {
            if ($raw instanceof \DateTimeInterface) {
                return Carbon::instance($raw)->format('Y-m-d');
            }

            if (is_numeric($raw)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float)$raw))->format('Y-m-d');
            }

            $str = trim((string)$raw);

            // Format d/m/Y atau d-m-Y
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $str, $m)) {
                return sprintf('%04d-%02d-%02d', (int)$m[3], (int)$m[2], (int)$m[1]);
            }

            // Format Y-m-d atau Y/m/d
            if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $str, $m)) {
                return sprintf('%04d-%02d-%02d', (int)$m[1], (int)$m[2], (int)$m[3]);
            }

            return Carbon::parse($str)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
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

    public function getFirstImportedDate(): ?string
    {
        return $this->firstImportedDate;
    }
}
