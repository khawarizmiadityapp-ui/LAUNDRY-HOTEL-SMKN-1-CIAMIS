@extends('layouts.petugas_piket')
@section('title', 'Dashboard Petugas Piket')
@section('content')

<div class="p-6 max-w-7xl mx-auto space-y-8 animate-fade-in" x-data="piketCheckinManager()">
    {{-- Header Section --}}
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/3"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100 uppercase tracking-wider">
                        Jadwal Piket Siswa
                    </span>
                    <span class="text-xs text-slate-400">
                        {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
                    @if($activePiket)
                        Halo, <span class="text-blue-600">{{ $activePiket->nama }}</span>!
                    @else
                        Selamat Datang di Lab Laundry SMKN 1 Ciamis
                    @endif
                </h1>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl mt-1">
                    Silakan pilih nama dan bagian tugas Anda hari ini untuk memulai aktivitas pengerjaan laundry.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                @if($activePiket && $activePiket->selected_station !== 'none')
                    @php
                        $sessionStation = session('active_piket_station');
                        $primaryLabel = $activePiket->selected_station === 'setrika' ? 'Ironing' : ($activePiket->selected_station === 'kasir' ? 'Kasir' : ucfirst($activePiket->selected_station));
                        $coveringLabel = ($sessionStation && $sessionStation !== $activePiket->selected_station)
                            ? ($sessionStation === 'setrika' ? 'Ironing' : ($sessionStation === 'kasir' ? 'Kasir' : ucfirst($sessionStation)))
                            : null;
                    @endphp
                    <div class="px-4 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>
                            Stasiun Utama: {{ $primaryLabel }}
                            @if($coveringLabel)
                                • Rangkap: {{ $coveringLabel }}
                            @endif
                        </span>
                    </div>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 text-xs font-semibold rounded-xl border border-blue-100">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Pilih Stasiun Tugas
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- ALERT MESSAGES --}}
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

    {{-- SECTION: CHECK-IN & PEMILIHAN STASIUN MANDIRI (MODEL 1) --}}
    <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-sm">1</span>
                    Pilih Stasiun Tugas Hari Ini
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pilih nama Anda dari jadwal hari ini, lalu klik stasiun kerja yang ingin Anda ambil.
                </p>
            </div>

            @if($jadwalHariIni->isEmpty())
            <div class="px-3.5 py-1.5 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                Jadwal hari ini belum di-import oleh Admin
            </div>
            @endif
        </div>

        <form action="{{ route('petugas_piket.checkin.station') }}" method="POST" class="space-y-6">
            @csrf

            {{-- 1. Dropdown Nama Siswa --}}
            <div class="max-w-xl">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Siswa / Petugas Piket Hari Ini
                </label>
                @if($jadwalHariIni->isNotEmpty())
                    <select name="jadwal_id" x-model="selectedJadwalId" @change="onSelectJadwal()" required
                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 focus:border-blue-500 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/10 shadow-sm transition">
                        <option value="" disabled>-- Pilih Nama Anda --</option>
                        @foreach($jadwalHariIni as $j)
                            <option value="{{ $j->id }}" {{ ($activePiket && $activePiket->id === $j->id) ? 'selected' : '' }}>
                                {{ $j->nama }} ({{ $j->shift }})
                                @if($j->selected_station !== 'none')
                                    — [🔒 Terkunci: {{ ucfirst($j->selected_station === 'setrika' ? 'Ironing' : ($j->selected_station === 'kasir' ? 'Kasir' : $j->selected_station)) }}]
                                @else
                                    — [Belum Pilih Stasiun]
                                @endif
                            </option>
                        @endforeach
                    </select>

                    {{-- Alert Banner jika stasiun siswa sudah dikunci --}}
                    <div x-show="isLocked && !canBypassLock" x-cloak class="mt-3 p-3.5 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3 text-amber-900 text-xs shadow-sm">
                        <span class="w-8 h-8 rounded-xl bg-amber-200/80 text-amber-800 flex items-center justify-center shrink-0 text-base">🔒</span>
                        <div class="leading-relaxed">
                            <p class="font-bold text-xs sm:text-sm text-amber-950">Stasiun Tugas Telah Terkunci</p>
                            <p class="text-amber-800 text-[11px] mt-0.5">
                                Siswa ini telah memilih stasiun <span class="font-extrabold underline" x-text="lockedStationLabel"></span>
                                <template x-if="currentSelectedPetugas?.checked_in_at">
                                    <span>(check-in pukul <span class="font-mono font-bold" x-text="currentSelectedPetugas.checked_in_at + ' WIB'"></span>)</span>
                                </template>.
                                Pilihan stasiun lain dinonaktifkan demi akuntabilitas tugas. Hubungi Admin/Guru jika butuh pergantian stasiun.
                            </p>
                        </div>
                    </div>

                    {{-- Alert Banner jika akun yang login adalah All-Roles / Admin --}}
                    <div x-show="isLocked && canBypassLock" x-cloak class="mt-3 p-3.5 bg-blue-50 border border-blue-200 rounded-2xl flex items-center gap-3 text-blue-900 text-xs shadow-sm">
                        <span class="w-8 h-8 rounded-xl bg-blue-200 text-blue-800 flex items-center justify-center shrink-0 text-base">⚡</span>
                        <div class="leading-relaxed">
                            <p class="font-bold text-xs sm:text-sm text-blue-950">Mode Akses Penuh — Rangkap Tugas (All-Roles / Admin)</p>
                            <p class="text-blue-800 text-[11px] mt-0.5">
                                Stasiun utama saat ini: <span class="font-extrabold underline" x-text="lockedStationLabel"></span>. Anda dapat memilih stasiun lain di bawah untuk <strong>merangkap tugas</strong> (membantu job lain jika ada petugas yang absen) atau memindahkan stasiun utama siswa ini.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-slate-100 rounded-xl text-xs text-slate-500 font-medium">
                        Tidak ada siswa yang terjadwal piket di database untuk hari ini. Hubungi admin untuk import jadwal Excel.
                    </div>
                @endif
            </div>

            {{-- 2. Kartu Pilihan Stasiun Tugas --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Pilih Stasiun Tugas Kerja
                    </label>
                    <template x-if="isLocked && !canBypassLock">
                        <span class="text-[11px] font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-lg flex items-center gap-1">
                            <span>🔒</span> Stasiun Tidak Dapat Diubah
                        </span>
                    </template>
                    <template x-if="isLocked && canBypassLock">
                        <span class="text-[11px] font-bold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-lg flex items-center gap-1">
                            <span>⚡</span> Akses All-Roles (Bisa Rangkap / Ganti Stasiun)
                        </span>
                    </template>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Station 1: Washing --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="washing" x-model="selectedStation" :disabled="isLocked && !canBypassLock && selectedStation !== 'washing'" class="sr-only">
                        <div :class="isLocked && !canBypassLock && selectedStation !== 'washing'
                                ? 'opacity-40 cursor-not-allowed bg-slate-100/70 border-slate-200'
                                : (selectedStation === 'washing' ? 'border-blue-600 bg-blue-50/70 ring-2 ring-blue-600 ring-offset-2 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 shadow-sm')"
                             class="p-4 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">🌊</span>
                                <div class="flex items-center gap-1.5">
                                    <template x-if="isLocked && currentSelectedPetugas?.station === 'washing'">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">🔒 Utama</span>
                                    </template>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                          :class="selectedStation === 'washing' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300'">
                                        <template x-if="selectedStation === 'washing'">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Washing / Cuci</h3>
                                <p class="text-xs text-slate-500 mt-1">Timbang cucian, mencuci dengan deterjen, dan mesin cuci.</p>
                            </div>
                        </div>
                    </label>

                    {{-- Station 2: Ironing --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="setrika" x-model="selectedStation" :disabled="isLocked && !canBypassLock && selectedStation !== 'setrika'" class="sr-only">
                        <div :class="isLocked && !canBypassLock && selectedStation !== 'setrika'
                                ? 'opacity-40 cursor-not-allowed bg-slate-100/70 border-slate-200'
                                : (selectedStation === 'setrika' ? 'border-amber-600 bg-amber-50/70 ring-2 ring-amber-600 ring-offset-2 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 shadow-sm')"
                             class="p-4 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">♨️</span>
                                <div class="flex items-center gap-1.5">
                                    <template x-if="isLocked && currentSelectedPetugas?.station === 'setrika'">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">🔒 Utama</span>
                                    </template>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                          :class="selectedStation === 'setrika' ? 'border-amber-600 bg-amber-600 text-white' : 'border-slate-300'">
                                        <template x-if="selectedStation === 'setrika'">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Ironing / Setrika</h3>
                                <p class="text-xs text-slate-500 mt-1">Proses ironing, pelicin pakaian, dan merapikan cucian.</p>
                            </div>
                        </div>
                    </label>

                    {{-- Station 3: Packing --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="packing" x-model="selectedStation" :disabled="isLocked && !canBypassLock && selectedStation !== 'packing'" class="sr-only">
                        <div :class="isLocked && !canBypassLock && selectedStation !== 'packing'
                                ? 'opacity-40 cursor-not-allowed bg-slate-100/70 border-slate-200'
                                : (selectedStation === 'packing' ? 'border-purple-600 bg-purple-50/70 ring-2 ring-purple-600 ring-offset-2 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 shadow-sm')"
                             class="p-4 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">📦</span>
                                <div class="flex items-center gap-1.5">
                                    <template x-if="isLocked && currentSelectedPetugas?.station === 'packing'">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">🔒 Utama</span>
                                    </template>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                          :class="selectedStation === 'packing' ? 'border-purple-600 bg-purple-600 text-white' : 'border-slate-300'">
                                        <template x-if="selectedStation === 'packing'">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Packing & Quality</h3>
                                <p class="text-xs text-slate-500 mt-1">Bungkus rapi, tempel nota/label, siap diambil pelanggan.</p>
                            </div>
                        </div>
                    </label>

                    {{-- Station 4: Kasir / POS --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="kasir" x-model="selectedStation" :disabled="isLocked && !canBypassLock && selectedStation !== 'kasir'" class="sr-only">
                        <div :class="isLocked && !canBypassLock && selectedStation !== 'kasir'
                                ? 'opacity-40 cursor-not-allowed bg-slate-100/70 border-slate-200'
                                : (selectedStation === 'kasir' ? 'border-emerald-600 bg-emerald-50/70 ring-2 ring-emerald-600 ring-offset-2 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 shadow-sm')"
                             class="p-4 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">🏪</span>
                                <div class="flex items-center gap-1.5">
                                    <template x-if="isLocked && currentSelectedPetugas?.station === 'kasir'">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">🔒 Utama</span>
                                    </template>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                          :class="selectedStation === 'kasir' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300'">
                                        <template x-if="selectedStation === 'kasir'">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Kasir (POS)</h3>
                                <p class="text-xs text-slate-500 mt-1">Input order baru, kasir pembayaran, dan cetak nota.</p>
                            </div>
                        </div>
                    </label>

                    {{-- Station 5: Inventory --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="inventory" x-model="selectedStation" :disabled="isLocked && !canBypassLock && selectedStation !== 'inventory'" class="sr-only">
                        <div :class="isLocked && !canBypassLock && selectedStation !== 'inventory'
                                ? 'opacity-40 cursor-not-allowed bg-slate-100/70 border-slate-200'
                                : (selectedStation === 'inventory' ? 'border-indigo-600 bg-indigo-50/70 ring-2 ring-indigo-600 ring-offset-2 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 shadow-sm')"
                             class="p-4 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">📋</span>
                                <div class="flex items-center gap-1.5">
                                    <template x-if="isLocked && currentSelectedPetugas?.station === 'inventory'">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">🔒 Utama</span>
                                    </template>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                          :class="selectedStation === 'inventory' ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300'">
                                        <template x-if="selectedStation === 'inventory'">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Inventory / Gudang</h3>
                                <p class="text-xs text-slate-500 mt-1">Stok deterjen, pewangi, plastik, dan logistik bahan.</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Opsi Mode Rangkap vs Pindah (Khusus All-Roles / Admin saat memilih stasiun berbeda) --}}
            <div x-show="isLocked && canBypassLock && selectedStation !== currentSelectedPetugas?.station" x-cloak
                 class="p-4 bg-blue-50/70 border border-blue-200 rounded-2xl space-y-2.5">
                <p class="text-xs font-bold text-blue-900 uppercase tracking-wider">Pilih Mode Aksi All-Roles:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="cursor-pointer flex items-start gap-2.5 p-3 rounded-xl border-2 transition"
                           :class="actionMode === 'cover' ? 'bg-white border-blue-600 shadow-sm' : 'bg-white/60 border-slate-200 hover:bg-white'">
                        <input type="radio" name="action_mode" value="cover" x-model="actionMode" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Rangkap Tugas (Multi-Job)</span>
                            <span class="text-[11px] text-slate-500">Masuk membantu stasiun ini tanpa menghapus stasiun utama siswa di jadwal piket.</span>
                        </div>
                    </label>
                    <label class="cursor-pointer flex items-start gap-2.5 p-3 rounded-xl border-2 transition"
                           :class="actionMode === 'move' ? 'bg-white border-amber-500 shadow-sm' : 'bg-white/60 border-slate-200 hover:bg-white'">
                        <input type="radio" name="action_mode" value="move" x-model="actionMode" class="mt-0.5 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Pindahkan Stasiun Utama</span>
                            <span class="text-[11px] text-slate-500">Ubah stasiun utama siswa pada Daftar Rekan Piket hari ini ke stasiun pilihan.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 3. Tombol Check-In / Mulai Bertugas --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span x-show="!isLocked">Pilihan stasiun akan <strong>dikunci permanen</strong> untuk siswa setelah Anda klik Mulai Bertugas.</span>
                    <span x-show="isLocked && !canBypassLock" class="text-amber-800 font-semibold">Stasiun terkunci. Klik tombol untuk langsung menuju workspace stasiun Anda.</span>
                    <span x-show="isLocked && canBypassLock" class="text-blue-800 font-semibold">Mode All-Roles aktif. Anda dapat langsung merangkap job lain atau pindah lewat menu sidebar.</span>
                </div>
                <button type="submit" :disabled="!selectedJadwalId || !selectedStation"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 text-white text-sm font-bold rounded-2xl transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="isLocked ? (canBypassLock ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/20' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20') : 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/20'">
                    <span x-text="isLocked ? (canBypassLock ? (selectedStation === currentSelectedPetugas?.station ? 'Masuk ke Stasiun Ini' : (actionMode === 'cover' ? 'Masuk & Rangkap Tugas Ini' : 'Pindahkan & Masuk Stasiun')) : 'Masuk ke Stasiun Saya') : 'Mulai Bertugas & Kunci Stasiun'"></span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    {{-- SECTION: ROSTER / REKAP TEMAN PIKET HARI INI --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Rekan Piket Hari Ini</h3>
                <p class="text-xs text-slate-400">Pembagian stasiun kerja siswa pada shift hari ini</p>
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs">
                Total: {{ $jadwalHariIni->count() }} Siswa
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            {{-- Stasiun Cuci --}}
            <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-blue-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🌊</span> Washing
                    </span>
                    <span class="px-2 py-0.5 bg-blue-200 text-blue-800 rounded-lg text-xs font-black">
                        {{ $jadwalHariIni->where('selected_station', 'washing')->count() }}
                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    @forelse($jadwalHariIni->where('selected_station', 'washing') as $w)
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-blue-100/60 font-semibold">
                            <span>{{ $w->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $w->shift }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    @endforelse
                </ul>
            </div>

            {{-- Stasiun Ironing --}}
            <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>♨️</span> Ironing
                    </span>
                    <span class="px-2 py-0.5 bg-amber-200 text-amber-800 rounded-lg text-xs font-black">
                        {{ $jadwalHariIni->where('selected_station', 'setrika')->count() }}
                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    @forelse($jadwalHariIni->where('selected_station', 'setrika') as $s)
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-amber-100/60 font-semibold">
                            <span>{{ $s->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $s->shift }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    @endforelse
                </ul>
            </div>

            {{-- Stasiun Packing --}}
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-purple-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📦</span> Packing
                    </span>
                    <span class="px-2 py-0.5 bg-purple-200 text-purple-800 rounded-lg text-xs font-black">
                        {{ $jadwalHariIni->where('selected_station', 'packing')->count() }}
                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    @forelse($jadwalHariIni->where('selected_station', 'packing') as $p)
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-purple-100/60 font-semibold">
                            <span>{{ $p->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $p->shift }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    @endforelse
                </ul>
            </div>

            {{-- Stasiun Kasir / POS --}}
            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🏪</span> Kasir / CS
                    </span>
                    <span class="px-2 py-0.5 bg-emerald-200 text-emerald-800 rounded-lg text-xs font-black">
                        {{ $jadwalHariIni->where('selected_station', 'kasir')->count() }}
                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    @forelse($jadwalHariIni->where('selected_station', 'kasir') as $k)
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-emerald-100/60 font-semibold">
                            <span>{{ $k->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $k->shift }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    @endforelse
                </ul>
            </div>

            {{-- Stasiun Inventory --}}
            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-indigo-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📋</span> Inventory
                    </span>
                    <span class="px-2 py-0.5 bg-indigo-200 text-indigo-800 rounded-lg text-xs font-black">
                        {{ $jadwalHariIni->where('selected_station', 'inventory')->count() }}
                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    @forelse($jadwalHariIni->where('selected_station', 'inventory') as $iv)
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-indigo-100/60 font-semibold">
                            <span>{{ $iv->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $iv->shift }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function piketCheckinManager() {
        const roster = {{ \Illuminate\Support\Js::from($rosterJson ?? []) }};

        const initialJadwalId = '{{ $activePiket ? (string) $activePiket->id : "" }}';
        const initialJadwal = roster.find(r => r.id === initialJadwalId);
        const sessionStation = @json(session('active_piket_station') ?? '');
        const initialStation = (initialJadwalId && sessionStation)
            ? sessionStation
            : ((initialJadwal && initialJadwal.station !== 'none')
                ? initialJadwal.station
                : '{{ ($activePiket && $activePiket->selected_station !== "none") ? $activePiket->selected_station : "washing" }}');

        return {
            roster: roster,
            canBypassLock: {{ ($canBypassLock ?? false) ? 'true' : 'false' }},
            selectedJadwalId: initialJadwalId,
            selectedStation: initialStation,
            actionMode: 'cover',

            get currentSelectedPetugas() {
                if (!this.selectedJadwalId) return null;
                return this.roster.find(r => r.id === String(this.selectedJadwalId)) || null;
            },

            get isLocked() {
                return !!(this.currentSelectedPetugas && this.currentSelectedPetugas.station && this.currentSelectedPetugas.station !== 'none');
            },

            get lockedStationLabel() {
                if (!this.isLocked) return '';
                const map = {
                    'washing': 'Washing / Cuci',
                    'setrika': 'Ironing / Setrika',
                    'packing': 'Packing & Quality',
                    'kasir': 'Kasir (POS / CS)',
                    'inventory': 'Inventory / Gudang',
                };
                return map[this.currentSelectedPetugas.station] || this.currentSelectedPetugas.station;
            },

            onSelectJadwal() {
                this.actionMode = 'cover';
                const petugas = this.currentSelectedPetugas;
                if (petugas && petugas.station && petugas.station !== 'none') {
                    this.selectedStation = petugas.station;
                }
            }
        };
    }
</script>
@endpush