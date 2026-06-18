{{--resources/views/admin/pengeluaran/create.blade.php--}}
@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran - Bening Laundry')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengeluaran</h1>
    <p class="text-sm text-gray-500 mt-1">Kategori pengeluaran dibatasi ke 3 item utama dan dapat dilengkapi file bon.</p>

    <div class="mt-6 bg-white border border-gray-200 rounded-2xl p-6">
        <p class="text-xs uppercase tracking-wider text-gray-500">ID Transaksi</p>
        <p class="text-lg font-semibold text-gray-800 mb-5">{{ $idTransaksi }}</p>

        <form action="{{ route('admin.pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengeluaran</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                        <option value="">Pilih kategori</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" min="0" name="nominal" value="{{ old('nominal') }}" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2.5">{{ old('keterangan') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Bon (JPG/PNG/PDF, max 2MB)</label>
                <input type="file" name="bon_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-white">
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold">Simpan</button>
                <a href="{{ route('admin.pengeluaran.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
