{{-- resources/views/admin/pembayaran/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Manajemen & Rekapan Pembayaran - Bening Laundry')
@section('content')

<div class="p-6 md:p-8" x-data="pembayaranApp()">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen & Rekapan Pembayaran</h1>
                <p class="text-slate-500 text-xs md:text-sm mt-0.5">Kelola pelunasan transaksi, rekapan harian & bulanan beserta rincian pelayanan laundry.</p>
            </div>

            {{-- Tab Switcher --}}
            <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 self-start md:self-auto">
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg transition {{ $tab === 'daftar' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm0 5.25h.007v.008H3.75V12zm0 5.25h.007v.008H3.75v-.008z" />
                    </svg>
                    <span>Daftar Transaksi</span>
                </a>
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'harian']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg transition {{ $tab === 'harian' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <span>Rekapan Harian</span>
                </a>
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'bulanan']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg transition {{ $tab === 'bulanan' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9 3.75h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                    <span>Rekapan Bulanan</span>
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- TAB 1: DAFTAR TRANSAKSI & STATUS PEMBAYARAN                         --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        @if($tab === 'daftar')
            {{-- STATISTIK & REKAPAN HARI INI CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                {{-- Card 1: Total Hari Ini --}}
                <div class="bg-white rounded-2xl shadow-card p-5 border-l-4 border-blue-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-[11px] uppercase font-bold">Total Masuk Hari Ini</p>
                            <p class="text-xl font-bold text-slate-900 mt-1">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 border-t border-slate-100 pt-2 flex justify-between">
                        <span>{{ $rekapHariIni['total_transaksi'] ?? 0 }} transaksi lunas</span>
                        <a href="{{ route('admin.pembayaran.index', ['tab' => 'harian']) }}" class="text-blue-600 font-bold hover:underline">Rekap &rarr;</a>
                    </div>
                </div>

                {{-- Card 2: Tunai Hari Ini --}}
                <div class="bg-white rounded-2xl shadow-card p-5 border-l-4 border-emerald-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-[11px] uppercase font-bold">Tunai (Cash) Hari Ini</p>
                            <p class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($rekapHariIni['tunai_nominal'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 border-t border-slate-100 pt-2">
                        <span>{{ $rekapHariIni['tunai_count'] ?? 0 }} order ({{ $rekapHariIni['tunai_persen'] ?? 0 }}%)</span>
                    </div>
                </div>

                {{-- Card 3: QRIS Hari Ini --}}
                <div class="bg-white rounded-2xl shadow-card p-5 border-l-4 border-indigo-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-[11px] uppercase font-bold">QRIS Hari Ini</p>
                            <p class="text-xl font-bold text-indigo-600 mt-1">Rp {{ number_format($rekapHariIni['qris_nominal'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.008v.008H6.75V6.75zm0 9.75h.008v.008H6.75v-.008zm9.75-9.75h.008v.008H16.5V6.75zM13.5 13.5h3v3h-3v-3zm3 3h3v3h-3v-3zm-3 3h3v3h-3v-3zm3-6h3v3h-3v-3z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 border-t border-slate-100 pt-2">
                        <span>{{ $rekapHariIni['qris_count'] ?? 0 }} order ({{ $rekapHariIni['qris_persen'] ?? 0 }}%)</span>
                    </div>
                </div>

                {{-- Card 4: Transfer Hari Ini --}}
                <div class="bg-white rounded-2xl shadow-card p-5 border-l-4 border-purple-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-[11px] uppercase font-bold">Transfer / E-Wallet</p>
                            <p class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($rekapHariIni['transfer_nominal'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5H4.5V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 border-t border-slate-100 pt-2">
                        <span>{{ $rekapHariIni['transfer_count'] ?? 0 }} order ({{ $rekapHariIni['transfer_persen'] ?? 0 }}%)</span>
                    </div>
                </div>

                {{-- Card 5: Belum Lunas (Piutang) --}}
                <div class="bg-white rounded-2xl shadow-card p-5 border-l-4 border-rose-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-[11px] uppercase font-bold">Belum Lunas (Piutang)</p>
                            <p class="text-xl font-bold text-rose-600 mt-1">Rp {{ number_format($nominalBelumLunas ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-[11px] text-slate-500 border-t border-slate-100 pt-2">
                        <span>{{ $transaksiBelumLunas }} transaksi</span>
                        <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => 'belum_bayar']) }}" class="text-rose-600 font-bold hover:underline">
                            Filter &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- FILTER TABS & SEARCH --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'metode' => request('metode')]) }}" 
                       class="px-3.5 py-2 text-xs font-semibold rounded-xl transition {{ !request('status') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua Status
                    </a>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => 'lunas', 'metode' => request('metode')]) }}" 
                       class="px-3.5 py-2 text-xs font-semibold rounded-xl transition {{ request('status') == 'lunas' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Lunas
                    </a>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => 'belum_bayar', 'metode' => request('metode')]) }}" 
                       class="px-3.5 py-2 text-xs font-semibold rounded-xl transition {{ request('status') == 'belum_bayar' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Belum Lunas
                    </a>

                    <span class="h-5 w-px bg-slate-200 mx-1"></span>

                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => request('status')]) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-xl transition {{ !request('metode') ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua Metode
                    </a>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => request('status'), 'metode' => 'tunai']) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-xl transition {{ request('metode') == 'tunai' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        💵 Tunai
                    </a>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => request('status'), 'metode' => 'qris']) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-xl transition {{ request('metode') == 'qris' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        📱 QRIS
                    </a>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => request('status'), 'metode' => 'transfer']) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-xl transition {{ request('metode') == 'transfer' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        🏦 Transfer
                    </a>
                </div>

                <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="daftar">
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    @if(request('metode')) <input type="hidden" name="metode" value="{{ request('metode') }}"> @endif
                    <div class="relative w-64">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari pelanggan / kode..."
                               class="w-full pl-9 pr-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-9">
                    </div>
                </form>
            </div>

            {{-- TABEL TRANSAKSI --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="overflow-x-auto min-h-[280px]">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">ID Transaksi</th>
                                <th class="px-6 py-4">Pelanggan & Rincian Layanan</th>
                                <th class="px-6 py-4">Total Tagihan</th>
                                <th class="px-6 py-4">Metode Bayar</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-xs text-slate-900">
                                    {{ $trx->transaksi_code }}
                                    <div class="text-[10px] text-slate-400 font-sans mt-0.5">{{ $trx->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $trx->customer_name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        @if($trx->details && $trx->details->count() > 0)
                                             {{ $trx->details->map(fn($d) => ($d->layanan->nama ?? 'Layanan') . ' (' . $d->qty . 'x)')->join(', ') }}
                                        @else
                                            {{ ucfirst($trx->service_type) }} - {{ $trx->weight }} kg
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900 font-mono">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                    @if($trx->dibayar > 0)
                                        <div class="text-[10px] text-slate-400 font-sans mt-0.5">
                                            Bayar: Rp {{ number_format($trx->dibayar, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap uppercase text-xs font-semibold text-slate-600">
                                    {{ str_replace('_', ' ', $trx->payment_method ?: 'Tunai') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($trx->payment_status == 'lunas')
                                        <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                            <span>Lunas</span>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-lg bg-rose-100 text-rose-800 border border-rose-200">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Belum Lunas</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="relative inline-block text-left">
                                        <button type="button" 
                                                onclick="toggleDropdown('dropdown-bayar-{{ $trx->id }}')" 
                                                class="w-8 h-8 inline-flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition focus:outline-none"
                                                title="Menu Aksi">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                                            </svg>
                                        </button>
                                        
                                        <div id="dropdown-bayar-{{ $trx->id }}" 
                                             class="hidden absolute right-0 top-full mt-1 w-44 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5 text-left">
                                            @if($trx->payment_status != 'lunas')
                                            <button type="button" 
                                                    onclick="openPaymentModal('{{ $trx->transaksi_code }}', {{ $trx->total_price }}); document.getElementById('dropdown-bayar-{{ $trx->id }}').classList.add('hidden')"
                                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 transition-colors">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                                </svg>
                                                <span>Bayar Transaksi</span>
                                            </button>
                                            <div class="h-px bg-slate-100 my-1"></div>
                                            @endif

                                            <button type="button" 
                                                    @click="openNota('{{ route('pos.nota', $trx->id) }}'); document.getElementById('dropdown-bayar-{{ $trx->id }}').classList.add('hidden')"
                                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                <span>Cetak Struk / Nota</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada data transaksi ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $transactions->links('vendor.pagination.custom') }}
                </div>
            </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- TAB 2: REKAPAN HARIAN & DETAIL PELAYANAN                             --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        @elseif($tab === 'harian')
            {{-- DATE FILTER & PRINT BAR --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="tab" value="harian">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pilih Tanggal:</label>
                        <input type="date" name="tanggal" value="{{ $tanggalHarian }}" onchange="this.form.submit()"
                               class="text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                    </div>
                </form>

                <a href="{{ route('admin.pembayaran.rekap_harian.pdf', ['tanggal' => $tanggalHarian]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md transition active:scale-95">
                    <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Cetak Rekapan Harian (PDF)</span>
                </a>
            </div>

            {{-- STATISTIK HARIAN CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Omzet Hari Ini</p>
                    <p class="text-xl font-bold text-slate-900 mt-1">Rp {{ number_format($harianTotalPendapatan, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($tanggalHarian)->translatedFormat('d F Y') }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Transaksi Lunas</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">{{ $harianTotalTransaksi }} Order</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Belum bayar: {{ $harianRekapMetode['piutang_count'] ?? 0 }} order</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tunai (Cash)</p>
                    <p class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($harianTotalTunai, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $harianRekapMetode['tunai_count'] ?? 0 }} transaksi ({{ $harianRekapMetode['tunai_persen'] ?? 0 }}%)</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pembayaran QRIS</p>
                    <p class="text-xl font-bold text-indigo-600 mt-1">Rp {{ number_format($harianTotalQris ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $harianRekapMetode['qris_count'] ?? 0 }} transaksi ({{ $harianRekapMetode['qris_persen'] ?? 0 }}%)</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Transfer / E-Wallet</p>
                    <p class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($harianTotalTransfer ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $harianRekapMetode['transfer_count'] ?? 0 }} transaksi ({{ $harianRekapMetode['transfer_persen'] ?? 0 }}%)</p>
                </div>
            </div>

            {{-- REKAPAN METODE PEMBAYARAN HARIAN --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rekapan Metode Pembayaran &amp; Status Pelunasan ({{ \Carbon\Carbon::parse($tanggalHarian)->translatedFormat('d F Y') }})</h2>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Metode Pembayaran</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Jumlah Transaksi</th>
                                <th class="px-6 py-3">Total Nominal (Rp)</th>
                                <th class="px-6 py-3">Persentase Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($harianRekapMetode['methods'] ?? [] as $m)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 font-bold text-slate-900">{{ $m['label'] }}</td>
                                <td class="px-6 py-3.5"><span class="px-2 py-0.5 text-[11px] font-bold rounded bg-emerald-100 text-emerald-700">Lunas</span></td>
                                <td class="px-6 py-3.5 font-semibold">{{ $m['count'] }} transaksi</td>
                                <td class="px-6 py-3.5 font-mono font-bold text-slate-900">Rp {{ number_format($m['total'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $m['persen'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $m['persen'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-slate-50 font-bold text-slate-900">
                                <td class="px-6 py-3.5">TOTAL PENERIMAAN LUNAS</td>
                                <td class="px-6 py-3.5"><span class="px-2 py-0.5 text-[11px] font-bold rounded bg-blue-100 text-blue-700">Total Kas Masuk</span></td>
                                <td class="px-6 py-3.5">{{ $harianTotalTransaksi }} transaksi</td>
                                <td class="px-6 py-3.5 font-mono text-blue-700">Rp {{ number_format($harianTotalPendapatan, 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5">100%</td>
                            </tr>
                            @if(($harianRekapMetode['piutang_count'] ?? 0) > 0)
                            <tr class="bg-rose-50/50 text-rose-700 font-semibold">
                                <td class="px-6 py-3">Transaksi Belum Dibayar (Piutang Hari Ini)</td>
                                <td class="px-6 py-3"><span class="px-2 py-0.5 text-[11px] font-bold rounded bg-rose-100 text-rose-700">Belum Lunas</span></td>
                                <td class="px-6 py-3">{{ $harianRekapMetode['piutang_count'] }} transaksi</td>
                                <td class="px-6 py-3 font-mono font-bold">Rp {{ number_format($harianRekapMetode['piutang_nominal'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-xs">Menunggu Pelunasan</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 1. RINCIAN BREAKDOWN PELAYANAN --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rincian Performa Jenis Pelayanan (Hari Ini)</h2>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Nama Layanan Laundry</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Total Qty / Berat</th>
                                <th class="px-6 py-3">Jumlah Transaksi</th>
                                <th class="px-6 py-3">Total Pendapatan</th>
                                <th class="px-6 py-3">Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($harianServiceBreakdown as $srv)
                            @php
                                $percent = $harianTotalPendapatan > 0 ? round(($srv['total'] / $harianTotalPendapatan) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 font-bold text-slate-900">{{ $srv['nama'] }}</td>
                                <td class="px-6 py-3.5 whitespace-nowrap uppercase text-xs text-slate-600 font-semibold">{{ $srv['kategori'] }}</td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-800">
                                    {{ $srv['qty'] }} {{ $srv['kategori'] == 'kiloan' ? 'kg' : 'pcs' }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-xs font-semibold text-slate-600">{{ $srv['count'] }}x order</td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp {{ number_format($srv['total'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi layanan pada tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. TABEL TRANSAKSI PADA HARI TERSEBUT --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Daftar Transaksi Pelanggan ({{ $tanggalHarian }})</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">No. Transaksi</th>
                                <th class="px-6 py-3">Pelanggan</th>
                                <th class="px-6 py-3">Rincian Layanan</th>
                                <th class="px-6 py-3">Metode Bayar</th>
                                <th class="px-6 py-3">Nominal</th>
                                <th class="px-6 py-3 text-right">Struk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($harianTrx as $trx)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-bold text-xs text-slate-900">{{ $trx->transaksi_code }}</td>
                                <td class="px-6 py-3 whitespace-nowrap font-semibold text-slate-900">{{ $trx->customer_name }}</td>
                                <td class="px-6 py-3 text-xs text-slate-600">
                                    @if($trx->details && $trx->details->count() > 0)
                                        {{ $trx->details->map(fn($d) => ($d->layanan->nama ?? 'Layanan') . ' (' . $d->qty . 'x)')->join(', ') }}
                                    @else
                                        {{ ucfirst($trx->service_type) }} ({{ $trx->weight }} kg)
                                    @endif
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap uppercase text-xs font-semibold text-slate-600">
                                    {{ str_replace('_', ' ', $trx->payment_method ?: 'Tunai') }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <button type="button" @click="openNota('{{ route('pos.nota', $trx->id) }}')"
                                            class="inline-flex items-center px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8h4a1 1 0 011 1v0a1 1 0 01-1 1h-4a1 1 0 01-1-1v0a1 1 0 011-1z" /></svg> Nota
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada transaksi pada tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- TAB 3: REKAPAN BULANAN                                              --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        @elseif($tab === 'bulanan')
            {{-- MONTH FILTER & PRINT BAR --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="tab" value="bulanan">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pilih Bulan & Tahun:</label>
                        <select name="bulan" onchange="this.form.submit()"
                                class="text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $bulanBulanan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <select name="tahun" onchange="this.form.submit()"
                                class="text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                            @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $tahunBulanan == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </form>

                <a href="{{ route('admin.pembayaran.rekap_bulanan.pdf', ['bulan' => $bulanBulanan, 'tahun' => $tahunBulanan]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md transition active:scale-95">
                    <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Cetak Rekapan Bulanan (PDF)
                </a>
            </div>

            {{-- STATISTIK BULANAN CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Omzet Bulan Ini</p>
                    <p class="text-xl font-bold text-slate-900 mt-1">Rp {{ number_format($bulananTotalPendapatan, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $bulananTotalTransaksi }} order lunas ({{ \Carbon\Carbon::create($tahunBulanan, $bulanBulanan, 1)->translatedFormat('F Y') }})</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tunai (Cash)</p>
                    <p class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($bulananTotalTunai, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $bulananRekapMetode['tunai_count'] ?? 0 }} order ({{ $bulananRekapMetode['tunai_persen'] ?? 0 }}%)</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pembayaran QRIS</p>
                    <p class="text-xl font-bold text-indigo-600 mt-1">Rp {{ number_format($bulananTotalQris ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $bulananRekapMetode['qris_count'] ?? 0 }} order ({{ $bulananRekapMetode['qris_persen'] ?? 0 }}%)</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Transfer / E-Wallet</p>
                    <p class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($bulananTotalTransfer ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $bulananRekapMetode['transfer_count'] ?? 0 }} order ({{ $bulananRekapMetode['transfer_persen'] ?? 0 }}%)</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-rata / Hari</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($rataRataHarian, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Terlaris: {{ $layananTerlaris }}</p>
                </div>
            </div>

            {{-- REKAPAN METODE PEMBAYARAN BULANAN --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rekapan Metode Pembayaran Bulanan ({{ \Carbon\Carbon::create($tahunBulanan, $bulanBulanan, 1)->translatedFormat('F Y') }})</h2>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Metode Pembayaran</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Total Order</th>
                                <th class="px-6 py-3">Total Penerimaan (Rp)</th>
                                <th class="px-6 py-3">Porsi %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($bulananRekapMetode['methods'] ?? [] as $m)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 font-bold text-slate-900">{{ $m['label'] }}</td>
                                <td class="px-6 py-3.5"><span class="px-2 py-0.5 text-[11px] font-bold rounded bg-emerald-100 text-emerald-700">Lunas</span></td>
                                <td class="px-6 py-3.5 font-semibold">{{ $m['count'] }} transaksi</td>
                                <td class="px-6 py-3.5 font-mono font-bold text-slate-900">Rp {{ number_format($m['total'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $m['persen'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $m['persen'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-slate-50 font-bold text-slate-900">
                                <td class="px-6 py-3.5">TOTAL OMZET LUNAS BULAN INI</td>
                                <td class="px-6 py-3.5"><span class="px-2 py-0.5 text-[11px] font-bold rounded bg-blue-100 text-blue-700">Total Kas Masuk</span></td>
                                <td class="px-6 py-3.5">{{ $bulananTotalTransaksi }} transaksi</td>
                                <td class="px-6 py-3.5 font-mono text-blue-700">Rp {{ number_format($bulananTotalPendapatan, 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5">100%</td>
                            </tr>
                            @if(($bulananRekapMetode['piutang_count'] ?? 0) > 0)
                            <tr class="bg-rose-50/50 text-rose-700 font-semibold">
                                <td class="px-6 py-3">Piutang / Belum Lunas Bulan Ini</td>
                                <td class="px-6 py-3"><span class="px-2 py-0.5 text-[11px] font-bold rounded bg-rose-100 text-rose-700">Belum Lunas</span></td>
                                <td class="px-6 py-3">{{ $bulananRekapMetode['piutang_count'] }} transaksi</td>
                                <td class="px-6 py-3 font-mono font-bold">Rp {{ number_format($bulananRekapMetode['piutang_nominal'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-xs">Menunggu Pelunasan</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 1. RINCIAN PERFORMA LAYANAN BULANAN --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">1. Ringkasan Performa Seluruh Jenis Layanan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Nama Layanan Laundry</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Total Volume (Kg/Pcs)</th>
                                <th class="px-6 py-3">Total Order</th>
                                <th class="px-6 py-3">Total Omzet</th>
                                <th class="px-6 py-3">Porsi %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($bulananServiceBreakdown as $srv)
                            @php
                                $percent = $bulananTotalPendapatan > 0 ? round(($srv['total'] / $bulananTotalPendapatan) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 font-bold text-slate-900">{{ $srv['nama'] }}</td>
                                <td class="px-6 py-3.5 whitespace-nowrap uppercase text-xs font-semibold text-slate-600">{{ $srv['kategori'] }}</td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-800">
                                    {{ $srv['qty'] }} {{ $srv['kategori'] == 'kiloan' ? 'kg' : 'pcs' }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-xs font-semibold text-slate-600">{{ $srv['count'] }}x</td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp {{ number_format($srv['total'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi layanan pada bulan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. TABEL REKAPAN OMZET HARIAN (Harian 1 s.d. 31) --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">2. Rincian Penerimaan Kas Harian (1 - {{ count($dailyBreakdown) }})</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Jumlah Transaksi</th>
                                <th class="px-6 py-3">Tunai (Cash)</th>
                                <th class="px-6 py-3">QRIS</th>
                                <th class="px-6 py-3">Transfer</th>
                                <th class="px-6 py-3">Total Omzet Harian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($dailyBreakdown as $day)
                            <tr class="hover:bg-slate-50/70 transition {{ $day['total'] > 0 ? '' : 'text-slate-400' }}">
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-semibold">{{ $day['tanggal'] }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ $day['hari'] }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ $day['count'] > 0 ? $day['count'] . ' order' : '-' }}</td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono text-emerald-700">
                                    {{ $day['tunai'] > 0 ? 'Rp ' . number_format($day['tunai'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono text-indigo-700">
                                    {{ ($day['qris'] ?? 0) > 0 ? 'Rp ' . number_format($day['qris'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono text-purple-700">
                                    {{ ($day['transfer'] ?? 0) > 0 ? 'Rp ' . number_format($day['transfer'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-bold {{ $day['total'] > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $day['total'] > 0 ? 'Rp ' . number_format($day['total'], 0, ',', '.') : 'Rp 0' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-100 font-bold text-slate-900 text-sm">
                            <tr>
                                <td colspan="2" class="px-6 py-3.5 uppercase">TOTAL REKAP BULANAN</td>
                                <td class="px-6 py-3.5">{{ $bulananTotalTransaksi }} order</td>
                                <td class="px-6 py-3.5 font-mono text-emerald-700">Rp {{ number_format($bulananTotalTunai, 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5 font-mono text-indigo-700">Rp {{ number_format($bulananTotalQris ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5 font-mono text-purple-700">Rp {{ number_format($bulananTotalTransfer ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5 font-mono text-blue-700">Rp {{ number_format($bulananTotalPendapatan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif

    </div>

    {{-- ═══════════ PAYMENT MODAL (UNTUK PELUNASAN KASIR) ═══════════ --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
        <div class="relative mx-auto p-6 border w-full max-w-md shadow-2xl rounded-2xl bg-white animate-fade-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-slate-900">Proses Pelunasan Pembayaran</h3>
                <button onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="paymentForm" action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="transaksi_id" id="modal_transaksi_id">
                <input type="hidden" name="tanggal_bayar" value="{{ date('Y-m-d') }}">
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">ID Transaksi</label>
                    <input type="text" id="modal_transaksi_code_display" class="w-full border border-slate-200 rounded-xl bg-slate-50 p-2.5 text-xs font-mono font-bold" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Tagihan</label>
                    <div class="flex items-center">
                        <span class="bg-slate-100 border border-slate-200 border-r-0 px-3 py-2.5 rounded-l-xl text-slate-500 font-bold text-xs">Rp</span>
                        <input type="text" id="modal_total_price_display" class="w-full border border-slate-200 rounded-r-xl bg-slate-50 p-2.5 text-xs font-bold text-slate-900" readonly>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Uang Pelanggan (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_bayar" id="modal_jumlah_bayar" required min="0"
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="metode_pembayaran" id="modal_metode_pembayaran" onchange="onModalPaymentMethodChange(this.value)" class="w-full border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer" required>
                        <option value="Tunai">💵 Tunai (Cash)</option>
                        <option value="QRIS">📱 QRIS (Scan Barcode)</option>
                        <option value="Transfer BCA">🏦 Transfer BCA</option>
                        <option value="Transfer Mandiri">🏦 Transfer Mandiri</option>
                        <option value="Transfer BRI">🏦 Transfer BRI</option>
                        <option value="E-Wallet">💳 E-Wallet</option>
                    </select>
                </div>

                {{-- Preview Barcode QRIS Otomatis Saat Pilih QRIS --}}
                <div id="modal_qris_box" class="hidden mb-3 p-3.5 bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl text-center">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] font-extrabold rounded uppercase">QRIS</span>
                        <span class="text-[11px] font-bold text-slate-700">{{ $qrisConfig['merchant_name'] ?? 'BENING LAUNDRY - SMKN 1 CIAMIS' }}</span>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-indigo-100 inline-block mx-auto">
                        <img id="modal_qris_img" src="{{ $qrisConfig['display_qr_url'] ?? '' }}" alt="QRIS" class="w-40 h-40 object-contain mx-auto">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5">NMID: <span class="font-mono">{{ $qrisConfig['nmid'] ?? 'ID1026000109281' }}</span></p>
                    <p class="text-[11px] text-indigo-700 font-semibold mt-0.5">Silakan scan QRIS sesuai nominal tagihan di atas</p>
                </div>

                {{-- Info Rekening Saat Pilih Transfer --}}
                <div id="modal_transfer_box" class="hidden mb-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700">
                    <span class="font-bold text-slate-500 uppercase text-[10px] flex items-center gap-1 mb-1"><svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg> Info Rekening Tujuan:</span>
                    <span class="font-bold text-slate-900">{{ $qrisConfig['bank_info'] ?? 'Bank BJB / BCA - Unit Produksi Perhotelan SMKN 1 Ciamis' }}</span>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Bukti Transfer (Opsional)</label>
                    <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-xs font-bold transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Proses Pelunasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════ NOTA MODAL ═══════════ --}}
    <div x-show="showNotaModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="closeNotaModal()" class="bg-white shadow-2xl w-full max-w-[400px] mx-4 animate-fade-up overflow-hidden rounded-2xl flex flex-col h-[85vh]">
            <div class="px-5 py-4 flex items-center justify-between bg-[#0b172a] text-white shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                    <h3 class="font-bold text-xs tracking-widest uppercase">STRUK NOTA / INVOICE TRANSAKSI</h3>
                </div>
                <button @click="closeNotaModal()" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-hidden relative bg-slate-50">
                <template x-if="notaUrl">
                    <iframe x-ref="notaIframe" :src="notaUrl" class="w-full h-full border-0 absolute inset-0"></iframe>
                </template>
            </div>
            
            <div class="px-5 py-3.5 border-t border-slate-100 flex gap-2 justify-center bg-white shrink-0">
                <button @click="kirimWa()"
                        class="px-4 py-2 bg-[#25D366] text-white text-xs font-bold rounded-full hover:bg-[#128C7E] transition shadow-sm flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.993.582 1.942.872 2.796.872 3.181 0 5.768-2.586 5.768-5.766 0-3.181-2.587-5.759-5.768-5.759zm3.376 8.204c-.149.418-.752.766-1.042.812-.291.046-.66.082-2.18-.546-1.52-.628-2.502-2.179-2.576-2.278-.074-.099-.607-.808-.607-1.542 0-.734.385-1.096.522-1.245.137-.149.299-.186.398-.186.099 0 .199 0 .285.005.092.005.215-.035.337.257.123.292.42 1.026.457 1.101.037.075.062.162.012.261-.049.099-.074.161-.148.248-.075.087-.157.194-.224.26-.075.075-.153.156-.066.305.087.149.387.638.831 1.032.572.508 1.054.665 1.203.74.149.074.236.062.323-.037.087-.099.373-.434.472-.583.099-.149.199-.124.336-.074.137.05.869.41 1.018.484.149.074.248.112.285.174.037.062.037.36-.112.778zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.434 5.176L2 22l4.957-1.398A9.956 9.956 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg> WA
                </button>
                <button @click="printNota()"
                        class="px-5 py-2 bg-black text-white text-xs font-bold rounded-full hover:bg-slate-800 transition shadow-sm flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg> Cetak
                </button>
                <button @click="closeNotaModal()"
                        class="px-5 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-full hover:bg-slate-200 transition active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const qrisStaticImgUrl = @json($qrisConfig['qris_image'] ?? null);
    const qrisMerchantName = @json($qrisConfig['merchant_name'] ?? 'BENING LAUNDRY - SMKN 1 CIAMIS');
    const qrisNmidCode = @json($qrisConfig['nmid'] ?? 'ID1026000109281');
    let currentModalPrice = 0;
    let currentModalTrxCode = '';

    function openPaymentModal(transaksiCode, totalPrice) {
        currentModalPrice = totalPrice;
        currentModalTrxCode = transaksiCode;
        document.getElementById('modal_transaksi_id').value = transaksiCode;
        document.getElementById('modal_transaksi_code_display').value = transaksiCode;
        document.getElementById('modal_total_price_display').value = new Intl.NumberFormat('id-ID').format(totalPrice);
        
        let jumlahBayarInput = document.getElementById('modal_jumlah_bayar');
        jumlahBayarInput.value = totalPrice;
        jumlahBayarInput.min = totalPrice;

        const selectEl = document.getElementById('modal_metode_pembayaran');
        if (selectEl) {
            onModalPaymentMethodChange(selectEl.value);
        }
        
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function onModalPaymentMethodChange(method) {
        const qrisBox = document.getElementById('modal_qris_box');
        const transferBox = document.getElementById('modal_transfer_box');
        const qrisImg = document.getElementById('modal_qris_img');
        const jumlahBayarInput = document.getElementById('modal_jumlah_bayar');

        if (method === 'QRIS') {
            if (qrisBox) qrisBox.classList.remove('hidden');
            if (transferBox) transferBox.classList.add('hidden');
            if (jumlahBayarInput && currentModalPrice > 0) {
                jumlahBayarInput.value = currentModalPrice;
            }
            if (qrisImg) {
                if (qrisStaticImgUrl) {
                    qrisImg.src = qrisStaticImgUrl;
                } else {
                    const payload = `QRIS:${qrisMerchantName}|NMID:${qrisNmidCode}|AMOUNT:Rp${Math.round(currentModalPrice)}|REF:${currentModalTrxCode}`;
                    qrisImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=280x280&margin=8&data=${encodeURIComponent(payload)}`;
                }
            }
        } else if (method && method !== 'Tunai') {
            if (qrisBox) qrisBox.classList.add('hidden');
            if (transferBox) transferBox.classList.remove('hidden');
            if (jumlahBayarInput && currentModalPrice > 0) {
                jumlahBayarInput.value = currentModalPrice;
            }
        } else {
            if (qrisBox) qrisBox.classList.add('hidden');
            if (transferBox) transferBox.classList.add('hidden');
        }
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('pembayaranApp', () => ({
            showNotaModal: false,
            notaUrl: '',
            
            openNota(url) {
                this.notaUrl = url;
                this.showNotaModal = true;
            },
            
            closeNotaModal() {
                this.showNotaModal = false;
                setTimeout(() => {
                    this.notaUrl = '';
                }, 200);
            },
            
            printNota() {
                const iframe = this.$refs.notaIframe;
                if (iframe && iframe.contentWindow) {
                    iframe.contentWindow.print();
                }
            },
            
            kirimWa() {
                const iframe = this.$refs.notaIframe;
                if (iframe && iframe.contentDocument) {
                    const waLink = iframe.contentDocument.getElementById('waLink');
                    if (waLink) {
                        window.open(waLink.href, '_blank');
                    } else {
                        alert('Link WhatsApp tidak ditemukan.');
                    }
                }
            }
        }));
    });
</script>
@endpush

@endsection
