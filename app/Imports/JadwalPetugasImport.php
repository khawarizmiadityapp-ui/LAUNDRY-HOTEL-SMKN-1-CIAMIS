<?php

namespace App\Imports;

use App\Models\JadwalPetugas;
use App\Models\Petugas;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class JadwalPetugasImport implements ToCollection, SkipsEmptyRows
{
    protected int $importedCount = 0;
    protected array $errors = [];
    protected ?string $firstImportedDate = null;

    public function collection(Collection $rows)
    {
        $headerMap = null;

        foreach ($rows as $rowIndex => $row) {
            $rowNum = $rowIndex + 1;
            $rowArray = $row->toArray();

            // 1. Cari baris header jika belum ditemukan
            if ($headerMap === null) {
                $candidate = [];
                $foundTanggal = false;
                $foundNama = false;

                foreach ($rowArray as $colIdx => $cellVal) {
                    if ($cellVal === null || $cellVal === '') continue;
                    $clean = strtolower(trim((string)$cellVal));
                    
                    if (str_contains($clean, 'tang') || str_contains($clean, 'tgl') || $clean === 'date') {
                        $candidate['tanggal'] = $colIdx;
                        $foundTanggal = true;
                    } elseif (str_contains($clean, 'nama') || str_contains($clean, 'siswa') || str_contains($clean, 'petugas')) {
                        if (!str_contains($clean, 'id') && !str_contains($clean, 'nis')) {
                            $candidate['nama'] = $colIdx;
                            $foundNama = true;
                        }
                    } elseif (str_contains($clean, 'shift') || str_contains($clean, 'waktu') || str_contains($clean, 'jam')) {
                        $candidate['shift'] = $colIdx;
                    } elseif (str_contains($clean, 'id') || str_contains($clean, 'nis')) {
                        $candidate['id_nis'] = $colIdx;
                    } elseif (str_contains($clean, 'kelas') || str_contains($clean, 'keterangan') || str_contains($clean, 'ket') || str_contains($clean, 'catat') || str_contains($clean, 'note')) {
                        $candidate['keterangan'] = $colIdx;
                    }
                }

                if ($foundTanggal && $foundNama) {
                    $headerMap = $candidate;
                }
                continue; // Lanjut ke baris data setelah header
            }

            // 2. Ambil nilai per kolom sesuai posisi header yang ditemukan
            $rawTanggal = isset($headerMap['tanggal']) ? ($rowArray[$headerMap['tanggal']] ?? null) : null;
            $nama = isset($headerMap['nama']) ? trim((string)($rowArray[$headerMap['nama']] ?? '')) : '';
            $idPetugas = isset($headerMap['id_nis']) ? trim((string)($rowArray[$headerMap['id_nis']] ?? '')) : null;
            $shift = isset($headerMap['shift']) ? trim((string)($rowArray[$headerMap['shift']] ?? '')) : 'Pagi';
            $keterangan = isset($headerMap['keterangan']) ? trim((string)($rowArray[$headerMap['keterangan']] ?? '')) : null;

            // Jika seluruh baris kosong atau tidak ada nama & tanggal, lewati
            if (empty($nama) && empty($rawTanggal)) {
                continue;
            }

            // Validasi format tanggal (jika bukan tanggal valid, misal baris tabel keterangan kelas di bawah, lewati dengan aman)
            $parsedDate = $this->parseDate($rawTanggal);
            if (!$parsedDate) {
                continue;
            }

            if (empty($nama)) {
                $this->errors[] = "Baris {$rowNum}: Kolom Nama Siswa/Petugas kosong.";
                continue;
            }

            if (!$shift) {
                $shift = 'Pagi';
            }

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

            // Pastikan bukan teks biasa yang gagal diparse
            $timestamp = strtotime($str);
            if ($timestamp === false) {
                return null;
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
