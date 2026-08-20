<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapan Pembayaran Bulanan - {{ $periodeLabel }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; line-height: 1.4; color: #1a202c; }
        .header { text-align: center; border-bottom: 2.5px double #1a202c; padding-bottom: 8px; margin-bottom: 15px; }
        .header h3 { margin: 0; font-size: 11px; font-weight: normal; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 14px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .header h1 { margin: 2px 0; font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 9px; color: #4a5568; }
        .title-box { text-align: center; margin-bottom: 15px; }
        .title-box h2 { margin: 0; font-size: 13px; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p { margin: 3px 0 0 0; font-size: 10px; color: #4a5568; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9px; }
        th, td { border: 1px solid #718096; padding: 4px 6px; vertical-align: middle; }
        th { background-color: #edf2f7; font-weight: bold; text-align: center; font-size: 8.5px; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier', monospace; }
        .font-bold { font-weight: bold; }

        .section-title { font-size: 11px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; color: #1e3a8a; }

        .summary-grid { width: 100%; border-collapse: collapse; border: none; margin-bottom: 12px; }
        .summary-grid td { border: none; padding: 3px; }
        .stat-card { border: 1px solid #cbd5e0; padding: 7px; background: #f8fafc; border-radius: 4px; }
        .stat-card span { font-size: 8px; color: #64748b; text-transform: uppercase; display: block; }
        .stat-card strong { font-size: 11px; color: #0f172a; }

        .signature-container { margin-top: 20px; width: 100%; }
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

    {{-- JUDUL --}}
    <div class="title-box">
        <h2>REKAPAN PEMBAYARAN & PELAYANAN BULANAN</h2>
        <p><strong>Periode:</strong> {{ $periodeLabel }}</p>
    </div>

    {{-- STATISTIK KARTU --}}
    <table class="summary-grid">
        <tr>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Total Omzet Bulan Ini</span>
                    <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Total Transaksi</span>
                    <strong>{{ $totalTransaksi }} Transaksi</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Rata-rata Omzet / Hari</span>
                    <strong>Rp {{ number_format($rataRataHarian, 0, ',', '.') }}</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Layanan Terlaris</span>
                    <strong>{{ $layananTerlaris }}</strong>
                </div>
            </td>
        </tr>
    </table>

    {{-- 1. RINCIAN PERFORMA LAYANAN BULAN INI --}}
    <div class="section-title">1. Ringkasan Kinerja Jenis Layanan</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Layanan Laundry</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Total Volume (Kg/Pcs)</th>
                <th style="width: 15%;">Total Order</th>
                <th style="width: 15%;">Total Omzet (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $noSrv = 1; @endphp
            @forelse($serviceBreakdown as $srv)
            <tr>
                <td class="text-center">{{ $noSrv++ }}</td>
                <td class="font-bold">{{ $srv['nama'] }}</td>
                <td class="text-center uppercase">{{ $srv['kategori'] }}</td>
                <td class="text-center font-mono">{{ $srv['qty'] }} {{ $srv['kategori'] == 'kiloan' ? 'kg' : 'pcs' }}</td>
                <td class="text-center">{{ $srv['count'] }}x</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($srv['total'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #718096;">Tidak ada transaksi layanan pada bulan ini.</td>
            </tr>
            @endforelse
            <tr style="background-color: #edf2f7; font-weight: bold;">
                <td colspan="5" class="text-center">TOTAL OMZET BULANAN</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- 2. REKAPITULASI OMZET HARIAN --}}
    <div class="section-title" style="margin-top: 15px;">2. Rekapan Penerimaan Kas Per Hari (Harian 1 - {{ count($dailyBreakdown) }})</div>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 12%;">Hari</th>
                <th style="width: 12%;">Jumlah Order</th>
                <th style="width: 18%;">Penerimaan Tunai</th>
                <th style="width: 18%;">Penerimaan Non-Tunai</th>
                <th style="width: 28%;">Total Omzet Harian (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyBreakdown as $day)
            @if($day['total'] > 0)
            <tr>
                <td class="text-center font-mono">{{ $day['tanggal'] }}</td>
                <td class="text-center">{{ $day['hari'] }}</td>
                <td class="text-center">{{ $day['count'] }}</td>
                <td class="text-right font-mono">{{ $day['tunai'] > 0 ? 'Rp ' . number_format($day['tunai'], 0, ',', '.') : '-' }}</td>
                <td class="text-right font-mono">{{ $day['non_tunai'] > 0 ? 'Rp ' . number_format($day['non_tunai'], 0, ',', '.') : '-' }}</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($day['total'], 0, ',', '.') }}</td>
            </tr>
            @endif
            @endforeach
            <tr style="background-color: #edf2f7; font-weight: bold;">
                <td colspan="3" class="text-center">TOTAL REKAP BULANAN</td>
                <td class="text-right font-mono">Rp {{ number_format($totalTunai, 0, ',', '.') }}</td>
                <td class="text-right font-mono">Rp {{ number_format($totalNonTunai, 0, ',', '.') }}</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 45%;">
                    Mengetahui,<br>
                    <strong>Kepala Program Keahlian / Penanggung Jawab Unit</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">Dra. Hj. Nunung Rohanah, M.Pd.</div>
                    <div>NIP. 19680512 199303 2 004</div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    Ciamis, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Pengelola Keuangan & Kasir Laundry</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ auth()->user()->name ?? 'Pengelola Keuangan' }}</div>
                    <div>Unit Laundry Hotel SMKN 1 Ciamis</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
