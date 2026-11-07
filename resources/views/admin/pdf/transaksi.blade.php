<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Keuangan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .kop-surat { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .kop-surat h1 { margin: 0; font-size: 24px; color: #1e3a8a; }
        .kop-surat p { margin: 3px 0; font-size: 12px; color: #4b5563; }
        .text-center { text-align: center; }
        .title-report { font-size: 16px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .filter-report { font-size: 12px; margin-bottom: 20px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        .signature-section { margin-top: 50px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-date { margin-bottom: 50px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

<div class="kop-surat">
    <h1>BENING LAUNDRY</h1>
    <p>Jl. Jend. Sudirman No. 123, Ciamis, Jawa Barat</p>
    <p>Telp: 0812-3456-7890 | Email: info@beninglaundry.com</p>
</div>

<div class="text-center">
    <div class="title-report">Laporan Transaksi Keuangan</div>
    @if(isset($filter))
        <div class="filter-report">Periode: {{ str_replace('_', ' ', ucfirst($filter)) }}</div>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Layanan</th>
            <th>Berat</th>
            <th>Total Harga</th>
            <th>Metode Bayar</th>
            <th>Status Pengerjaan</th>
            <th>Status Pembayaran</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $trx)
        <tr>
            <td>{{ $trx->transaksi_code }}</td>
            <td>{{ $trx->customer_name }}</td>
            <td>{{ ucfirst($trx->service_type) }}</td>
            <td>{{ $trx->weight }} kg</td>
            <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
            <td>{{ strtoupper(classify_payment_method($trx->payment_method)) }}</td>
            <td>{{ ucfirst($trx->status) }}</td>
            <td>{{ str_replace('_', ' ', ucfirst($trx->payment_status)) }}</td>
            <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(isset($rekapPembayaran))
<div style="margin-top: 20px;">
    <div style="font-weight: bold; font-size: 12px; margin-bottom: 6px; text-transform: uppercase; color: #1e3a8a;">
        Rekapan Metode Pembayaran & Status Pelunasan
    </div>
    <table>
        <thead>
            <tr>
                <th>Metode / Status Pembayaran</th>
                <th>Jumlah Transaksi</th>
                <th>Persentase Omzet Lunas</th>
                <th>Total Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Pembayaran Tunai (Cash)</strong></td>
                <td>{{ $rekapPembayaran['tunai_count'] }} Transaksi</td>
                <td>{{ $rekapPembayaran['tunai_persen'] }}%</td>
                <td><strong>Rp {{ number_format($rekapPembayaran['tunai_total'], 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Pembayaran QRIS (Scan Barcode)</strong></td>
                <td>{{ $rekapPembayaran['qris_count'] }} Transaksi</td>
                <td>{{ $rekapPembayaran['qris_persen'] }}%</td>
                <td><strong>Rp {{ number_format($rekapPembayaran['qris_total'], 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Transfer Bank (BCA / Mandiri / BRI)</strong></td>
                <td>{{ $rekapPembayaran['transfer_count'] }} Transaksi</td>
                <td>{{ $rekapPembayaran['transfer_persen'] }}%</td>
                <td><strong>Rp {{ number_format($rekapPembayaran['transfer_total'], 0, ',', '.') }}</strong></td>
            </tr>
            <tr style="background-color: #e0f2fe; font-weight: bold;">
                <td>TOTAL PENERIMAAN LUNAS</td>
                <td>{{ $rekapPembayaran['lunas_count'] }} Transaksi</td>
                <td>100%</td>
                <td>Rp {{ number_format($rekapPembayaran['lunas_total'], 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #fef2f2; font-weight: bold; color: #991b1b;">
                <td>TOTAL BELUM LUNAS (PIUTANG)</td>
                <td>{{ $rekapPembayaran['belum_bayar_count'] }} Transaksi</td>
                <td>Piutang</td>
                <td>Rp {{ number_format($rekapPembayaran['belum_bayar_total'], 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td>GRAND TOTAL SELURUH TRANSAKSI</td>
                <td>{{ $rekapPembayaran['grand_count'] }} Transaksi</td>
                <td>-</td>
                <td>Rp {{ number_format($rekapPembayaran['grand_total'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endif

<div class="signature-section clearfix">
    <div class="signature-box">
        <div class="signature-date">Ciamis, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
        <div>Mengetahui,</div>
        <br><br><br>
        <div class="signature-name">Pimpinan / Manager</div>
    </div>
</div>

</body>
</html>