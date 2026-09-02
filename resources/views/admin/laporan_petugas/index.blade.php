@extends('layouts.admin')

@section('title', 'Laporan Pekerjaan Harian Petugas - Bening Laundry')

@section('content')
<div x-data="laporanPetugasApp()" x-cloak class="space-y-6 pb-12 animate-fade-in">
    {{-- HEADER & EXPORT ACTIONS --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-blue-500/20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">Laporan Pekerjaan Petugas</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Rekapitulasi beban kerja, produktivitas stasiun, dan riwayat aktivitas piket</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.laporan_petugas.pdf', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-md shadow-blue-600/20 hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.028-.341-2.09-.341-3.179a6.002 6.002 0 0111.442-2.484M19.5 12.75v4.5A2.25 2.25 0 0117.25 19.5h-10.5A2.25 2.25 0 014.5 17.25v-4.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15l3-3m-3 3l-3-3m3 3V3" />
                </svg>
                <span>Cetak PDF Resmi</span>
            </a>

            <a href="{{ route('admin.laporan_petugas.excel', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-md shadow-emerald-600/20 hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <span>Export Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    {{-- FILTER & PRESETS PANEL --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        {{-- Mode Switcher & Presets --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl text-xs font-semibold">
                <button type="button" 
                        @click="filterMode = 'harian'" 
                        :class="filterMode === 'harian' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3.5 py-1.5 rounded-lg transition">
                    📅 Laporan Harian
                </button>
                <button type="button" 
                        @click="filterMode = 'rentang'" 
                        :class="filterMode === 'rentang' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3.5 py-1.5 rounded-lg transition">
                    📆 Rentang Tanggal (Custom)
                </button>
            </div>

            {{-- Quick Presets --}}
            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                <span class="text-slate-400 font-medium mr-1">Preset:</span>
                <button type="button" @click="setPreset('today')" class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg border border-slate-200 transition">Hari Ini</button>
                <button type="button" @click="setPreset('yesterday')" class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg border border-slate-200 transition">Kemarin</button>
                <button type="button" @click="setPreset('this_week')" class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg border border-slate-200 transition">7 Hari Terakhir</button>
                <button type="button" @click="setPreset('this_month')" class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg border border-slate-200 transition">Bulan Ini</button>
            </div>
        </div>

        {{-- Form Filter --}}
        <form method="GET" action="{{ route('admin.laporan_petugas.index') }}" id="filterForm" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 items-end">
            <input type="hidden" name="filter_mode" :value="filterMode">

            {{-- Tanggal Harian --}}
            <div x-show="filterMode === 'harian'">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Tanggal</label>
                <input type="date" name="date" x-model="singleDate"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            {{-- Dari Tanggal --}}
            <div x-show="filterMode === 'rentang'" class="space-y-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="dari" x-model="dariDate"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            {{-- Sampai Tanggal --}}
            <div x-show="filterMode === 'rentang'" class="space-y-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="sampai" x-model="sampaiDate"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            {{-- Filter Stasiun Tugas --}}
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Stasiun Tugas</label>
                <select name="station" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ ($selectedStation ?? 'all') == 'all' ? 'selected' : '' }}>Semua Stasiun</option>
                    <option value="washing" {{ ($selectedStation ?? '') == 'washing' ? 'selected' : '' }}>🌊 Washing (Pencucian)</option>
                    <option value="ironing" {{ ($selectedStation ?? '') == 'ironing' ? 'selected' : '' }}>♨️ Ironing (Penyetrikaan)</option>
                    <option value="packing" {{ ($selectedStation ?? '') == 'packing' ? 'selected' : '' }}>📦 Packing (Pengemasan)</option>
                    <option value="kasir" {{ ($selectedStation ?? '') == 'kasir' ? 'selected' : '' }}>💳 Kasir / POS</option>
                </select>
            </div>

            {{-- Filter Petugas --}}
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Petugas / Siswa</label>
                <select name="petugas" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ ($selectedPetugas ?? 'all') == 'all' ? 'selected' : '' }}>Semua Petugas</option>
                    @foreach($petugasDropdown as $pName)
                        <option value="{{ $pName }}" {{ ($selectedPetugas ?? '') == $pName ? 'selected' : '' }}>{{ $pName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Shift --}}
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Shift Piket</label>
                <select name="shift" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ ($selectedShift ?? 'all') == 'all' ? 'selected' : '' }}>Semua Shift</option>
                    <option value="Pagi" {{ ($selectedShift ?? '') == 'Pagi' ? 'selected' : '' }}>Pagi</option>
                    <option value="Siang" {{ ($selectedShift ?? '') == 'Siang' ? 'selected' : '' }}>Siang</option>
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="flex gap-2">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.laporan_petugas.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition flex items-center justify-center" title="Reset Filter">
                    ✕
                </a>
            </div>
        </form>
    </div>

    {{-- STATISTIK KPI CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Total Petugas --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Petugas</span>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total_petugas'] }}</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-1">
                        ✓ {{ $stats['petugas_hadir'] }} Hadir / Aktif
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Output Tugas Selesai --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Tugas Selesai</span>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total_tasks'] }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-1">
                        Rata-rata: {{ $stats['avg_tasks_per_petugas'] }} per petugas
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Bobot Cucian --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Bobot Cucian</span>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ number_format($stats['total_weight'], 1) }} <span class="text-lg font-semibold text-slate-500">Kg</span></p>
                    <p class="text-xs text-slate-500 font-medium mt-1">
                        Beban cucian operasional
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-16.5-.52c-1.01.143-2.01.317-3 .52m16.5 0a48.667 48.667 0 013.75 1.07M3.75 5.49a48.667 48.667 0 00-3.75 1.07" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Breakdown Stasiun --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-5 rounded-2xl shadow-sm text-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Output per Bagian</span>
            <div class="grid grid-cols-3 gap-2 mt-3 text-center">
                <div class="bg-white/10 rounded-xl p-2 backdrop-blur-sm">
                    <span class="text-[10px] text-blue-300 font-bold block">🌊 Cuci</span>
                    <span class="text-base font-black">{{ $stats['washing_count'] }}</span>
                </div>
                <div class="bg-white/10 rounded-xl p-2 backdrop-blur-sm">
                    <span class="text-[10px] text-amber-300 font-bold block">♨️ Setrika</span>
                    <span class="text-base font-black">{{ $stats['ironing_count'] }}</span>
                </div>
                <div class="bg-white/10 rounded-xl p-2 backdrop-blur-sm">
                    <span class="text-[10px] text-emerald-300 font-bold block">📦 Pack</span>
                    <span class="text-base font-black">{{ $stats['packing_count'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABBED CONTAINER --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Navigation Tabs --}}
        <div class="flex border-b border-slate-100 px-6 pt-4 gap-6 bg-slate-50/50">
            <button type="button" 
                    @click="activeTab = 'rekap'"
                    :class="activeTab === 'rekap' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                    class="pb-3 text-sm border-b-2 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span>Rekapitulasi Kinerja Petugas</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[11px] bg-blue-100 text-blue-700 font-bold">{{ $rekapPetugas->count() }}</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                    class="pb-3 text-sm border-b-2 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Log Riwayat Tugas Detail</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[11px] bg-slate-200 text-slate-700 font-bold">{{ $taskLogs->count() }}</span>
            </button>
        </div>

        {{-- TAB 1: REKAPITULASI KINERJA PETUGAS --}}
        <div x-show="activeTab === 'rekap'" class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-semibold bg-slate-50/50">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama Petugas / Siswa</th>
                            <th class="py-3 px-4 text-center">Shift & Presensi</th>
                            <th class="py-3 px-4 text-center">🌊 Washing</th>
                            <th class="py-3 px-4 text-center">♨️ Ironing</th>
                            <th class="py-3 px-4 text-center">📦 Packing</th>
                            <th class="py-3 px-4 text-center">💳 Kasir</th>
                            <th class="py-3 px-4 text-center">Total Tugas</th>
                            <th class="py-3 px-4 text-center">Beban (Kg)</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rekapPetugas as $index => $petugas)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-800">{{ $petugas['nama'] }}</div>
                                    <span class="text-[11px] text-slate-400 font-mono">ID: {{ $petugas['id_petugas'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $petugas['shift'] }}
                                    </span>
                                    @if($petugas['checked_in_at'])
                                        <div class="text-[10px] text-emerald-600 font-medium mt-0.5">
                                            Hadir {{ \Carbon\Carbon::parse($petugas['checked_in_at'])->format('H:i') }}
                                        </div>
                                    @else
                                        <div class="text-[10px] text-slate-400 mt-0.5 capitalize">{{ $petugas['status_kehadiran'] }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-blue-600">
                                    {{ $petugas['washing_count'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-amber-600">
                                    {{ $petugas['ironing_count'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-purple-600">
                                    {{ $petugas['packing_count'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-600">
                                    {{ $petugas['kasir_count'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-8 h-7 px-2 rounded-lg text-xs font-black {{ $petugas['total_output'] > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-400' }}">
                                        {{ $petugas['total_output'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                    {{ number_format($petugas['total_weight'], 1) }} kg
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" 
                                            @click="openDetailModal(@js($petugas))"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 text-xs font-semibold rounded-lg transition border border-slate-200 hover:border-blue-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Rincian</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400">
                                    Tidak ada data aktivitas petugas yang cocok dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: LOG RIWAYAT PEKERJAAN HARIAN --}}
        <div x-show="activeTab === 'logs'" class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-semibold bg-slate-50/50">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Waktu Selesai</th>
                            <th class="py-3 px-4">Nama Petugas</th>
                            <th class="py-3 px-4 text-center">Stasiun / Bagian</th>
                            <th class="py-3 px-4">No. Transaksi</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Layanan & Berat</th>
                            <th class="py-3 px-4 text-center">Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($taskLogs as $index => $log)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 font-mono text-xs text-slate-600">
                                    {{ $log['completed_at'] ? \Carbon\Carbon::parse($log['completed_at'])->translatedFormat('d M H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">
                                    {{ $log['petugas_name'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($log['stage'] === 'washing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            🌊 Washing
                                        </span>
                                    @elseif($log['stage'] === 'ironing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ♨️ Ironing
                                        </span>
                                    @elseif($log['stage'] === 'packing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            📦 Packing
                                        </span>
                                    @elseif($log['stage'] === 'kasir')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            💳 Kasir POS
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                                    {{ $log['transaksi_code'] }}
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-700">
                                    {{ $log['customer_name'] }}
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-600">
                                    <span class="font-bold text-slate-800">{{ $log['weight'] ? $log['weight'] . ' kg' : '-' }}</span>
                                    <span class="text-slate-400 block">{{ $log['layanan'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                                        {{ $log['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">
                                    Belum ada log aktivitas pekerjaan yang tercatat pada rentang waktu ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL RINCIAN TUGAS PETUGAS --}}
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[85vh]"
             @click.outside="showDetailModal = false">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800" x-text="activePetugasData?.nama"></h3>
                        <p class="text-xs text-slate-400">
                            Shift: <span class="font-semibold text-slate-600" x-text="activePetugasData?.shift"></span> | 
                            ID: <span class="font-mono text-slate-600" x-text="activePetugasData?.id_petugas"></span>
                        </p>
                    </div>
                </div>
                <button type="button" @click="showDetailModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                    ✕
                </button>
            </div>

            {{-- Summary Badges in Modal --}}
            <div class="grid grid-cols-4 gap-2 py-4 border-b border-slate-100 text-center">
                <div class="bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                    <span class="text-[10px] text-blue-500 font-bold uppercase block">Washing</span>
                    <strong class="text-base font-black text-blue-700" x-text="activePetugasData?.washing_count || 0"></strong>
                </div>
                <div class="bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                    <span class="text-[10px] text-amber-600 font-bold uppercase block">Ironing</span>
                    <strong class="text-base font-black text-amber-700" x-text="activePetugasData?.ironing_count || 0"></strong>
                </div>
                <div class="bg-purple-50 p-2.5 rounded-xl border border-purple-100">
                    <span class="text-[10px] text-purple-600 font-bold uppercase block">Packing</span>
                    <strong class="text-base font-black text-purple-700" x-text="activePetugasData?.packing_count || 0"></strong>
                </div>
                <div class="bg-emerald-50 p-2.5 rounded-xl border border-emerald-100">
                    <span class="text-[10px] text-emerald-600 font-bold uppercase block">Kasir</span>
                    <strong class="text-base font-black text-emerald-700" x-text="activePetugasData?.kasir_count || 0"></strong>
                </div>
            </div>

            {{-- Task List in Modal --}}
            <div class="flex-1 overflow-y-auto py-4 space-y-2.5 pr-1">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Daftar Pekerjaan Yang Diselesaikan</h4>
                
                <template x-if="!activePetugasData?.details || activePetugasData?.details.length === 0">
                    <p class="text-center py-6 text-xs text-slate-400">Belum ada rincian tugas yang diselesaikan pada tanggal ini.</p>
                </template>

                <template x-for="(task, idx) in activePetugasData?.details || []" :key="idx">
                    <div class="p-3 bg-slate-50 hover:bg-blue-50/40 rounded-xl border border-slate-100 transition flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase"
                                  :class="{
                                      'bg-blue-100 text-blue-700': task.stage === 'washing',
                                      'bg-amber-100 text-amber-700': task.stage === 'ironing',
                                      'bg-purple-100 text-purple-700': task.stage === 'packing',
                                      'bg-emerald-100 text-emerald-700': task.stage === 'kasir'
                                  }"
                                  x-text="task.stage">
                            </span>
                            <div>
                                <div class="font-bold text-slate-800" x-text="task.transaksi_code"></div>
                                <div class="text-[11px] text-slate-500">
                                    <span x-text="task.customer_name"></span> &bull; <span class="font-semibold text-slate-700" x-text="task.layanan"></span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-slate-800 block" x-text="task.weight + ' kg'"></span>
                            <span class="text-[10px] text-slate-400 font-mono" x-text="'Jam ' + task.completed_at"></span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Modal Footer --}}
            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="button" @click="showDetailModal = false" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function laporanPetugasApp() {
    return {
        filterMode: @js($filterMode ?? 'harian'),
        activeTab: 'rekap',
        singleDate: @js($selectedDate ?? now()->format('Y-m-d')),
        dariDate: @js($dari ?? now()->startOfMonth()->format('Y-m-d')),
        sampaiDate: @js($sampai ?? now()->format('Y-m-d')),
        showDetailModal: false,
        activePetugasData: null,

        setPreset(type) {
            const today = new Date();
            const formatDate = (d) => d.toISOString().split('T')[0];

            if (type === 'today') {
                this.filterMode = 'harian';
                this.singleDate = formatDate(today);
            } else if (type === 'yesterday') {
                this.filterMode = 'harian';
                const y = new Date(today);
                y.setDate(y.getDate() - 1);
                this.singleDate = formatDate(y);
            } else if (type === 'this_week') {
                this.filterMode = 'rentang';
                const start = new Date(today);
                start.setDate(start.getDate() - 6);
                this.dariDate = formatDate(start);
                this.sampaiDate = formatDate(today);
            } else if (type === 'this_month') {
                this.filterMode = 'rentang';
                const start = new Date(today.getFullYear(), today.getMonth(), 1);
                this.dariDate = formatDate(start);
                this.sampaiDate = formatDate(today);
            }

            this.$nextTick(() => {
                document.getElementById('filterForm').submit();
            });
        },

        openDetailModal(petugas) {
            this.activePetugasData = petugas;
            this.showDetailModal = true;
        }
    };
}
</script>
@endsection
