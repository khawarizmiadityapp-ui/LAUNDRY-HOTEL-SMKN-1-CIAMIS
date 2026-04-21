<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; }
    </style>
</head>
<body>

<h2>Laporan Transaksi</h2>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Layanan</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $trx)
        <tr>
            <td>{{ $trx->transaksi_code }}</td>
            <td>{{ $trx->customer_name }}</td>
            <td>{{ $trx->service_type }}</td>
            <td>{{ $trx->total_price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>