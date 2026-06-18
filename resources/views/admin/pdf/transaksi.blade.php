<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2 class="text-center">Laporan Transaksi Keuangan</h2>
@if(isset($filter))
    <p class="text-center">Filter: {{ str_replace('_', ' ', ucfirst($filter)) }}</p>
@endif

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

</body>
</html>