@extends('layouts.admin')

@section('title', 'Edit Kategori Pengeluaran')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.kategori-pengeluaran.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-slate-800">Edit Kategori Pengeluaran</h1>
        <p class="text-slate-600 mt-1">Perbarui informasi kategori</p>
    </div>

    {{-- Info: Digunakan oleh berapa pengeluaran --}}
    @if($kategoriPengeluaran->pengeluarans_count > 0)
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-blue-700 font-medium">Kategori ini digunakan oleh {{ $kategoriPengeluaran->pengeluarans_count }} pengeluaran</p>
        </div>
    </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('admin.kategori-pengeluaran.update', $kategoriPengeluaran) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Kategori --}}
            <div class="mb-6">
                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama" 
                       id="nama" 
                       value="{{ old('nama', $kategoriPengeluaran->nama) }}" 
                       required
                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                       placeholder="Contoh: Gaji Karyawan">
                @error('nama')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4"
                          class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                          placeholder="Opsional: Jelaskan kategori pengeluaran ini">{{ old('deskripsi', $kategoriPengeluaran->deskripsi) }}</textarea>
                @error('deskripsi')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $kategoriPengeluaran->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <span class="ml-3 text-sm font-medium text-slate-700">Aktifkan kategori ini</span>
                </label>
                <p class="ml-8 mt-1 text-sm text-slate-500">Kategori aktif akan muncul di dropdown pengeluaran</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.kategori-pengeluaran.index') }}" 
                   class="px-6 py-3 bg-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-300 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
