{{-- resources/views/admin/layanan/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Manajemen Layanan - Bening Laundry')
@section('content')
{{-- ============================================================
     PAGE HEADER
============================================================ --}}
<div x-data="{}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Layanan</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola daftar harga dan jenis jasa pembersihan pakaian.</p>
    </div>
    <button
        @click="$dispatch('open-modal', { name: 'tambah-layanan' })"
        class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 active:scale-95
               text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-brand-600/25
               transition-all duration-150 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Layanan Baru
    </button>
</div>

{{-- ============================================================
     STATISTIK CARDS
============================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- Total Jenis Layanan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Jenis Layanan</p>
            <p class="text-3xl font-bold text-slate-800 leading-tight">{{ $totalLayanan }}</p>
        </div>
    </div>

    {{-- Layanan Terlaris --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Layanan Terlaris</p>
            <p class="text-lg font-bold text-slate-800 leading-tight">{{ $layananTerlaris }}</p>
        </div>
    </div>

    {{-- Status Operasional --}}
    <div class="rounded-2xl shadow-md p-5 flex items-center gap-4
                {{ $semuaAktif ? 'bg-brand-600 text-white' : 'bg-amber-500 text-white' }}">
        <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wider">Status Operasional</p>
            <p class="text-xl font-bold text-white leading-tight">
                {{ $semuaAktif ? 'Semua Aktif' : 'Ada Nonaktif' }}
            </p>
        </div>
    </div>

</div>

{{-- ============================================================
     FILTER TAB
============================================================ --}}
@php $aktivTab = request('kategori', 'semua'); @endphp

<div id="kategori-tabs" class="flex items-center gap-2 mb-5">
    @foreach ([
        ['key' => 'semua',   'label' => 'Semua'],
        ['key' => 'kiloan',  'label' => 'Cuci Kiloan'],
        ['key' => 'satuan',  'label' => 'Cuci Satuan'],
    ] as $tab)
        <a href="{{ route('admin.layanan.index', ['kategori' => $tab['key']]) }}"
           class="px-5 py-2 rounded-xl text-sm font-medium transition-all
                  {{ $aktivTab === $tab['key']
                      ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20'
                      : 'bg-white text-slate-500 border border-slate-200 hover:border-brand-400 hover:text-brand-600' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>

{{-- ============================================================
     GRID LAYANAN
============================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">

    @forelse ($layanans as $layanan)

    <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-5
                hover:shadow-lg hover:-translate-y-1 transition-all duration-200"
         x-data="{ aktif: {{ $layanan->status ? 'true' : 'false' }}, loading: false }">

        {{-- Top row: icon + badge + toggle --}}
        <div class="flex items-start justify-between mb-3">

            {{-- Icon --}}
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                        {{ match($layanan->icon) {
                            'bolt'     => 'bg-brand-600',
                            'hourglass'=> 'bg-slate-100',
                            default    => 'bg-slate-100',
                        } }}">
                @switch($layanan->icon)
                    @case('bolt')
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        @break
                    @case('hourglass')
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @break
                    @case('bed')
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 12H3v8h18v-8h-2M5 12V7a2 2 0 012-2h10a2 2 0 012 2v5M5 12h14" />
                        </svg>
                        @break
                    @case('shoe')
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 19h18M5 19V10l3-3h8l3 3v9" />
                        </svg>
                        @break
                    @case('droplet')
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 2C6.5 2 3 8 3 12a9 9 0 0018 0c0-4-3.5-10-9-10z" />
                        </svg>
                        @break
                    @default {{-- shirt --}}
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 3H5L3 9l3 1V21h12V10l3-1-2-6h-4m-6 0a3 3 0 006 0" />
                        </svg>
                @endswitch
            </div>

            {{-- Badge + Toggle --}}
            <div class="flex items-center gap-2">
                @if ($layanan->badge)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                 {{ $layanan->badge === 'Populer'
                                     ? 'bg-orange-100 text-orange-600'
                                     : 'bg-emerald-100 text-emerald-600' }}">
                        {{ $layanan->badge }}
                    </span>
                @endif

                {{-- Toggle Switch --}}
                <button @click="toggleStatus({{ $layanan->id }}, $data)"
                        :class="aktif ? 'bg-brand-600' : 'bg-slate-200'"
                        :disabled="loading"
                        class="relative inline-flex items-center w-10 h-5 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-500/30 cursor-pointer">
                    <span :class="aktif ? 'translate-x-5' : 'translate-x-1'"
                          class="inline-block w-3.5 h-3.5 bg-white rounded-full shadow transition-transform duration-200"></span>
                </button>
            </div>
        </div>

        {{-- Info --}}
        <h3 class="font-semibold text-slate-800 text-sm leading-snug mb-1">{{ $layanan->nama }}</h3>

        @if ($layanan->estimasi)
            <div class="flex items-center gap-1 text-slate-400 text-xs mb-3">
                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $layanan->estimasi }}
            </div>
        @endif

        {{-- Harga --}}
        <div class="mb-4">
            <span class="text-xs text-slate-400 font-medium">Rp</span>
            <span class="text-xl font-bold text-slate-800">
                {{ number_format($layanan->harga, 0, ',', '.') }}
            </span>
            <span class="text-xs text-slate-400 font-medium">{{ $layanan->satuan }}</span>
        </div>

        <div class="flex gap-2">
            {{-- Edit Button --}}
            <button @click="$dispatch('open-modal', { name: 'edit-layanan', data: {{ json_encode(['id' => $layanan->id, 'nama' => $layanan->nama, 'harga' => $layanan->harga, 'estimasi' => $layanan->estimasi, 'badge' => $layanan->badge]) }} })"
                    class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-semibold
                           text-slate-600 bg-slate-50 border border-slate-200
                           hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200
                           active:scale-95 transition-all duration-150">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Harga
            </button>

            {{-- Delete Button --}}
            <form action="{{ route('admin.layanan.destroy', $layanan) }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="p-2 rounded-xl text-rose-500 bg-rose-50 border border-rose-100
                               hover:bg-rose-500 hover:text-white transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>

    </div>

    @empty
    <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
        <svg class="w-14 h-14 mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg>
        <p class="text-sm font-medium">Belum ada layanan</p>
        <p class="text-xs mt-1">Tambahkan layanan pertama Anda</p>
    </div>
    @endforelse

</div>

{{-- ============================================================
     CARD LAYANAN KHUSUS
============================================================ --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col sm:flex-row items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-brand-600 flex items-center justify-center shrink-0 shadow-lg shadow-brand-600/30">
        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
    </div>
    <div class="flex-1 text-center sm:text-left">
        <h3 class="font-bold text-slate-800 text-base">Butuh jenis layanan khusus?</h3>
        <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">
            Anda dapat menambahkan kategori baru seperti "Cuci Sepatu"
            atau "Perawatan Kulit" untuk memperluas jangkauan bisnis Anda.
        </p>
    </div>
    <a href="#kategori-tabs" 
       class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 active:scale-95
                   text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-brand-600/20
                   transition-all whitespace-nowrap">
        Konfigurasi Kategori
    </a>
</div>
{{-- ============================================================
     MODAL: TAMBAH LAYANAN
============================================================ --}}
@include('admin.layanan.create')

{{-- ============================================================
     MODAL: EDIT LAYANAN
============================================================ --}}
@include('admin.layanan.edit')

{{-- ============================================================
     SCRIPT: Toggle Status AJAX
============================================================ --}}
<script>
    function toggleStatus(id) {
        const components = document.querySelectorAll(`[data-layanan-id="${id}"]`);

        fetch(`/admin/layanan/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Gagal');
        })
        .catch(() => {
            // Revert jika gagal
            // Alpine akan menangani UI state-nya
        });
    }
</script>

{{-- Tambahan: Daftarkan fungsi toggleStatus ke window agar Alpine dapat mengaksesnya --}}
<script>
    window.toggleStatus = function(id, component) {
        component.loading = true;
        fetch(`/admin/layanan/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                component.aktif = data.status;
            } else {
                throw new Error('Gagal memperbarui status');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal mengubah status layanan');
        })
        .finally(() => {
            component.loading = false;
        });
    };
</script>

@endsection
