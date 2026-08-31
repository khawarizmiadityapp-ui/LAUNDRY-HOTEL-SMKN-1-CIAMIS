@extends('layouts.admin')

@section('title', 'Manajemen Jadwal Piket Petugas')

@section('content')
<div x-data="jadwalPetugasManager()" class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Jadwal Piket Petugas</h1>
                    <p class="text-slate-500 text-sm">Kelola jadwal piket siswa/petugas melalui Import Excel dan monitoring kehadiran stasiun</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.jadwal.template') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download Template
            </a>
            <button @click="openImportModal = true" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Import Excel
            </button>
            <button @click="openAddModal = true" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-blue-600/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Manual
            </button>
        </div>
    </div>

    {{-- Alert Success / Error --}}
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>{!! session('success') !!}</div>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-sm">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <div>{!! session('error') !!}</div>
    </div>
    @endif

    {{-- Stats Cards (Monitoring Hari Ini) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Terjadwal</p>
            <p class="text-2xl font-black text-slate-800 mt-1">{{ $statsToday['total'] }}</p>
            <span class="text-[11px] text-slate-500">petugas pada tanggal ini</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Sudah Check-In</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $statsToday['checked_in'] }}</p>
            <span class="text-[11px] text-emerald-600 font-medium">memilih bagian tugas</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Washing</p>
                <span class="text-base">🌊</span>
            </div>
            <p class="text-2xl font-black text-blue-700 mt-1">{{ $statsToday['washing'] }}</p>
            <span class="text-[11px] text-slate-400">petugas cuci</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Ironing</p>
                <span class="text-base">♨️</span>
            </div>
            <p class="text-2xl font-black text-amber-700 mt-1">{{ $statsToday['setrika'] }}</p>
            <span class="text-[11px] text-slate-400">petugas ironing</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-purple-100 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Packing</p>
                <span class="text-base">📦</span>
            </div>
            <p class="text-2xl font-black text-purple-700 mt-1">{{ $statsToday['packing'] }}</p>
            <span class="text-[11px] text-slate-400">petugas packing</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum Check-In</p>
            <p class="text-2xl font-black text-slate-500 mt-1">{{ $statsToday['pending_checkin'] }}</p>
            <span class="text-[11px] text-amber-600 font-medium">belum memilih stasiun</span>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
                <input type="date" name="date" value="{{ $selectedDate }}" 
                       class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50"
                       onchange="this.form.submit()">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Stasiun Tugas</label>
                <select name="station" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50"
                        onchange="this.form.submit()">
                    <option value="all" {{ request('station') == 'all' ? 'selected' : '' }}>Semua Stasiun</option>
                    <option value="washing" {{ request('station') == 'washing' ? 'selected' : '' }}>🌊 Washing (Cuci)</option>
                    <option value="setrika" {{ request('station') == 'setrika' ? 'selected' : '' }}>♨️ Ironing</option>
                    <option value="packing" {{ request('station') == 'packing' ? 'selected' : '' }}>📦 Packing</option>
                    <option value="none" {{ request('station') == 'none' ? 'selected' : '' }}>⏳ Belum Check-in</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Cari Nama Siswa</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama..." 
                           class="w-full pl-9 pr-3.5 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    Filter
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition text-center flex items-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Jadwal --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Nama Siswa / Petugas</th>
                        <th class="py-3.5 px-4">ID / NIS</th>
                        <th class="py-3.5 px-4">Shift</th>
                        <th class="py-3.5 px-4">Stasiun Terpilih (Model 1)</th>
                        <th class="py-3.5 px-4">Waktu Check-In</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($jadwalList as $item)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">{{ $item->nama }}</div>
                            @if($item->keterangan)
                            <span class="text-xs text-slate-400">{{ $item->keterangan }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs font-mono text-slate-500">
                            {{ $item->id_petugas ?: '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">
                                {{ $item->shift }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($item->selected_station === 'washing')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold">
                                    <span>🌊</span> Washing (Cuci)
                                </span>
                            @elseif($item->selected_station === 'setrika')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold">
                                    <span>♨️</span> Ironing
                                </span>
                            @elseif($item->selected_station === 'packing')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-bold">
                                    <span>📦</span> Packing
                                </span>
                            @elseif($item->selected_station === 'kasir')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold">
                                    <span>🏪</span> Kasir / CS
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-medium">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Belum Pilih Stasiun
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-500">
                            {{ $item->checked_in_at ? \Carbon\Carbon::parse($item->checked_in_at)->format('H:i') . ' WIB' : '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($item->status === 'hadir')
                                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-xs font-semibold">Hadir</span>
                            @elseif($item->status === 'izin')
                                <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">Izin</span>
                            @elseif($item->status === 'alpha')
                                <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 rounded-full text-xs font-semibold">Alpha</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Terjadwal</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="editJadwal({{ json_encode($item) }})" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal untuk {{ $item->nama }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-600">Belum ada jadwal untuk tanggal ini</p>
                            <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Import Excel" atau "Tambah Manual" untuk membuat jadwal.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($jadwalList->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $jadwalList->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL IMPORT EXCEL --}}
    <div x-show="openImportModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
         @click.self="openImportModal = false">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl animate-scale-up">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Import Jadwal dari Excel</h3>
                </div>
                <button @click="openImportModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.jadwal.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <div class="bg-blue-50/60 p-4 rounded-xl border border-blue-100 text-xs text-blue-800 space-y-2">
                    <p class="font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        Petunjuk Import:
                    </p>
                    <ul class="list-disc pl-4 space-y-1 text-slate-600">
                        <li>File wajib berformat <strong>.xlsx</strong> atau <strong>.csv</strong>.</li>
                        <li>Kolom format Excel: <strong>Tanggal, Nama Siswa / Petugas, ID / NIS, Shift, Keterangan</strong>.</li>
                        <li>Siswa yang di-import otomatis bisa memilih sendiri stasiun tugasnya (**Washing, Ironing, Packing**) di halaman piket.</li>
                    </ul>
                    <div class="pt-1">
                        <a href="{{ route('admin.jadwal.template') }}" class="text-blue-600 font-bold hover:underline inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Unduh Template Contoh (.xlsx)
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pilih File Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                           class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer">
                </div>

                <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" @click="openImportModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH MANUAL --}}
    <div x-show="openAddModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
         @click.self="openAddModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl animate-scale-up">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Tambah Jadwal Piket</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $selectedDate }}" required 
                           class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Siswa / Petugas</label>
                    <input type="text" name="nama" required placeholder="Contoh: Siti Aminah" 
                           class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Shift</label>
                        <select name="shift" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                            <option value="Full Day">Full Day</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Stasiun (Opsional)</label>
                        <select name="selected_station" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                            <option value="none">Biarkan Siswa Pilih Sendiri</option>
                            <option value="washing">Washing (Cuci)</option>
                            <option value="setrika">Ironing</option>
                            <option value="packing">Packing</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Keterangan / Catatan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Piket Kelas XII Perhotelan 1" 
                           class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                </div>

                <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div x-show="openEditModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
         @click.self="openEditModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl animate-scale-up">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Edit Jadwal & Stasiun</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form :action="'{{ url('/admin/jadwal-petugas') }}/' + editingItem.id" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Petugas</label>
                    <input type="text" :value="editingItem.nama" disabled class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-100 text-slate-500 font-bold">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Shift</label>
                        <select name="shift" x-model="editingItem.shift" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                            <option value="Full Day">Full Day</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Stasiun Tugas</label>
                        <select name="selected_station" x-model="editingItem.selected_station" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                            <option value="none">Belum Pilih Stasiun</option>
                            <option value="washing">🌊 Washing (Cuci)</option>
                            <option value="setrika">♨️ Ironing</option>
                            <option value="packing">📦 Packing</option>
                            <option value="kasir">🏪 Kasir</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status Kehadiran</label>
                    <select name="status" x-model="editingItem.status" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                        <option value="terjadwal">Terjadwal</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" x-model="editingItem.keterangan" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50">
                </div>

                <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function jadwalPetugasManager() {
        return {
            openImportModal: false,
            openAddModal: false,
            openEditModal: false,
            editingItem: {},

            editJadwal(item) {
                this.editingItem = Object.assign({}, item);
                this.openEditModal = true;
            }
        };
    }
</script>
@endpush
