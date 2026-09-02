<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pekerjaan Petugas - {{ $periodeJudul }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.2cm 1.4cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9.5px; line-height: 1.4; color: #1e293b; }
        
        .header { text-align: center; border-bottom: 2.5px double #1e293b; padding-bottom: 6px; margin-bottom: 12px; }
        .header h3 { margin: 0; font-size: 10.5px; font-weight: normal; text-transform: uppercase; letter-spacing: 0.5px; }
        .header h2 { margin: 2px 0; font-size: 13.5px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .header h1 { margin: 2px 0; font-size: 14.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 2px 0; font-size: 8.5px; color: #475569; }
        
        .title-box { text-align: center; margin-bottom: 12px; }
        .title-box h2 { margin: 0; font-size: 12.5px; font-weight: bold; text-transform: uppercase; text-decoration: underline; color: #0f172a; }
        .title-box p { margin: 3px 0 0 0; font-size: 9.5px; color: #475569; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9px; }
        th, td { border: 1px solid #94a3b8; padding: 4.5px 6px; vertical-align: middle; }
        th { background-color: #f1f5f9; font-weight: bold; text-align: center; font-size: 8.5px; text-transform: uppercase; color: #334155; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .section-title { font-size: 10px; font-weight: bold; margin-top: 12px; margin-bottom: 4px; text-transform: uppercase; color: #1e40af; border-left: 3px solid #1e40af; padding-left: 6px; }

        .summary-grid { width: 100%; border-collapse: collapse; border: none; margin-bottom: 10px; }
        .summary-grid td { border: none; padding: 3px; }
        .stat-card { border: 1px solid #cbd5e1; padding: 6px 8px; background: #f8fafc; border-radius: 4px; text-align: center; }
        .stat-card span { font-size: 8px; color: #64748b; text-transform: uppercase; display: block; font-weight: 600; }
        .stat-card strong { font-size: 11.5px; color: #0f172a; margin-top: 2px; display: block; }

        .badge { display: inline-block; padding: 1.5px 5px; font-size: 7.5px; font-weight: bold; border-radius: 3px; text-transform: uppercase; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .badge-amber { background-color: #fef3c7; color: #92400e; }
        .badge-emerald { background-color: #d1fae5; color: #065f46; }
        .badge-purple { background-color: #f3e8ff; color: #6b21a8; }
        .badge-slate { background-color: #f1f5f9; color: #475569; }

        .signature-container { margin-top: 20px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; border: none; }
        .signature-table td { border: none; text-align: center; font-size: 9px; }
        .signature-space { height: 45px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h2>SMK NEGERI 1 CIAMIS</h2>
        <h1>UNIT PRODUKSI & JASA: BENING LAUNDRY HOTEL</h1>
        <p>Jl. Jenderal Sudirman No. 269 Ciamis 46211 | Telp. (0265) 771204 | Email: beninglaundry@smkn1ciamis.sch.id</p>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="title-box">
        <h2>LAPORAN KINERJA & PEKERJAAN HARIAN PETUGAS PIKET</h2>
        <p><strong>Periode:</strong> {{ $periodeJudul }} | <strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
    </div>

    {{-- STATISTIK RINGKASAN --}}
    <table class="summary-grid">
        <tr>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Total Petugas Piket</span>
                    <strong>{{ $stats['total_petugas'] }} Petugas</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Total Tugas Selesai</span>
                    <strong>{{ $stats['total_tasks'] }} Tugas</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Total Bobot Cucian</span>
                    <strong>{{ number_format($stats['total_weight'], 1) }} Kg</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Output Cuci / Setrika / Pack</span>
                    <strong>{{ $stats['washing_count'] }} / {{ $stats['ironing_count'] }} / {{ $stats['packing_count'] }}</strong>
                </div>
            </td>
        </tr>
    </table>

    {{-- 1. REKAPITULASI KINERJA PETUGAS --}}
    <div class="section-title">1. Rekapitulasi Kinerja Petugas Piket</div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 24%;" class="text-left">Nama Petugas / Siswa</th>
                <th style="width: 14%;">Shift & Kehadiran</th>
                <th style="width: 10%;">Washing</th>
                <th style="width: 10%;">Ironing</th>
                <th style="width: 10%;">Packing</th>
                <th style="width: 10%;">Kasir / POS</th>
                <th style="width: 10%;">Total Tugas</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapPetugas as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold text-left">
                        {{ $item['nama'] }}
                        @if(!empty($item['id_petugas']))
                            <div style="font-size: 7.5px; color: #64748b; font-weight: normal;">ID: {{ $item['id_petugas'] }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-slate">{{ $item['shift'] }}</span>
                        <div style="font-size: 7.5px; color: #059669; font-weight: 600; margin-top: 1px;">
                            {{ $item['checked_in_at'] ? \Carbon\Carbon::parse($item['checked_in_at'])->format('H:i') : ucfirst($item['status_kehadiran']) }}
                        </div>
                    </td>
                    <td class="text-center font-bold" style="color: #1e40af;">{{ $item['washing_count'] }}</td>
                    <td class="text-center font-bold" style="color: #b45309;">{{ $item['ironing_count'] }}</td>
                    <td class="text-center font-bold" style="color: #6b21a8;">{{ $item['packing_count'] }}</td>
                    <td class="text-center font-bold" style="color: #047857;">{{ $item['kasir_count'] }}</td>
                    <td class="text-center font-bold bg-slate-50" style="background-color: #f8fafc;">{{ $item['total_output'] }}</td>
                    <td class="text-center">
                        @if($item['total_output'] >= 5)
                            <span class="badge badge-emerald">Sangat Aktif</span>
                        @elseif($item['total_output'] > 0)
                            <span class="badge badge-blue">Aktif</span>
                        @else
                            <span class="badge badge-slate">Standby</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 10px; color: #64748b;">
                        Tidak ada catatan aktivitas petugas pada tanggal/periode yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 2. LOG RINCIAN TUGAS HARIAN --}}
    <div class="section-title">2. Log Rincian Transaksi & Tugas yang Dikerjakan</div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Waktu Selesai</th>
                <th style="width: 20%;" class="text-left">Nama Petugas</th>
                <th style="width: 12%;">Stasiun</th>
                <th style="width: 14%;">No. Transaksi</th>
                <th style="width: 18%;" class="text-left">Nama Pelanggan</th>
                <th style="width: 10%;">Berat / Qty</th>
                <th style="width: 10%;">Status Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taskLogs as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-mono">
                        {{ $log['completed_at'] ? \Carbon\Carbon::parse($log['completed_at'])->format('d/m H:i') : '-' }}
                    </td>
                    <td class="font-bold text-left">{{ $log['petugas_name'] ?? 'Petugas' }}</td>
                    <td class="text-center">
                        @if($log['stage'] === 'washing')
                            <span class="badge badge-blue">Washing</span>
                        @elseif($log['stage'] === 'ironing')
                            <span class="badge badge-amber">Ironing</span>
                        @elseif($log['stage'] === 'packing')
                            <span class="badge badge-purple">Packing</span>
                        @else
                            <span class="badge badge-slate">{{ ucfirst($log['stage']) }}</span>
                        @endif
                    </td>
                    <td class="text-center font-mono font-bold">{{ $log['transaksi_code'] }}</td>
                    <td class="text-left">{{ $log['customer_name'] }}</td>
                    <td class="text-center">{{ $log['weight'] ? $log['weight'] . ' kg' : '-' }}</td>
                    <td class="text-center font-bold" style="color: #059669;">{{ ucfirst($log['status']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 10px; color: #64748b;">
                        Belum ada data tugas yang diselesaikan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    Ciamis, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Koordinator Piket / Penanggung Jawab</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">( ..................................................... )</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 2px;">NIP/NIS. ..............................................</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 15px;">
                    Mengetahui,<br>
                    <strong>Kepala Unit Produksi & Jasa Bening Laundry Hotel SMKN 1 Ciamis</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">( Dra. Hj. Heni Hendrayani, M.Pd. )</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 2px;">NIP. 19680514 199403 2 005</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
