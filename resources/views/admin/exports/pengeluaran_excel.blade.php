<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .header-title { font-size: 14pt; font-weight: bold; text-align: center; color: #b91c1c; }
        .table-head { font-weight: bold; background-color: #fee2e2; text-align: center; }
        .sub-total { font-weight: bold; background-color: #fecaca; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="6" class="header-title">RINCIAN BEBAN &amp; PENGELUARAN OPERASIONAL</td>
        </tr>
        <tr>
            <td colspan="6" class="text-center" style="font-size: 10pt; color: #64748b;">
                Periode: 
                @if($filter == 'bulanan')
                    Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                @elseif($filter == 'tahunan')
                    Tahun {{ \Carbon\Carbon::now()->year }}
                @elseif($filter == 'custom')
                    {{ $dari ? \Carbon\Carbon::parse($dari)->format('d/m/Y') : '-' }} s/d {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d/m/Y') : '-' }}
                @else
                    Semua Periode
                @endif
            </td>
        </tr>
        <tr><td colspan="6"></td></tr>

        <tr class="table-head">
            <th width="15">ID Transaksi</th>
            <th width="25">Kategori</th>
            <th width="30">Nama / Keterangan Pengeluaran</th>
            <th width="20">Nominal (Rp)</th>
            <th width="18">Tanggal</th>
            <th width="20">Detail Keterangan</th>
        </tr>
        @php $grandTotal = 0; @endphp
        @forelse($pengeluaranData as $item)
            @php $grandTotal += $item->nominal; @endphp
            <tr>
                <td class="text-center">{{ $item->id_transaksi ?? 'EXP-'.$item->id }}</td>
                <td>{{ $item->kategori_nama }}</td>
                <td>{{ $item->nama ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center" style="color: #94a3b8;">Tidak ada data pengeluaran pada periode ini.</td>
            </tr>
        @endforelse
        
        <tr class="sub-total">
            <td colspan="3" class="text-center" style="font-weight: bold;">TOTAL PENGELUARAN</td>
            <td class="text-right" style="font-weight: bold;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
    </table>
</body>
</html>
