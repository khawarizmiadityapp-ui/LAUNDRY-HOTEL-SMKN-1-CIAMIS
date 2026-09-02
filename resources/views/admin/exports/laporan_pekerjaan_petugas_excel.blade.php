<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center; color: #1e3a8a;">
                UNIT PRODUKSI & JASA: BENING LAUNDRY HOTEL SMKN 1 CIAMIS
            </td>
        </tr>
        <tr>
            <td colspan="8" style="font-size: 12pt; font-weight: bold; text-align: center; color: #0f172a;">
                LAPORAN PEKERJAAN & KINERJA HARIAN PETUGAS PIKET
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; font-size: 10pt; color: #64748b;">
                Periode: {{ $periodeJudul }} | Dicetak: {{ now()->translatedFormat('d F Y H:i') }} WIB
            </td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Total Petugas Piket</th>
            <td colspan="2" style="font-weight: bold;">{{ $stats['total_petugas'] }} Petugas</td>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Total Tugas Selesai</th>
            <td colspan="2" style="font-weight: bold;">{{ $stats['total_tasks'] }} Tugas</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Total Bobot Cucian</th>
            <td colspan="2" style="font-weight: bold;">{{ number_format($stats['total_weight'], 1) }} Kg</td>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Output (Cuci/Setrika/Pack/Kasir)</th>
            <td colspan="2" style="font-weight: bold;">{{ $stats['washing_count'] }} / {{ $stats['ironing_count'] }} / {{ $stats['packing_count'] }} / {{ $stats['kasir_count'] }}</td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="font-weight: bold; font-size: 11pt; background-color: #1e40af; color: #ffffff; text-align: left;">
                1. REKAPITULASI KINERJA PETUGAS
            </td>
        </tr>
        <tr style="background-color: #e2e8f0; font-weight: bold; text-align: center;">
            <th width="8">No</th>
            <th width="30">Nama Petugas / Siswa</th>
            <th width="20">Shift & Presensi</th>
            <th width="15">Washing</th>
            <th width="15">Ironing</th>
            <th width="15">Packing</th>
            <th width="15">Kasir</th>
            <th width="18">Total Output</th>
        </tr>
        @forelse($rekapPetugas as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ $item['nama'] }}</td>
                <td style="text-align: center;">{{ $item['shift'] }} ({{ ucfirst($item['status_kehadiran']) }})</td>
                <td style="text-align: center;">{{ $item['washing_count'] }}</td>
                <td style="text-align: center;">{{ $item['ironing_count'] }}</td>
                <td style="text-align: center;">{{ $item['packing_count'] }}</td>
                <td style="text-align: center;">{{ $item['kasir_count'] }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #f8fafc;">{{ $item['total_output'] }} item</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #94a3b8;">Tidak ada data aktivitas petugas pada periode ini.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="font-weight: bold; font-size: 11pt; background-color: #047857; color: #ffffff; text-align: left;">
                2. RINCIAN LOG PEKERJAAN & TUGAS HARIAN
            </td>
        </tr>
        <tr style="background-color: #e2e8f0; font-weight: bold; text-align: center;">
            <th width="8">No</th>
            <th width="18">Waktu Selesai</th>
            <th width="25">Nama Petugas</th>
            <th width="16">Stasiun</th>
            <th width="20">Kode Transaksi</th>
            <th width="25">Nama Pelanggan</th>
            <th width="20">Berat / Layanan</th>
            <th width="16">Status</th>
        </tr>
        @forelse($taskLogs as $index => $log)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $log['completed_at'] ? \Carbon\Carbon::parse($log['completed_at'])->translatedFormat('d/m/Y H:i') : '-' }}</td>
                <td style="font-weight: bold;">{{ $log['petugas_name'] ?? 'Petugas' }}</td>
                <td style="text-align: center;">{{ strtoupper($log['stage']) }}</td>
                <td style="text-align: center;">{{ $log['transaksi_code'] }}</td>
                <td>{{ $log['customer_name'] }}</td>
                <td style="text-align: center;">{{ $log['weight'] ? $log['weight'] . ' kg' : '-' }} ({{ $log['layanan'] }})</td>
                <td style="text-align: center;">{{ ucfirst($log['status']) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #94a3b8;">Belum ada catatan log pekerjaan pada periode ini.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>
