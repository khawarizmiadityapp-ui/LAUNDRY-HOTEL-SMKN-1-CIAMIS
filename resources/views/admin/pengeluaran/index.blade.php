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
            <span class="inline-flex items-center gap-1 text-xs font-600 text-emerald-600 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                +5.2%
            </span>
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
        <p class="text-[11px] text-gray-400 mt-1.5">Penjualan Jasa: {{ rupiah($targetAnggaran) }}</p>
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

        {{-- Export --}}
        <a href="{{ route('admin.pengeluaran.export') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-500 text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition shadow-card">
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export
        </a>
    </div>

    {{-- Tambah Baru --}}
    <a href="{{ route('admin.pengeluaran.create') }}"
        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-600 text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all hover:shadow-md hover:-translate-y-px active:translate-y-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengeluaran Baru
    </a>
</div>

{{-- ===== FILTER PANEL (hidden by default) ===== --}}
<div id="filterPanel" class="hidden bg-white border border-gray-200 rounded-2xl shadow-card p-5 mb-5">
    <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-600 text-gray-500 mb-1.5 uppercase tracking-wider">Kategori</label>
            <select name="kategori" class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-white">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-600 text-gray-500 mb-1.5 uppercase tracking-wider">Status</label>
            <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-white">
                <option value="">Semua Status</option>
                <option value="lunas"   {{ request('status') == 'lunas'   ? 'selected' : '' }}>Lunas</option>
                <option value="urgent"  {{ request('status') == 'urgent'  ? 'selected' : '' }}>Urgent</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
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
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="pengeluaranTable">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="text-left px-6 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">ID Transaksi</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Nama Pengeluaran</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Kategori</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="text-right px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Nominal</th>
                    <th class="text-left px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Bon</th>
                    <th class="text-center px-4 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-center px-6 py-3.5 text-xs font-700 text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengeluarans as $item)
                <tr class="table-row transition-colors" data-search="{{ strtolower($item->nama . ' ' . $item->kategori . ' ' . $item->id_transaksi) }}">

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
                        <span class="inline-block text-xs font-500 bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">{{ $item->kategori }}</span>
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

                    {{-- Status --}}
                    <td class="px-4 py-4 text-center">
                        @php
                            $statusKey = strtolower($item->status);
                            $badgeClass = match($statusKey) {
                                'lunas'   => 'badge-lunas',
                                'urgent'  => 'badge-urgent',
                                default   => 'badge-pending',
                            };
                        @endphp
                        <span class="inline-block text-xs font-600 uppercase tracking-wider px-3 py-1 rounded-full {{ $badgeClass }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>

                    {{-- Aksi Dropdown --}}
                    <td class="px-6 py-4 text-center relative">
                        <button onclick="toggleDropdown(this)"
                            class="btn-action w-8 h-8 rounded-lg flex items-center justify-center mx-auto text-gray-400 hover:text-gray-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu bg-white border border-gray-100 rounded-xl shadow-card-hover py-1 absolute right-0 top-full mt-1">
                            <a href="{{ route('admin.pengeluaran.show', $item) }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('admin.pengeluaran.edit', $item) }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
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
                    <td colspan="8" class="text-center py-16 text-gray-400">
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
</script>
@endpush
