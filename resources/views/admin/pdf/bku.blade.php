<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BKU TEFA HTL - {{ $periodeJudul }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }
        .header-title {
            text-align: center;
            margin-bottom: 22px;
        }
        .header-title h1, 
        .header-title h2, 
        .header-title h3 {
            margin: 0;
            padding: 1px 0;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        table.bku-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 9.5pt;
        }
        table.bku-table th, 
        table.bku-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }
        table.bku-table th {
            font-weight: bold;
            text-align: center;
            background-color: #fff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .currency-cell {
            white-space: nowrap;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-top: 35px;
            page-break-inside: avoid;
        }
        .signature-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            font-size: 10pt;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <!-- Header / Judul Laporan -->
    <div class="header-title">
        <h1>TEFA PERHOTELAN</h1>
        <h2>BUKU KAS UMUM</h2>
        <h3>{{ $periodeJudul }}</h3>
    </div>

    <!-- Tabel BKU -->
    <table class="bku-table">
        <thead>
            <tr>
                <th style="width: 16%;">Tanggal</th>
                <th style="width: 22%;">Nomor Transaksi</th>
                <th style="width: 25%;">Keterangan</th>
                <th style="width: 6%;">Ref</th>
                <th style="width: 11%;">Penerimaan</th>
                <th style="width: 10%;">Pengeluaran</th>
                <th style="width: 10%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Saldo Awal -->
            <tr>
                <td class="text-left">{{ $tanggalAwalFormatted }}</td>
                <td class="text-center">-</td>
                <td class="text-left font-bold">{{ $saldoAwalLabel }}</td>
                <td class="text-center">-</td>
                <td class="text-right currency-cell font-bold">
                    {{ $saldoAwal > 0 ? 'Rp ' . number_format($saldoAwal, 0, ',', '.') : 'Rp 0' }}
                </td>
                <td class="text-right currency-cell">Rp -</td>
                <td class="text-right currency-cell font-bold">
                    {{ $saldoAwal > 0 ? 'Rp ' . number_format($saldoAwal, 0, ',', '.') : 'Rp 0' }}
                </td>
            </tr>

            <!-- Daftar Transaksi & Pengeluaran -->
            @foreach($ledgerItems as $item)
            <tr>
                <td class="text-left">{{ $item['tanggal'] }}</td>
                <td class="text-center">{{ $item['no_bukti'] }}</td>
                <td class="text-left">{{ $item['keterangan'] }}</td>
                <td class="text-center">{{ $item['ref'] }}</td>
                <td class="text-right currency-cell">
                    {{ $item['debet'] > 0 ? 'Rp ' . number_format($item['debet'], 0, ',', '.') : '' }}
                </td>
                <td class="text-right currency-cell">
                    {{ $item['kredit'] > 0 ? 'Rp ' . number_format($item['kredit'], 0, ',', '.') : '' }}
                </td>
                <td class="text-right currency-cell font-bold" style="{{ $item['saldo'] < 0 ? 'color: #b91c1c;' : '' }}">
                    @if($item['saldo'] < 0)
                        -Rp {{ number_format(abs($item['saldo']), 0, ',', '.') }}
                    @else
                        Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                    @endif
                </td>
            </tr>
            @endforeach

            <!-- Total -->
            <tr class="font-bold" style="background-color: #f8fafc;">
                <td colspan="4" class="text-center">TOTAL KAS (PENERIMAAN & PENGELUARAN)</td>
                <td class="text-right currency-cell">Rp {{ number_format($totalPenerimaanKumulatif ?? ($saldoAwal + $totalDebet), 0, ',', '.') }}</td>
                <td class="text-right currency-cell">{{ $totalKredit > 0 ? 'Rp ' . number_format($totalKredit, 0, ',', '.') : 'Rp -' }}</td>
                <td class="text-right currency-cell font-bold" style="{{ $saldoAkhir < 0 ? 'color: #b91c1c;' : '' }}">
                    @if($saldoAkhir < 0)
                        -Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                    @else
                        Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Ringkasan Penutupan Kas -->
    <table style="width: 100%; margin-top: 14px; border-collapse: collapse; font-size: 9pt;">
        <tr>
            <td style="width: 58%; vertical-align: top; border: none; padding-right: 15px;">
                @if($saldoAkhir < 0)
                    <div style="padding: 7px 10px; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 4px; color: #991b1b; font-size: 8pt; line-height: 1.4;">
                        <strong>Catatan Kas Defisit:</strong> Total pengeluaran periode ini (Rp {{ number_format($totalKredit, 0, ',', '.') }}) melebihi penerimaan kas (Rp {{ number_format($totalPenerimaanKumulatif ?? ($saldoAwal + $totalDebet), 0, ',', '.') }}). Masukkan <em>Saldo Awal Kas / Modal Kas Operasional</em> pada filter unduh BKU jika terdapat modal kas fisik di laci kasir.
                    </div>
                @endif
            </td>
            <td style="width: 42%; vertical-align: top; border: none;">
                <table style="width: 100%; border-collapse: collapse; font-size: 8.5pt;">
                    <tr>
                        <td style="padding: 2px 4px; border: none;">Saldo Awal Kas</td>
                        <td style="padding: 2px 4px; border: none; text-align: right;">: Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px; border: none;">Penerimaan Periode Ini</td>
                        <td style="padding: 2px 4px; border: none; text-align: right;">: Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px; border: none;">Pengeluaran Periode Ini</td>
                        <td style="padding: 2px 4px; border: none; text-align: right;">: Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="font-weight: bold; border-top: 1px solid #000;">
                        <td style="padding: 3px 4px; border: none;">Sisa Saldo Kas Akhir</td>
                        <td style="padding: 3px 4px; border: none; text-align: right; {{ $saldoAkhir < 0 ? 'color: #b91c1c;' : '' }}">
                            : {{ $saldoAkhir < 0 ? '-Rp ' . number_format(abs($saldoAkhir), 0, ',', '.') : 'Rp ' . number_format($saldoAkhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%; padding-left: 20px;">
                Menyetujui, :<br>
                Manajer/Ketua,
                <div class="signature-space"></div>
            </td>
            <td style="width: 50%; padding-left: 40px;">
                Bagian Administrasi,
                <div class="signature-space"></div>
            </td>
        </tr>
    </table>

</body>
</html>
