{{-- resources/views/admin/customers/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Customer')

@section('content')

{{-- ── Page Header ─────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Customer</h1>
        <p class="text-sm text-slate-400 font-medium mt-0.5">Kelola data pelanggan dan pantau riwayat layanan.</p>
    </div>
    <a href="{{ route('admin.customers.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white
              font-bold text-sm rounded-xl shadow-md shadow-brand-200 transition-all duration-200 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3
                 m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Tambah Customer Baru
    </a>
</div>

{{-- ── Stats Cards ──────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

    {{-- Total Customer --}}
    <x-stat-cardcustomer
        title="Total Customer"
        value="{{ number_format($stats['total_customer']) }}"
        badge="12%"
        :badgeUp="true"
        icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
    />

    {{-- Aktif Bulan Ini --}}
    <x-stat-cardcustomer
        title="Customer Aktif Bulan Ini"
        value="{{ number_format($stats['aktif_bulan_ini']) }}"
        subtitle="Target: 400"
        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
    />

    {{-- Highlight Card --}}
    <!-- (Metode Favorit dihilangkan) -->

</div>

{{-- ── Customer Table Card ───────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100">

    {{-- Table Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-5 border-b border-slate-100">
        <div>
            <h2 class="text-base font-bold text-slate-800">Daftar Customer</h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Menampilkan {{ $customers->firstItem() }}–{{ $customers->lastItem() }} dari {{ number_format($customers->total()) }} customer
            </p>
        </div>
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
            <input type="text"
                   name="search"
                   value="{{ $search ?? '' }}"
                   placeholder="Cari nama customer..."
                   class="px-3 py-2 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-200">
            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all duration-200">
                Cari
            </button>
            @if(!empty($search))
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all duration-200">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto min-h-[250px]">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">ID Customer</th>
                    <th class="text-left px-4 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama</th>
                    <th class="text-left px-4 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">No. Telepon</th>
                    <th class="text-left px-4 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Terakhir Transaksi</th>
                    <th class="text-left px-4 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Order</th>
                    <th class="text-left px-4 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="table-row border-b border-slate-50 last:border-0">
                    {{-- ID --}}
                    <td class="px-6 py-4">
                        <span class="text-brand-600 font-bold text-xs">#{{ $customer->id }}</span>
                    </td>
                    {{-- Nama + email --}}
                    <td class="px-4 py-4">
                        <div>
                            <p class="font-semibold text-slate-800 leading-tight">{{ $customer->nama }}</p>
                            <p class="text-[11px] text-slate-400">{{ $customer->email ?? '-' }}</p>
                        </div>
                    </td>
                    {{-- Telepon --}}
                    <td class="px-4 py-4 text-slate-600 font-medium">{{ $customer->no_hp ?? '-' }}</td>
                    {{-- Terakhir transaksi --}}
                    <td class="px-4 py-4 text-slate-500 text-xs leading-relaxed">
                        @if($customer->terakhir_transaksi)
                            {{ \Carbon\Carbon::parse($customer->terakhir_transaksi)->translatedFormat('d M Y, H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    {{-- Total order --}}
                    <td class="px-4 py-4">
                        <span class="font-bold text-slate-800">{{ $customer->total_order ?? 0 }}</span>
                        <span class="text-slate-400 text-xs ml-0.5">Order</span>
                    </td>
                    {{-- Status --}}
                    <td class="px-4 py-4">
                        <span class="badge-aktif inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 bg-brand-600 rounded-full"></span>
                            Aktif
                        </span>
                    </td>
                    {{-- Aksi --}}
                    <td class="px-6 py-4 text-right">
                        <div class="relative inline-block text-left">
                            <button onclick="toggleDropdown('dropdown-{{ $customer->id }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                                           hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                                </svg>
                            </button>
                            
                            <div id="dropdown-{{ $customer->id }}" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                                
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                <div class="h-px bg-slate-50 my-1"></div>

                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus customer ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                             M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                                             m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">Belum ada data customer</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $customers->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif

</div>

@endsection
