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
            <td>{{ ucfirst($trx->status) }}</td>
            <td>{{ str_replace('_', ' ', ucfirst($trx->payment_status)) }}</td>
            <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

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