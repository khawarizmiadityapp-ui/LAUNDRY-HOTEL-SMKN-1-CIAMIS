{{-- resources/views/admin/inventory/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Inventory Management')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up   { animation: fadeSlideUp .5s ease both; }
    .delay-1   { animation-delay: .08s; }
    .delay-2   { animation-delay: .16s; }
    .delay-3   { animation-delay: .24s; }
    .delay-4   { animation-delay: .32s; }
    .progress-bar { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
</style>
@endpush

@section('content')
    <!-- Header -->
    <div class="mb-8 fade-up flex justify-between items-end">
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-[10px] font-bold tracking-widest text-blue-500 uppercase mb-3">
                Inventaris Kecil
            </span>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Supply Management</h1>
            <p class="mt-1.5 text-sm text-slate-400 max-w-lg leading-relaxed">
                Curated list of essential laundry supplies. Monitor detergent levels and scent reserves with high-precision conductors.
            </p>
        </div>
        <button onclick="document.getElementById('add-item-modal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-xl shadow-sm hover:bg-blue-700 transition font-bold text-sm h-full flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang
        </button>
    </div>

    @if(isset($pendingRequests) && $pendingRequests->isNotEmpty())
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-amber-800 uppercase tracking-wider">Permintaan Konfirmasi Stok</h2>
            <span class="text-xs font-semibold text-amber-700">{{ $pendingRequests->count() }} pending</span>
        </div>
        <div class="space-y-3">
            @foreach($pendingRequests as $requestItem)
            <div class="bg-white border border-amber-100 rounded-xl p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-800">{{ $requestItem->inventory->name ?? 'Item' }}
                        <span class="text-xs text-slate-500">({{ $requestItem->adjustment > 0 ? '+' : '' }}{{ $requestItem->adjustment }})</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Diminta oleh: {{ $requestItem->requester->name ?? '-' }} • {{ $requestItem->created_at->diffForHumans() }}</p>
                    @if($requestItem->reason)
                        <p class="text-xs text-slate-500">Alasan: {{ $requestItem->reason }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.inventory.request.approve', $requestItem->id) }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('admin.inventory.request.reject', $requestItem->id) }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-lg bg-rose-600 text-white text-xs font-semibold">Tolak</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 3-column grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- ── 2-col content ── -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Premium Detergents -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 fade-up delay-1">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-slate-800 text-lg uppercase tracking-tight">Premium Detergents</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Active wash agents and surfactants</p>
                    </div>
                </div>

                <!-- Product cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($detergents as $item)
                    <div class="bg-white/80 backdrop-blur-sm border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="relative bg-gradient-to-br {{ $loop->index % 2 == 0 ? 'from-cyan-50 via-teal-50 to-sky-50' : 'from-blue-50 via-sky-50 to-indigo-50' }} h-44 flex items-center justify-center p-4">
                            @if($item->quantity < 20)
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-red-50 border border-red-200 text-red-500 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                Low Stock
                            </span>
                            @endif
                            <!-- Bottle SVG -->
                            <svg viewBox="0 0 80 130" class="h-32 drop-shadow-lg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="28" y="1" width="24" height="13" rx="5" fill="{{ $loop->index % 2 == 0 ? '#67e8f9' : '#93c5fd' }}" opacity=".8"/>
                                <rect x="22" y="12" width="36" height="7" rx="4" fill="{{ $loop->index % 2 == 0 ? '#22d3ee' : '#60a5fa' }}"/>
                                <rect x="12" y="17" width="56" height="92" rx="16" fill="url(#grad-{{ $item->id }})"/>
                                <rect x="16" y="21" width="48" height="84" rx="12" fill="url(#grad-inner-{{ $item->id }})" opacity=".6"/>
                                <ellipse cx="32" cy="46" rx="7" ry="7" fill="white" opacity=".25"/>
                                <ellipse cx="50" cy="38" rx="4" ry="4" fill="white" opacity=".15"/>
                                <defs>
                                    <linearGradient id="grad-{{ $item->id }}" x1="12" y1="17" x2="68" y2="109" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="{{ $loop->index % 2 == 0 ? '#22d3ee' : '#3b82f6' }}"/>
                                        <stop offset="1" stop-color="{{ $loop->index % 2 == 0 ? '#0891b2' : '#1d4ed8' }}"/>
                                    </linearGradient>
                                    <linearGradient id="grad-inner-{{ $item->id }}" x1="16" y1="21" x2="64" y2="105" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="{{ $loop->index % 2 == 0 ? '#a5f3fc' : '#bfdbfe' }}"/>
                                        <stop offset="1" stop-color="{{ $loop->index % 2 == 0 ? '#06b6d4' : '#3b82f6' }}" stop-opacity=".5"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="px-4 pt-3 pb-4">
                            <p class="text-[9px] font-bold tracking-widest text-blue-400 uppercase mb-0.5">{{ $item->type ?? 'Liquid' }}</p>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base leading-snug">{{ $item->name }}</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Industrial Grade Formula</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-xl font-bold text-slate-800" id="qty{{ $item->id }}">{{ $item->quantity }}</span>
                                    <span class="text-xs text-slate-400 ml-0.5">units</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 mt-3">
                                <button onclick="updateQty({{ $item->id }}, 'decrement')"
                                        class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm transition-colors">−</button>
                                <span class="min-w-[2rem] text-center font-semibold text-slate-700 text-sm" id="qty-disp-{{ $item->id }}">{{ $item->quantity }}</span>
                                <button onclick="updateQty({{ $item->id }}, 'increment')"
                                        class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm transition-colors">+</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Fragrance Library -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 fade-up delay-2">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-slate-800 text-lg uppercase tracking-tight">Fragrance Library</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Signature olfactory profiles for finished loads</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($fragrances as $item)
                    @php
                        $colors = [
                            ['bg' => 'bg-green-100',  'text' => 'text-green-500',  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['bg' => 'bg-blue-100',   'text' => 'text-blue-500',   'icon' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z'],
                            ['bg' => 'bg-orange-100', 'text' => 'text-orange-400', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                            ['bg' => 'bg-slate-100',  'text' => 'text-slate-400',  'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ];
                        $style = $colors[$loop->index % 4];
                    @endphp
                    <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl {{ $style['bg'] }} flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 {{ $style['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $style['icon'] }}"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm leading-snug">{{ $item->name }}</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5 leading-tight">{{ $item->type ?? 'Concentrated Oil' }}</p>
                        <div class="flex items-center gap-2 mt-3">
                            <button onclick="updateQty({{ $item->id }}, 'decrement')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">−</button>
                            <span class="text-sm font-mono font-semibold text-slate-700 min-w-[1.75rem] text-center" id="qty{{ $item->id }}">{{ str_pad($item->quantity, 2, '0', STR_PAD_LEFT) }}</span>
                            <button onclick="updateQty({{ $item->id }}, 'increment')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">+</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- ── RIGHT PANEL ── -->
        <div class="space-y-4 fade-up delay-3">

            <!-- Inventory Health -->
            <div class="bg-gradient-to-br from-brand-600 to-brand-400 rounded-2xl p-5 shadow-lg shadow-brand-200 relative overflow-hidden">
                <div class="absolute -top-6 -right-6 w-28 h-28 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="absolute -bottom-8 -left-4 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

                <h2 class="text-white font-display text-base mb-4 relative z-10">Inventory Health</h2>

                <div class="space-y-3.5 relative z-10">
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs font-medium text-brand-100">Total Stock Units</span>
                            <span class="text-xs font-bold text-white">{{ number_format($totalItems) }}</span>
                        </div>
                        <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full progress-bar" style="width: 100%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs font-medium text-brand-100">Low Stock Markers</span>
                            <span class="text-xs font-bold text-white">{{ $lowStock }} Items</span>
                        </div>
                        @php
                            $health = $totalItems > 0 ? max(0, 100 - (($lowStock / max(1, count($detergents)+count($fragrances)+count($hangers))) * 100)) : 100;
                        @endphp
                        <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full {{ $health < 50 ? 'bg-rose-300' : ($health < 80 ? 'bg-amber-300' : 'bg-emerald-300') }} rounded-full progress-bar" style="width: {{ $health }}%"></div>
                        </div>
                    </div>
                </div>

                <button class="relative z-10 mt-5 w-full bg-white text-brand-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-brand-50 transition-colors shadow-sm">
                    Supply Report
                </button>
            </div>

            <!-- Essential Hangers -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 fade-up delay-4">
                <h3 class="text-[9.5px] font-bold tracking-widest text-slate-400 uppercase mb-4">Essential Hangers</h3>
                <div class="space-y-3">
                    @foreach($hangers as $item)
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0 0L4 19h16L12 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700 leading-tight">{{ $item->name }}</p>
                                <p class="text-xs text-slate-400">Qty: <span id="qty{{ $item->id }}">{{ number_format($item->quantity) }}</span></p>
                            </div>
                        </div>
                        <button onclick="updateQty({{ $item->id }}, 'increment')" class="w-7 h-7 rounded-full border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-brand-50 hover:border-brand-200 hover:text-brand-600 transition-colors flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

<!-- Add Item Modal -->
<div id="add-item-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in mx-4">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-slate-800">Tambah Barang Baru</h3>
            <button onclick="document.getElementById('add-item-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.inventory.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Barang</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                        <option value="detergent">Detergent</option>
                        <option value="fragrance">Pewangi</option>
                        <option value="hanger">Hanger</option>
                        <option value="packaging">Packaging</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Satuan (Unit)</label>
                    <input type="text" name="unit" required placeholder="Liter, Pcs, dll" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Stok Awal</label>
                    <input type="number" name="quantity" required min="0" value="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Stok Minimum</label>
                    <input type="number" name="minimum_stock" required min="0" value="5" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                </div>
            </div>
            
            <div class="pt-4 mt-6 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('add-item-modal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQty(id, type) {
    let url = "{{ route('admin.inventory.update', ['id' => ':id']) }}";
    url = url.replace(':id', id);
    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ type: type })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => {
                throw new Error(err.message || 'Gagal memperbarui stok');
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            const el = document.getElementById('qty' + id);
            const elDisp = document.getElementById('qty-disp-' + id);

            if (el) {
                if (el.classList.contains('font-mono')) {
                    el.innerText = String(data.qty).padStart(2, '0');
                } else {
                    el.innerText = data.qty;
                }
            }
            if (elDisp) elDisp.innerText = data.qty;
        } else {
            alert(data.message || 'Gagal memperbarui stok');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Terjadi kesalahan saat memperbarui stok');
    });
}
</script>
@endpush
