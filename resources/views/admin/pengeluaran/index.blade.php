{{-- resources/views/admin/pengeluaran/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Pengeluaran')
@section('page-title', 'Manajemen Pengeluaran')

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    {{-- Card 1: Total Pengeluaran --}}
    <div class="card-stat bg-white rounded-2xl shadow-card p-6 border border-gray-100 fade-in">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs font-500 text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran Bulan Ini</p>
        <p class="font-display text-2xl font-700 text-gray-900 tracking-tight">{{ rupiah($totalBulanIni) }}</p>
    </div>

    {{-- Card 2: Sisa Anggaran --}}
    <div class="card-stat bg-white rounded-2xl shadow-card p-6 border border-gray-100 fade-in delay-1">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
        <p class="text-xs font-500 text-gray-400 uppercase tracking-wider mb-1">Sisa Anggaran Operasional</p>
        <p class="font-display text-2xl font-700 text-gray-900 tracking-tight mb-3">{{ rupiah($sisaAnggaran) }}</p>
        {{-- Progress bar --}}
        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
            @php
                $target = $targetAnggaran ?? 0;
                $nilai = $sisaAnggaran ?? 0;

                $pct = $target > 0 ? round(($nilai / $target) * 100) : 0;
            @endphp
            <div class="progress-bar-inner h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"
                 style="width: {{ $pct }}%"></div>
        </div>
        <p class="text-[11px] text-gray-400 mt-1.5">Anggaran Bulanan: {{ rupiah($targetAnggaran) }}</p>
    </div>

    {{-- Card 3: Kategori Terbesar --}}
    <div class="card-stat bg-white rounded-2xl shadow-card p-6 border border-gray-100 fade-in delay-2">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="badge-terbesar text-[10px] font-700 uppercase tracking-wider px-2.5 py-1 rounded-full">Terbesar</span>
        </div>
        <p class="text-xs font-500 text-gray-400 uppercase tracking-wider mb-1">Kategori Terbesar</p>
        <p class="font-display text-xl font-700 text-gray-900 tracking-tight leading-tight">{{ $kategoriTerbesar['nama'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Kontribusi <span class="font-700 text-red-500">{{ $kategoriTerbesar['persen'] }}%</span> dari total pengeluaran.</p>
    </div>

</div>{{-- end stat cards --}}


{{-- ===== ACTION BAR ===== --}}
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2">

        {{-- Filter --}}
        <button onclick="toggleFilterPanel()"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-500 text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition shadow-card">
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter
        </button>


    </div>

    <div class="flex items-center gap-3">
        {{-- Atur Anggaran --}}
        <button onclick="openAnggaranModal()"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-600 text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 rounded-xl shadow-sm transition-all hover:shadow-md hover:-translate-y-px active:translate-y-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Atur Anggaran
        </button>

        {{-- Tambah Baru --}}
        <a href="{{ route('admin.pengeluaran.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-600 text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all hover:shadow-md hover:-translate-y-px active:translate-y-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengeluaran Baru
        </a>
    </div>
</div>

{{-- ===== FILTER PANEL (hidden by default) ===== --}}
<div id="filterPanel" class="hidden bg-white border border-gray-200 rounded-2xl shadow-card p-5 mb-5">
    <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-600 text-gray-500 mb-1.5 uppercase tracking-wider">Kategori</label>
            <select name="kategori" class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-white">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-600 text-gray-500 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari') }}"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
        <div>
            <label class="block text-xs font-600 text-gray-500 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
        <div class="flex gap-2 mt-auto">
            <button type="submit" class="px-4 py-2.5 text-sm font-600 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">Terapkan</button>
            <a href="{{ route('admin.pengeluaran.index') }}" class="px-4 py-2.5 text-sm font-600 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>


{{-- ===== TABLE ===== --}}
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden fade-in delay-3">
    <div class="overflow-x-auto min-h-[320px]">
        <table class="w-full text-sm" id="pengeluaranTable">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="text-left px-6 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">ID Transaksi</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Nama Pengeluaran</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Kategori</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="text-right px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Nominal</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Bon</th>

                    <th class="text-center px-6 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengeluarans as $item)
                <tr class="table-row transition-colors" data-search="{{ strtolower($item->nama . ' ' . $item->kategori_nama . ' ' . $item->id_transaksi) }}">

                    {{-- ID Transaksi --}}
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.pengeluaran.show', $item) }}"
                           class="text-blue-600 font-600 hover:text-blue-700 hover:underline transition">#{{ $item->id_transaksi }}</a>
                    </td>

                    {{-- Nama --}}
                    <td class="px-4 py-4">
                        <p class="font-500 text-gray-800">{{ $item->nama }}</p>
                        @if($item->keterangan)
                            <p class="text-xs text-gray-400 uppercase tracking-wider mt-0.5">{{ $item->keterangan }}</p>
                        @endif
                    </td>

                    {{-- Kategori --}}
                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-500 bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">{{ $item->kategori_nama }}</span>
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-4 py-4 text-gray-600 tabular-nums">
                        {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM YYYY') }}
                    </td>

                    {{-- Nominal --}}
                    <td class="px-4 py-4 text-right font-600 text-gray-800 tabular-nums">
                        {{ rupiah($item->nominal) }}
                    </td>

                    {{-- Bon --}}
                    <td class="px-4 py-4">
                        @if($item->bon_file)
                            <a href="{{ asset('storage/' . $item->bon_file) }}" target="_blank" class="text-xs font-600 text-blue-600 hover:underline">Lihat Bon</a>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>


                    {{-- Aksi Dropdown --}}
                    <td class="px-6 py-4 text-center relative">
                        <button onclick="toggleDropdown('dropdown-{{ $item->id }}')"
                            class="btn-action w-8 h-8 rounded-lg flex items-center justify-center mx-auto text-gray-400 hover:text-gray-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <div id="dropdown-{{ $item->id }}" class="hidden z-50 dropdown-menu bg-white border border-gray-100 rounded-xl shadow-card-hover py-1 absolute right-0 top-full mt-1">
                            <a href="{{ route('admin.pengeluaran.show', $item) }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            <button type="button" onclick="openEditPengeluaranModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->kategori_id }}', '{{ optional($item->tanggal)->format('Y-m-d') }}', '{{ $item->nominal }}', '{{ preg_replace('/\r|\n/', ' ', addslashes($item->keterangan)) }}')"
                               class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <div class="my-1 border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('admin.pengeluaran.destroy', $item) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="font-500">Belum ada data pengeluaran</p>
                        <a href="{{ route('admin.pengeluaran.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">+ Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Table Footer: count + pagination --}}
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-gray-50/40">
        <p class="text-xs text-gray-400">
            Menampilkan <span class="font-600 text-gray-600">{{ $pengeluarans->firstItem() }}–{{ $pengeluarans->lastItem() }}</span>
            dari <span class="font-600 text-gray-600">{{ $pengeluarans->total() }}</span> pengeluaran
        </p>
        <div>
            {{ $pengeluarans->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
    </div>

</div>{{-- end table card --}}

{{-- MODAL ANGGARAN --}}
<div id="anggaranModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative mx-auto p-6 border w-full max-w-sm shadow-lg rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-gray-900">Atur Anggaran Bulanan</h3>
            <button onclick="closeAnggaranModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('admin.pengeluaran.anggaran.update') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal Anggaran</label>
                <div class="flex items-center">
                    <span class="bg-gray-100 border border-gray-300 border-r-0 px-4 py-2.5 rounded-l-xl text-gray-500 font-medium">Rp</span>
                    <input type="number" name="anggaran_bulanan" value="{{ $targetAnggaran }}" class="w-full border border-gray-300 rounded-r-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none" required min="0">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAnggaranModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-medium transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-medium transition-colors">
                    Simpan Anggaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PENGELUARAN --}}
<div id="editPengeluaranModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4 transition-all duration-300" onclick="if(event.target === this) closeEditPengeluaranModal()">
    <div class="relative mx-auto border w-full max-w-3xl shadow-2xl rounded-2xl bg-white overflow-hidden flex flex-col max-h-[90vh]" style="animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Edit Pengeluaran</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui detail pengeluaran.</p>
            </div>
            <button onclick="closeEditPengeluaranModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200/50 text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="editPengeluaranForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pengeluaran</label>
                    <input type="text" id="edit_pengeluaran_nama" name="nama" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50 hover:bg-white focus:bg-white transition-all outline-none" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select id="edit_pengeluaran_kategori_id" name="kategori_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50 hover:bg-white focus:bg-white transition-all outline-none" required>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal</label>
                        <input type="date" id="edit_pengeluaran_tanggal" name="tanggal" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50 hover:bg-white focus:bg-white transition-all outline-none" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nominal</label>
                    <input type="number" min="0" id="edit_pengeluaran_nominal" name="nominal" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50 hover:bg-white focus:bg-white transition-all outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan</label>
                    <textarea id="edit_pengeluaran_keterangan" name="keterangan" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50 hover:bg-white focus:bg-white transition-all outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Bon Baru (opsional)</label>
                    <input type="file" name="bon_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50 hover:bg-white focus:bg-white transition-all outline-none">
                </div>
                <div class="pt-5 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeEditPengeluaranModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-sm font-bold shadow-md transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

@endsection

@push('scripts')
<script>
    // Frontend search
    const searchInput = document.getElementById('searchInput');
    searchInput?.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('#pengeluaranTable tbody tr[data-search]').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    });

    function toggleFilterPanel() {
        const p = document.getElementById('filterPanel');
        p.classList.toggle('hidden');
    }

    function openAnggaranModal() {
        document.getElementById('anggaranModal').classList.remove('hidden');
    }
    
    function closeAnggaranModal() {
        document.getElementById('anggaranModal').classList.add('hidden');
    }
    
    function openEditPengeluaranModal(id, nama, kategoriId, tanggal, nominal, keterangan) {
        document.getElementById('editPengeluaranModal').classList.remove('hidden');
        document.getElementById('editPengeluaranForm').action = `/admin/pengeluaran/${id}`;
        document.getElementById('edit_pengeluaran_nama').value = nama;
        document.getElementById('edit_pengeluaran_kategori_id').value = kategoriId;
        document.getElementById('edit_pengeluaran_tanggal').value = tanggal;
        document.getElementById('edit_pengeluaran_nominal').value = nominal;
        document.getElementById('edit_pengeluaran_keterangan').value = keterangan || '';
    }

    function closeEditPengeluaranModal() {
        document.getElementById('editPengeluaranModal').classList.add('hidden');
    }
</script>
@endpush
