<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Kas Umum (BKU) - Laundry Hotel SMKN 1 Ciamis</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1a202c;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px double #1a202c;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0;
            font-size: 11px;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a8a;
        }
        .header h1 {
            margin: 2px 0;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 9px;
            color: #4a5568;
        }
        .title-box {
            text-align: center;
            margin-bottom: 15px;
        }
        .title-box h2 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .title-box p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #4a5568;
        }
        table.bku-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 9.5px;
        }
        table.bku-table th, table.bku-table td {
            border: 1px solid #718096;
            padding: 5px 6px;
            vertical-align: middle;
        }
        table.bku-table th {
            background-color: #edf2f7;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier', monospace; }
        .font-bold { font-weight: bold; }
        
        .saldo-awal-row {
            background-color: #f7fafc;
            font-style: italic;
            font-weight: bold;
        }
        .total-row {
            background-color: #edf2f7;
            font-weight: bold;
        }

        .summary-box {
            margin-top: 15px;
            float: left;
            width: 50%;
            border: 1px solid #cbd5e0;
            padding: 8px 12px;
            background-color: #f8fafc;
            border-radius: 4px;
        }
        .summary-box table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }
        .summary-box td {
            padding: 2px 0;
        }

        .signature-container {
            margin-top: 20px;
            width: 100%;
            clear: both;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-top: 15px;
        }
        .signature-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            text-align: center;
            font-size: 9.5px;
        }
        .signature-space {
            height: 55px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI --}}
    <div class="header">
        <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h2>SMK NEGERI 1 CIAMIS</h2>
        <h1>UNIT PRODUKSI & JASA: BENING LAUNDRY HOTEL</h1>
        <p>Jl. Jenderal Sudirman No. 269 Ciamis 46211 | Telp. (0265) 771204 | Email: beninglaundry@smkn1ciamis.sch.id</p>
    </div>

    {{-- JUDUL DOKUMEN --}}
    <div class="title-box">
        <h2>BUKU KAS UMUM (BKU)</h2>
        <p><strong>Periode:</strong> {{ $periodeLabel }}</p>
    </div>

    {{-- TABEL BKU --}}
    <table class="bku-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 15%;">No. Bukti</th>
                <th style="width: 29%;">Uraian Transaksi</th>
                <th style="width: 13%;">Penerimaan (Debet)</th>
                <th style="width: 13%;">Pengeluaran (Kredit)</th>
                <th style="width: 14%;">Saldo Kas</th>
            </tr>
            <tr style="background-color: #f7fafc; font-size: 8px; color: #718096;">
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
            </tr>
        </thead>
        <tbody>
            {{-- Saldo Awal --}}
            <tr class="saldo-awal-row">
                <td class="text-center">-</td>
                <td class="text-center">{{ $tanggalAwalFormatted }}</td>
                <td class="text-center font-mono">-</td>
                <td><strong>Saldo Kas Awal Periode</strong></td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-bold">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>

            {{-- Baris Transaksi --}}
            @php $no = 1; @endphp
            @forelse($ledgerItems as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $item['tanggal'] }}</td>
                <td class="text-center font-mono" style="font-size: 8.5px;">{{ $item['no_bukti'] }}</td>
                <td>
                    {{ $item['uraian'] }}
                    @if(!empty($item['sub_info']))
                        <div style="font-size: 8px; color: #64748b;">{{ $item['sub_info'] }}</div>
                    @endif
                </td>
                <td class="text-right font-mono">
                    {{ $item['debet'] > 0 ? 'Rp ' . number_format($item['debet'], 0, ',', '.') : '-' }}
                </td>
                <td class="text-right font-mono">
                    {{ $item['kredit'] > 0 ? 'Rp ' . number_format($item['kredit'], 0, ',', '.') : '-' }}
                </td>
                <td class="text-right font-mono font-bold">
                    Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px; color: #718096;">
                    Tidak ada mutasi kas pada periode ini.
                </td>
            </tr>
            @endforelse

            {{-- Total Baris --}}
            <tr class="total-row">
                <td colspan="4" class="text-center font-bold">JUMLAH MUTASI PERIODE INI</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- REKAPITULASI BOX --}}
    <div class="clearfix" style="margin-top: 15px;">
        <div class="summary-box">
            <strong style="text-decoration: underline; font-size: 10px;">REKAPITULASI BUKU KAS:</strong>
            <table style="margin-top: 4px;">
                <tr>
                    <td style="width: 55%;">Saldo Awal Periode</td>
                    <td style="width: 5%;">: Rp</td>
                    <td class="text-right font-mono" style="width: 40%;">{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Penerimaan (Debet)</td>
                    <td>: Rp</td>
                    <td class="text-right font-mono">{{ number_format($totalDebet, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Pengeluaran (Kredit)</td>
                    <td>: Rp</td>
                    <td class="text-right font-mono">{{ number_format($totalKredit, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-top: 1px solid #718096; font-weight: bold;">
                    <td>Saldo Kas Akhir</td>
                    <td>: Rp</td>
                    <td class="text-right font-mono">{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- TANDA TANGAN RESMI --}}
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 45%;">
                    Mengetahui,<br>
                    <strong>Kepala Program Keahlian Perhotelan / Unit Usaha</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">Dra. Hj. Nunung Rohanah, M.Pd.</div>
                    <div>NIP. 19680512 199303 2 004</div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    Ciamis, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <strong>Bendahara / Pengelola Kas Laundry</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ auth()->user()->name ?? 'Pengelola Kas' }}</div>
                    <div>Unit Laundry Hotel SMKN 1 Ciamis</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
