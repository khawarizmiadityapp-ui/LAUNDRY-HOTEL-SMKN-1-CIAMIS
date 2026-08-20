<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapan Pembayaran Harian - {{ $tanggalFormatted }}</title>
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
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9.5px; }
        th, td { border: 1px solid #718096; padding: 5px 6px; vertical-align: middle; }
        th { background-color: #edf2f7; font-weight: bold; text-align: center; font-size: 9px; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier', monospace; }
        .font-bold { font-weight: bold; }

        .section-title { font-size: 11px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; color: #1e3a8a; }

        .summary-grid { width: 100%; border-collapse: collapse; border: none; margin-bottom: 15px; }
        .summary-grid td { border: none; padding: 4px; }
        .stat-card { border: 1px solid #cbd5e0; padding: 8px; background: #f8fafc; border-radius: 4px; }
        .stat-card span { font-size: 8.5px; color: #64748b; text-transform: uppercase; display: block; }
        .stat-card strong { font-size: 12px; color: #0f172a; }

        .signature-container { margin-top: 25px; width: 100%; }
        .signature-table { width: 100%; border-collapse: collapse; border: none; }
        .signature-table td { border: none; text-align: center; font-size: 9.5px; }
        .signature-space { height: 50px; }
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
        <h2>REKAPAN PEMBAYARAN & PELAYANAN HARIAN</h2>
        <p><strong>Hari / Tanggal:</strong> {{ $tanggalFormatted }}</p>
    </div>

    {{-- STATISTIK KARTU --}}
    <table class="summary-grid">
        <tr>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Total Omzet Diterima</span>
                    <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Jumlah Transaksi</span>
                    <strong>{{ $totalTransaksi }} Transaksi</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Pembayaran Tunai</span>
                    <strong>Rp {{ number_format($totalTunai, 0, ',', '.') }}</strong>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="stat-card">
                    <span>Pembayaran Non-Tunai</span>
                    <strong>Rp {{ number_format($totalNonTunai, 0, ',', '.') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    {{-- 1. RINCIAN BREAKDOWN PELAYANAN --}}
    <div class="section-title">1. Rincian Pelayanan Laundry Hari Ini</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Layanan Laundry</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Total Qty / Berat</th>
                <th style="width: 15%;">Jumlah Order</th>
                <th style="width: 15%;">Total Penerimaan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($serviceBreakdown as $srv)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="font-bold">{{ $srv['nama'] }}</td>
                <td class="text-center uppercase" style="font-size: 8.5px;">{{ $srv['kategori'] }}</td>
                <td class="text-center font-mono">{{ $srv['qty'] }} {{ $srv['kategori'] == 'kiloan' ? 'kg' : 'pcs' }}</td>
                <td class="text-center">{{ $srv['count'] }}x</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($srv['total'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #718096;">Tidak ada transaksi layanan pada hari ini.</td>
            </tr>
            @endforelse
            <tr style="background-color: #edf2f7; font-weight: bold;">
                <td colspan="5" class="text-center">TOTAL PENERIMAAN LAYANAN</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- 2. DAFTAR TRANSAKSI MASUK --}}
    <div class="section-title" style="margin-top: 15px;">2. Daftar Transaksi & Pelunasan Pelanggan</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No. Transaksi</th>
                <th style="width: 25%;">Pelanggan</th>
                <th style="width: 25%;">Rincian Layanan</th>
                <th style="width: 15%;">Metode</th>
                <th style="width: 15%;">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @php $noTrx = 1; @endphp
            @forelse($transactions as $trx)
            <tr>
                <td class="text-center">{{ $noTrx++ }}</td>
                <td class="text-center font-mono" style="font-size: 8.5px;">{{ $trx->transaksi_code }}</td>
                <td>
                    <strong>{{ $trx->customer_name }}</strong>
                    <div style="font-size: 8px; color: #64748b;">{{ $trx->customer_phone ?: '-' }}</div>
                </td>
                <td>
                    @if($trx->details && $trx->details->count() > 0)
                        {{ $trx->details->map(fn($d) => ($d->layanan->nama ?? 'Layanan') . ' (' . $d->qty . 'x)')->join(', ') }}
                    @else
                        {{ ucfirst($trx->service_type) }} ({{ $trx->weight }} kg)
                    @endif
                </td>
                <td class="text-center uppercase" style="font-size: 8.5px;">
                    {{ str_replace('_', ' ', $trx->payment_method ?: 'Tunai') }}
                </td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #718096;">Belum ada transaksi pada tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 45%;">
                    Mengetahui,<br>
                    <strong>Kepala Program Keahlian / Unit Usaha</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">Dra. Hj. Nunung Rohanah, M.Pd.</div>
                    <div>NIP. 19680512 199303 2 004</div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    Ciamis, {{ $tanggalFormatted }}<br>
                    <strong>Kasir / Petugas Penerima Pembayaran</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ auth()->user()->name ?? 'Kasir Laundry' }}</div>
                    <div>Unit Laundry Hotel SMKN 1 Ciamis</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
