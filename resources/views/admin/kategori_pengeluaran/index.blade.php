@extends('layouts.admin')

@section('title', 'Kategori Pengeluaran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Kategori Pengeluaran</h1>
            <p class="text-slate-600 mt-1">Kelola kategori untuk pengeluaran operasional</p>
        </div>
        <a href="{{ route('admin.kategori-pengeluaran.create') }}" 
           class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Kategori</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Kategori Aktif</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['aktif'] }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-500 to-slate-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-100 text-sm font-medium">Tidak Aktif</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['tidak_aktif'] }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="GET" action="{{ route('admin.kategori-pengeluaran.index') }}" class="flex gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari kategori..." 
                   class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.kategori-pengeluaran.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-300 transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Jumlah Digunakan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kategoris as $kategori)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-800">{{ $kategori->nama }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $kategori->deskripsi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $kategori->pengeluarans_count }} pengeluaran
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($kategori->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                <span class="w-2 h-2 bg-slate-500 rounded-full mr-2"></span>
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.kategori-pengeluaran.edit', $kategori) }}" 
                                   class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.kategori-pengeluaran.toggle-status', $kategori) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2 {{ $kategori->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors" 
                                            title="{{ $kategori->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if($kategori->is_active)
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @endif
                                    </button>
                                </form>

                                <form action="{{ route('admin.kategori-pengeluaran.destroy', $kategori) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-lg font-medium">Tidak ada kategori ditemukan</p>
                            <p class="text-sm mt-1">Silakan tambahkan kategori pengeluaran baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kategoris->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $kategoris->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
