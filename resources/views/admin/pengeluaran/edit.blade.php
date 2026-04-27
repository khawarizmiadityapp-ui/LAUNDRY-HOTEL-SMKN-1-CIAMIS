@extends('layouts.admin')

@section('title', 'Edit Pengeluaran - Bening Laundry')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pengeluaran</h1>

    <div class="mt-6 bg-white border border-gray-200 rounded-2xl p-6">
        <form action="{{ route('admin.pengeluaran.update', $pengeluaran) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengeluaran</label>
                <input type="text" name="nama" value="{{ old('nama', $pengeluaran->nama) }}" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori', $pengeluaran->kategori) === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', optional($pengeluaran->tanggal)->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                    <input type="number" min="0" name="nominal" value="{{ old('nominal', $pengeluaran->nominal) }}" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                        <option value="pending" {{ old('status', $pengeluaran->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ old('status', $pengeluaran->status) === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="urgent" {{ old('status', $pengeluaran->status) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2.5">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Bon Baru (opsional)</label>
                <input type="file" name="bon_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-white">
                @if($pengeluaran->bon_file)
                    <a href="{{ asset('storage/' . $pengeluaran->bon_file) }}" target="_blank" class="inline-block mt-2 text-sm text-brand-600 hover:underline">Lihat bon saat ini</a>
                @endif
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-semibold">Simpan Perubahan</button>
                <a href="{{ route('admin.pengeluaran.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
