@extends('layouts.admin')

@section('title', 'Detail Pengeluaran - Bening Laundry')

@section('content')
<div class="max-w-2xl bg-white border border-gray-200 rounded-2xl p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-5">Detail Pengeluaran</h1>

    <dl class="space-y-3 text-sm">
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">ID Transaksi</dt>
            <dd class="col-span-2 font-semibold">{{ $pengeluaran->id_transaksi }}</dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Nama</dt>
            <dd class="col-span-2">{{ $pengeluaran->nama }}</dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Kategori</dt>
            <dd class="col-span-2">{{ $pengeluaran->kategori }}</dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Tanggal</dt>
            <dd class="col-span-2">{{ optional($pengeluaran->tanggal)->format('d/m/Y') }}</dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Nominal</dt>
            <dd class="col-span-2 font-semibold">Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}</dd>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Keterangan</dt>
            <dd class="col-span-2">{{ $pengeluaran->keterangan ?? '-' }}</dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">File Bon</dt>
            <dd class="col-span-2">
                @if($pengeluaran->bon_file)
                    <a href="{{ asset('storage/' . $pengeluaran->bon_file) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File Bon</a>
                @else
                    -
                @endif
            </dd>
        </div>
    </dl>

    <div class="mt-6 flex items-center gap-3">
        <a href="{{ route('admin.pengeluaran.edit', $pengeluaran) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">Edit</a>
        <a href="{{ route('admin.pengeluaran.index') }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-xl">Kembali</a>
    </div>
</div>
@endsection
