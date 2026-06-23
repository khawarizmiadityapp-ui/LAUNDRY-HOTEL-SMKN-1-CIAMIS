@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas_piket')

@section('title', 'Buat Pesanan')

@push('styles')
<style>
    /* ── POS specific styles ── */
    .pos-card {
        transition: all 0.2s cubic-bezier(.22,1,.36,1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .pos-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px 0 rgba(21,34,120,0.13);
    }
    .pos-card.selected {
        border-color: #3568f4;
        box-shadow: 0 0 0 2px rgba(53,104,244,0.3), 0 4px 14px rgba(53,104,244,0.15);
    }
    .pos-card.selected .pos-check {
        opacity: 1;
        transform: scale(1);
    }
    .pos-check {
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.2s ease;
    }

    /* Category filter pills */
    .cat-pill {
        transition: all 0.15s ease;
    }
    .cat-pill.active {
        background: #3568f4;
        color: #fff;
        box-shadow: 0 2px 10px rgba(53,104,244,0.35);
    }
    .cat-pill:not(.active):hover {
        background: #eef4ff;
        color: #3568f4;
    }

    /* Cart item animation */
    .cart-item {
        animation: slideIn 0.25s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(12px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Payment method button */
    .pay-btn {
        transition: all 0.15s ease;
        border: 2px solid #e2e8f0;
    }
    .pay-btn.active {
        border-color: #3568f4;
        background: #eef4ff;
        color: #3568f4;
    }
    .pay-btn:hover:not(.active) {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    /* Customer dropdown */
    .customer-dropdown {
        max-height: 220px;
        overflow-y: auto;
    }

    /* Modal overlay */
    .modal-overlay {
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(4px);
    }

    /* Qty input */
    .qty-input {
        width: 56px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 0;
        font-size: 13px;
        font-weight: 600;
    }
    .qty-input:focus {
        outline: none;
        border-color: #3568f4;
        box-shadow: 0 0 0 2px rgba(53,104,244,0.2);
    }

    /* Smooth scrollbar for right panel */
    .order-panel::-webkit-scrollbar { width: 4px; }
    .order-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

    @if(auth()->user()->role !== 'admin')
    /* Override cashier layout constraints for full-screen POS */
    body {
        overflow: hidden !important;
        height: 100vh !important;
    }
    main {
        height: 100vh !important;
        min-height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
    }
    main > div.max-w-screen-xl {
        padding: 0 !important;
        margin: 0 !important;
        max-width: none !important;
        flex: 1 1 0% !important;
        height: 100% !important;
        width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }
    main > div.max-w-screen-xl > div.p-6 {
        padding: 0 !important;
        flex: 1 1 0% !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }
    div[x-data="posApp()"] {
        margin: 0 !important;
        height: 100% !important;
    }
    @endif
</style>
@endpush

@section('content')
<div x-data="posApp()" x-init="init()" class="flex flex-col lg:flex-row gap-0 -mx-5 lg:-mx-8 -my-8 {{ auth()->user()->role === 'admin' ? 'h-[calc(100vh-4rem)] lg:h-[calc(100vh-4rem)] overflow-hidden' : 'min-h-[calc(100vh-4rem)] lg:min-h-[calc(100vh-4rem)]' }}">

    {{-- ═══════════ LEFT: Service Grid ═══════════ --}}
    <div class="flex-1 p-5 lg:p-7 overflow-y-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <button @click="viewMode = 'order'" 
                            :class="viewMode === 'order' ? 'text-slate-900 border-b-2 border-brand-500' : 'text-slate-400'"
                            class="text-xl font-display font-bold pb-1 transition-all">
                        Buat Pesanan
                    </button>
                    <button @click="viewMode = 'pickup'" 
                            :class="viewMode === 'pickup' ? 'text-slate-900 border-b-2 border-brand-500' : 'text-slate-400'"
                            class="text-xl font-display font-bold pb-1 transition-all flex items-center gap-2">
                        Siap Diambil
                        @if(count($readyToPickup) > 0)
                        <span class="bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ count($readyToPickup) }}</span>
                        @endif
                    </button>
                </div>
                <p class="text-sm text-slate-400 mt-0.5" x-text="viewMode === 'order' ? 'Pilih layanan untuk customer' : 'Daftar cucian yang sudah selesai packing'"></p>
            </div>
            <div class="flex items-center gap-4">
                @if(auth()->user()->role === 'admin')
                <button @click="openAddServiceModal()"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 text-brand-600 rounded-lg text-xs font-semibold hover:bg-brand-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Layanan
                </button>
                @endif
                <div class="text-right">
                    <p class="text-xs text-slate-400">
                        <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        <span x-text="currentDate"></span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Content based on View Mode --}}
        <div x-show="viewMode === 'order'" x-transition>
            {{-- Customer Search --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-5 shadow-card relative">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </span>
                    <input type="text"
                           x-model="customerSearch"
                           @input.debounce.300ms="searchCustomers()"
                           @focus="showDropdown = customerSearch.length > 0 && customerResults.length > 0"
                           placeholder="Cari pelanggan (Nama / No. HP)..."
                           class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400
                                  placeholder:text-slate-400 transition">
                    {{-- Customer Dropdown --}}
                    <div x-show="showDropdown" x-cloak
                         @click.outside="showDropdown = false"
                         class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-20 customer-dropdown">
                        <template x-for="c in customerResults" :key="c.id">
                            <button @click="selectCustomer(c)"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition text-left">
                                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold" x-text="c.nama.charAt(0).toUpperCase()"></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate" x-text="c.nama"></p>
                                    <p class="text-xs text-slate-400" x-text="c.no_hp || '-'"></p>
                                </div>
                            </button>
                        </template>
                        <div x-show="customerResults.length === 0 && customerSearch.length > 0" class="px-4 py-3 text-sm text-slate-400 text-center">
                            Tidak ditemukan
                        </div>
                    </div>
                </div>
                <button @click="showNewCustomerModal = true"
                        class="flex items-center gap-1.5 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-brand-600
                               hover:bg-brand-50 hover:border-brand-200 transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    Baru
                </button>
            </div>

            {{-- Selected customer badge --}}
            <div x-show="selectedCustomer" x-cloak class="mt-3 flex items-center gap-2">
                <div class="flex items-center gap-2 bg-brand-50 border border-brand-100 rounded-lg px-3 py-1.5">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    <span class="text-sm font-medium text-brand-700" x-text="selectedCustomer?.nama"></span>
                    <span class="text-xs text-brand-400" x-text="selectedCustomer?.no_hp"></span>
                    <button @click="selectedCustomer = null" class="ml-1 text-brand-400 hover:text-brand-600 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Category Filters --}}
        <div class="flex items-center gap-2 mb-5 flex-wrap">
            <button @click="activeCategory = 'semua'"
                    :class="activeCategory === 'semua' ? 'active' : ''"
                    class="cat-pill px-4 py-1.5 rounded-full text-sm font-medium bg-slate-100 text-slate-600">
                Semua
            </button>
            @foreach($kategoris as $kat)
            <button @click="activeCategory = '{{ $kat }}'"
                    :class="activeCategory === '{{ $kat }}' ? 'active' : ''"
                    class="cat-pill px-4 py-1.5 rounded-full text-sm font-medium bg-slate-100 text-slate-600 capitalize">
                {{ ucfirst($kat) }}
            </button>
            @endforeach
        </div>

        {{-- Service Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($layanans as $layanan)
            <div x-show="activeCategory === 'semua' || activeCategory === '{{ $layanan->kategori }}'"
                 :class="isInCart({{ $layanan->id }}) ? 'selected' : ''"
                 class="pos-card bg-white border border-slate-100 rounded-2xl p-4 shadow-sm group">

                {{-- Edit overlay for admin --}}
                @if(auth()->user()->role === 'admin')
                <button @click.stop="openEditServiceModal({{ json_encode($layanan) }})"
                        class="absolute top-2.5 left-2.5 w-7 h-7 bg-white/90 backdrop-blur shadow-sm border border-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-brand-600 hover:scale-110 transition opacity-0 group-hover:opacity-100 z-10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </button>
                @endif

                <div @click="toggleService({{ $layanan->id }})">
                    {{-- Check badge --}}
                    <div class="pos-check absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-brand-500 text-white flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </div>

                    {{-- Service icon/image --}}
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center mb-3">
                        @if($layanan->icon === 'bolt')
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        @elseif($layanan->icon === 'hourglass')
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($layanan->icon === 'shirt')
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        @elseif($layanan->icon === 'bed')
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        @elseif($layanan->icon === 'shoe')
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                        @elseif($layanan->icon === 'droplet')
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/></svg>
                        @else
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        @endif
                    </div>

                    <h3 class="text-sm font-semibold text-slate-800 leading-tight mb-1">{{ $layanan->nama }}</h3>
                    <p class="text-[11px] text-slate-400 capitalize mb-2">{{ $layanan->kategori }}</p>



                    <p class="text-sm font-bold text-brand-600">{{ $layanan->harga_format }}<span class="text-[11px] font-normal text-slate-400">{{ $layanan->satuan }}</span></p>
                </div>
            </div>
            @endforeach
        </div>
        </div>

        {{-- Pickup Mode --}}
        <div x-show="viewMode === 'pickup'" x-transition x-cloak>
            <div class="space-y-4">
                @forelse($readyToPickup as $trx)
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr($trx->customer_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-900">{{ $trx->customer_name }}</h3>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase">{{ $trx->transaksi_code }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $trx->customer_phone }} • {{ count($trx->details) }} Item</p>
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($trx->details as $det)
                                <span class="text-[10px] bg-brand-50 text-brand-600 px-2 py-0.5 rounded-md font-medium">{{ $det->layanan->nama }} ({{ $det->qty }}{{ $det->layanan->satuan }})</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-auto">
                        <div class="text-right mr-2 hidden sm:block">
                            <p class="text-xs text-slate-400">Total Tagihan</p>
                            <p class="text-sm font-bold text-brand-600">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                            <span class="text-[10px] font-bold {{ $trx->payment_status === 'lunas' ? 'text-emerald-500' : 'text-rose-500' }} uppercase">
                                {{ $trx->payment_status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                            </span>
                        </div>
                        
                        <form action="{{ route('pos.pickup', $trx->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Konfirmasi pengambilan cucian untuk {{ $trx->customer_name }}?')"
                                    class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 shadow-sm transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ambil Cucian
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl py-12 px-4 text-center">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                    <h3 class="text-slate-900 font-bold">Tidak ada cucian siap diambil</h3>
                    <p class="text-sm text-slate-400 mt-1">Cucian yang sudah selesai di-packing akan muncul di sini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="w-full lg:w-[380px] bg-white border-l border-slate-100 flex flex-col order-panel shrink-0 overflow-y-auto">

        {{-- Cart Header --}}
        <div class="p-5 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-display font-bold text-slate-900">Ringkasan Pesanan</h2>
                <span x-show="cart.length > 0" class="text-xs bg-brand-100 text-brand-600 font-bold px-2 py-0.5 rounded-full" x-text="cart.length + ' item'"></span>
            </div>

            {{-- Selected customer small --}}
            <div x-show="selectedCustomer" x-cloak class="mt-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span class="text-sm text-slate-600" x-text="selectedCustomer?.nama"></span>
            </div>
            <div x-show="!selectedCustomer" class="mt-2 text-xs text-rose-400">
                <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                Pilih customer terlebih dahulu
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-5 space-y-3">
            <template x-if="cart.length === 0">
                <div class="text-center py-10">
                    <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    <p class="text-sm text-slate-400">Belum ada layanan dipilih</p>
                    <p class="text-xs text-slate-300 mt-1">Klik layanan di sebelah kiri</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="cart-item bg-slate-50 rounded-xl p-3 flex items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate" x-text="item.nama"></p>
                        <p class="text-xs text-slate-400 mt-0.5" x-text="formatRupiah(item.harga) + item.satuan"></p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button @click="decrementQty(index)" class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-sm font-bold">−</button>
                        <input type="number" :value="item.qty" @change="updateQty(index, $event.target.value)" class="qty-input" step="0.1" min="0.1">
                        <button @click="incrementQty(index)" class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-sm font-bold">+</button>
                    </div>
                    <button @click="removeFromCart(index)" class="p-1 text-slate-300 hover:text-rose-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- Totals & Payment --}}
        <div class="border-t border-slate-100 p-5 space-y-4">

            {{-- Subtotal etc --}}
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center text-slate-900 font-semibold">
                    <span>Subtotal</span>
                    <span x-text="formatRupiah(subtotal)"></span>
                </div>

                {{-- Diskon --}}
                <div class="flex justify-between items-center text-slate-900 font-semibold">
                    <span>Diskon</span>
                    <div class="relative w-32">
                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-slate-400 text-xs font-semibold pointer-events-none">Rp</span>
                        <input type="number" x-model.number="discount" min="0" placeholder="0" class="w-full pl-8 pr-2 py-1.5 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-right text-slate-800 font-semibold shadow-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                <span class="text-base font-bold text-slate-900">Total Tagihan</span>
                <span class="text-xl font-bold text-brand-600" x-text="formatRupiah(totalTagihan)"></span>
            </div>

            {{-- Payment Method --}}
            <div class="grid grid-cols-3 gap-2">
                <button @click="paymentMethod = 'tunai'"
                        :class="paymentMethod === 'tunai' ? 'active' : ''"
                        class="pay-btn flex flex-col items-center gap-1 py-2.5 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    <span class="text-[11px] font-semibold">Tunai</span>
                </button>
                <button @click="paymentMethod = 'qris'"
                        :class="paymentMethod === 'qris' ? 'active' : ''"
                        class="pay-btn flex flex-col items-center gap-1 py-2.5 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z M13.5 14.625v5.625m2.813-5.625v5.625m2.812-3.375v3.375"/></svg>
                    <span class="text-[11px] font-semibold">QRIS</span>
                </button>
                <button @click="paymentMethod = 'transfer'"
                        :class="paymentMethod === 'transfer' ? 'active' : ''"
                        class="pay-btn flex flex-col items-center gap-1 py-2.5 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>
                    <span class="text-[11px] font-semibold">Transfer</span>
                </button>
            </div>

            {{-- Payment Status Toggle --}}
            <div class="flex gap-2">
                <button @click="paymentStatus = 'belum_bayar'"
                        :class="paymentStatus === 'belum_bayar' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-white text-slate-500 border-slate-200'"
                        class="flex-1 py-2 text-sm font-medium border rounded-xl transition">
                    belum bayar
                </button>
                <button @click="paymentStatus = 'lunas'"
                        :class="paymentStatus === 'lunas' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-white text-slate-500 border-slate-200'"
                        class="flex-1 py-2 text-sm font-medium border rounded-xl transition">
                    Lunas
                </button>
            </div>

            <!-- Cash Change Calculator (only for Tunai & Lunas payment) -->
            <div x-show="paymentMethod === 'tunai' && paymentStatus === 'lunas'" x-cloak class="space-y-3 p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <label for="cash_received" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 font-sans">Nominal Uang Pelanggan</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-semibold text-xs">
                            Rp
                        </div>
                        <input type="number" id="cash_received" x-model.number="cashReceived"
                               class="w-full pl-8 pr-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-semibold"
                               placeholder="Contoh: 50000" min="0">
                    </div>
                    <div x-show="cashReceived && cashReceived < totalTagihan" x-cloak class="text-xs text-rose-500 font-semibold mt-1">
                        Uang pelanggan kurang dari total tagihan
                    </div>
                </div>
                <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                    <span>Kembalian</span>
                    <span class="text-sm font-bold text-emerald-600 font-sans" x-text="formatRupiah(changeAmount)"></span>
                </div>
            </div>

            {{-- Petugas Kasir --}}
            <div class="space-y-1.5 relative" @click.outside="showKasirDropdown = false">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Petugas Kasir <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </span>
                    <input type="text"
                           x-model="kasirSearch"
                           @input="filterKasir()"
                           @focus="showKasirDropdown = true"
                           placeholder="Cari nama Kasir..."
                           class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500
                                  placeholder:text-slate-300 transition-all font-medium text-slate-800">
                    
                    {{-- Autocomplete Dropdown --}}
                    <div x-show="showKasirDropdown && filteredKasirList.length > 0" x-cloak
                         class="absolute bottom-full left-0 right-0 mb-1 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
                        <template x-for="p in filteredKasirList" :key="p.id_petugas">
                            <button type="button" @click="selectKasir(p)"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition text-left">
                                <div class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-xs font-bold" x-text="p.nama.charAt(0).toUpperCase()"></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate" x-text="p.nama"></p>
                                    <p class="text-xs text-slate-400" x-text="p.id_petugas"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Error Warning --}}
                <div x-show="isKasirInvalid && kasirSearch.length > 0" x-cloak class="text-xs text-rose-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <span>Petugas tidak terdaftar</span>
                </div>
            </div>

            {{-- Submit --}}
            <button @click="submitOrder()"
                    :disabled="cart.length === 0 || !selectedCustomer || submitting || isKasirInvalid || isPaymentInvalid"
                    :class="cart.length === 0 || !selectedCustomer || isKasirInvalid || isPaymentInvalid ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg hover:shadow-brand-200'"
                    class="w-full py-3 bg-gradient-to-r from-brand-500 to-brand-700 text-white rounded-xl font-semibold text-sm
                           flex items-center justify-center gap-2 transition-all duration-200">
                <span x-show="!submitting" class="flex items-center gap-2">
                    Proses Pesanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </span>
                <span x-show="submitting" x-cloak class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Memproses...
                </span>
            </button>
        </div>
    </div>

    <div x-show="showNewCustomerModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center modal-overlay"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showNewCustomerModal = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 animate-fade-up overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-display font-bold text-slate-900">Tambah Pelanggan Baru</h3>
                <button @click="showNewCustomerModal = false" class="p-1 text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-1 block">Nama Lengkap <span class="text-rose-400">*</span></label>
                    <input type="text" x-model="newCustomer.nama" placeholder="Masukkan nama pelanggan"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 placeholder:text-slate-300">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-1 block">Nomor HP <span class="text-rose-400">*</span></label>
                    <input type="tel" x-model="newCustomer.no_hp" @input="newCustomer.no_hp = newCustomer.no_hp.replace(/[^0-9]/g, '')" placeholder="Contoh: 08123xxx" inputmode="numeric"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 placeholder:text-slate-300">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-1 block">Alamat (Opsional)</label>
                    <textarea x-model="newCustomer.alamat" rows="3" placeholder="Masukkan alamat lengkap..."
                              class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 placeholder:text-slate-300"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex gap-3 justify-end bg-slate-50/50">
                <button @click="showNewCustomerModal = false"
                        class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition">
                    Batal
                </button>
                <button @click="saveNewCustomer()"
                        :disabled="!newCustomer.nama || !newCustomer.no_hp"
                        class="px-5 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Simpan Pelanggan
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════ SERVICE MODAL (ADD/EDIT) ═══════════ --}}
    @if(auth()->user()->role === 'admin')
    <div x-show="showServiceModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center modal-overlay"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showServiceModal = false"
             class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 animate-fade-up overflow-hidden border border-slate-100">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex flex-col">
                    <h3 class="text-base font-bold text-slate-900" x-text="serviceForm.id ? 'Edit Layanan' : 'Tambah Layanan Baru'"></h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="serviceForm.id ? 'Perbarui informasi layanan laundry Anda' : 'Buat master data layanan laundry baru'"></p>
                </div>
                <button @click="showServiceModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Nama Layanan <span class="text-rose-400">*</span></label>
                    <input type="text" x-model="serviceForm.nama" placeholder="Contoh: Cuci Kering Regular"
                           class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 placeholder:text-slate-300 transition bg-white">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Kategori <span class="text-rose-400">*</span></label>
                        <select x-model="serviceForm.kategori" 
                                @change="serviceForm.satuan = serviceForm.kategori === 'kiloan' ? '/kg' : '/pcs'"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition bg-white">
                            <option value="kiloan">Kiloan</option>
                            <option value="satuan">Satuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Satuan (Otomatis)</label>
                        <input type="text" x-model="serviceForm.satuan" readonly disabled
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 text-slate-400 rounded-xl cursor-not-allowed">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Harga <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-4 flex items-center text-slate-400 text-sm font-semibold pointer-events-none">Rp</span>
                            <input type="number" x-model="serviceForm.harga" placeholder="0"
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Estimasi</label>
                        <input type="text" x-model="serviceForm.estimasi" placeholder="Contoh: 2-3 Hari"
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 placeholder:text-slate-300 transition bg-white">
                    </div>
                </div>
                
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Icon Visual</label>
                    <div class="grid grid-cols-6 gap-2">
                        <template x-for="icon in ['bolt', 'hourglass', 'shirt', 'bed', 'shoe', 'droplet']">
                            <button @click="serviceForm.icon = icon"
                                    type="button"
                                    :class="serviceForm.icon === icon ? 'border-brand-500 bg-brand-50/70 text-brand-600 shadow-sm shadow-brand-100 scale-[1.03]' : 'border-slate-200 hover:border-slate-300 bg-white text-slate-400'"
                                    class="h-11 border-2 rounded-xl flex items-center justify-center transition-all duration-200">
                                <template x-if="icon === 'bolt'"><svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg></template>
                                <template x-if="icon === 'hourglass'"><svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
                                <template x-if="icon === 'shirt'"><svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg></template>
                                <template x-if="icon === 'bed'"><svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M22.5 12c0-5.385-4.365-9.75-9.75-9.75S3 6.615 3 12a9.75 9.75 0 009.75 9.75c4.507 0 8.334-3.057 9.447-7.29a.75.75 0 01.72-.544c.414 0 .75.336.75.75v.084c0 .307-.122.6-.339.817l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.084.084 0 00.026-.062l-.004-.007a8.25 8.25 0 01-8.25 6.75 8.25 8.25 0 01-8.25-8.25 8.25 8.25 0 018.25-8.25 8.25 8.25 0 017.388 4.544.75.75 0 01-1.352.648 6.75 6.75 0 00-6.036-3.712z"/></svg></template>
                                <template x-if="icon === 'shoe'"><svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg></template>
                                <template x-if="icon === 'droplet'"><svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/></svg></template>
                            </button>
                        </template>
                    </div>
                </div>
                
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 block">Alur Kerja (Workflow) <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Mencuci -->
                        <label :class="serviceForm.needs_washing ? 'border-brand-500 bg-brand-50/50 text-brand-700 shadow-sm shadow-brand-50' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                               class="flex items-center gap-2.5 p-3 border-2 rounded-xl cursor-pointer transition-all duration-200">
                            <input type="checkbox" x-model="serviceForm.needs_washing" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold">Mencuci</span>
                                <span class="text-[9px] opacity-75">Cuci basah/kering</span>
                            </div>
                        </label>
                        
                        <!-- Setrika -->
                        <label :class="serviceForm.needs_ironing ? 'border-brand-500 bg-brand-50/50 text-brand-700 shadow-sm shadow-brand-50' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                               class="flex items-center gap-2.5 p-3 border-2 rounded-xl cursor-pointer transition-all duration-200">
                            <input type="checkbox" x-model="serviceForm.needs_ironing" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold">Setrika</span>
                                <span class="text-[9px] opacity-75">Suhu & uap</span>
                            </div>
                        </label>
                        
                        <!-- Packing (Mandatory / Locked) -->
                        <label class="flex items-center gap-2.5 p-3 border-2 border-slate-200 bg-slate-50 text-slate-400 rounded-xl cursor-not-allowed opacity-80 transition-all duration-200">
                            <input type="checkbox" checked disabled class="w-4 h-4 text-slate-400 rounded border-slate-300 cursor-not-allowed">
                            <div class="flex flex-col text-left">
                                <span class="text-xs font-bold text-slate-500">Packing</span>
                                <span class="text-[9px] text-slate-400 font-medium">Langkah wajib</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 flex gap-3 justify-end bg-slate-50/50">
                <button @click="showServiceModal = false"
                        class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                    Batal
                </button>
                <button @click="saveService()"
                        :disabled="!serviceForm.nama || !serviceForm.harga || savingService"
                        class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-brand-700 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-brand-100 transition duration-200 flex items-center gap-2">
                    <svg x-show="savingService" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="serviceForm.id ? 'Simpan Perubahan' : 'Tambah Layanan'"></span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function posApp() {
    return {
        // Services data from backend
        services: @json($layanansJson),

        // State
        activeCategory: 'semua',
        cart: [],
        customerSearch: '',
        customerResults: [],
        selectedCustomer: null,
        showDropdown: false,
        showNewCustomerModal: false,
        paymentMethod: 'tunai',
        paymentStatus: 'belum_bayar',
        cashReceived: '',
        discount: 0,
        petugasList: @json($petugasList->map(fn($p) => ['nama' => $p->nama, 'id_petugas' => $p->id_petugas])),
        kasirSearch: '',
        showKasirDropdown: false,
        isKasirInvalid: true,
        filteredKasirList: [],
        viewMode: 'order', // 'order' or 'pickup'
        submitting: false,
        currentDate: '',
        newCustomer: { nama: '', no_hp: '', alamat: '' },

        // Service management state
        showServiceModal: false,
        savingService: false,
        serviceForm: { id: null, nama: '', kategori: 'kiloan', harga: '', satuan: '/kg', estimasi: '', icon: 'bolt', needs_washing: true, needs_ironing: true, needs_packing: true },

        init() {
            const d = new Date();
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            this.currentDate = `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()} • ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
            
            // Initialize cashier list
            this.filteredKasirList = this.petugasList;
            
            // Load saved cashier name
            let savedKasir = localStorage.getItem('petugas_kasir_name');
            if (!savedKasir && "{{ auth()->check() }}") {
                savedKasir = "{{ auth()->user()->name }}";
            }
            if (savedKasir) {
                this.kasirSearch = savedKasir;
                const exactMatch = this.petugasList.some(p => p.nama.toLowerCase() === savedKasir.toLowerCase().trim());
                this.isKasirInvalid = !exactMatch;
            }
        },

        filterKasir() {
            const q = this.kasirSearch.toLowerCase().trim();
            if (q === '') {
                this.filteredKasirList = this.petugasList;
                this.isKasirInvalid = true;
            } else {
                this.filteredKasirList = this.petugasList.filter(p => p.nama.toLowerCase().includes(q));
                const exactMatch = this.petugasList.find(p => p.nama.toLowerCase() === q);
                this.isKasirInvalid = !exactMatch;
            }
        },

        selectKasir(p) {
            this.kasirSearch = p.nama;
            this.showKasirDropdown = false;
            this.isKasirInvalid = false;
            localStorage.setItem('petugas_kasir_name', p.nama);
        },

        // Computed
        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        },
        get totalTagihan() {
            return Math.max(0, this.subtotal - (Number(this.discount) || 0));
        },
        get changeAmount() {
            if (!this.cashReceived || this.cashReceived < this.totalTagihan) {
                return 0;
            }
            return this.cashReceived - this.totalTagihan;
        },
        get isPaymentInvalid() {
            if (this.paymentStatus === 'lunas' && this.paymentMethod === 'tunai') {
                return !this.cashReceived || this.cashReceived < this.totalTagihan;
            }
            return false;
        },

        // Methods
        formatRupiah(n) {
            return 'Rp ' + Math.round(n).toLocaleString('id-ID');
        },

        isInCart(id) {
            return this.cart.some(i => i.id === id);
        },

        toggleService(id) {
            const idx = this.cart.findIndex(i => i.id === id);
            if (idx >= 0) {
                this.cart.splice(idx, 1);
            } else {
                const svc = this.services.find(s => s.id === id);
                if (svc) {
                    // Jika tipe layanan baru adalah kiloan:
                    if (svc.kategori === 'kiloan') {
                        if (svc.needs_washing) {
                            // Layanan Kiloan yang butuh cuci: keluarkan layanan Kiloan butuh cuci lainnya
                            this.cart = this.cart.filter(item => !(item.kategori === 'kiloan' && item.needs_washing));
                        } else {
                            // Layanan Kiloan yang hanya setrika (tidak butuh cuci): keluarkan layanan Kiloan hanya setrika lainnya
                            this.cart = this.cart.filter(item => !(item.kategori === 'kiloan' && !item.needs_washing));
                        }
                    }
                    this.cart.push({ ...svc, qty: 1 });
                }
            }
        },

        incrementQty(index) {
            this.cart[index].qty = parseFloat((this.cart[index].qty + (this.cart[index].kategori === 'kiloan' ? 0.5 : 1)).toFixed(2));
        },

        decrementQty(index) {
            const step = this.cart[index].kategori === 'kiloan' ? 0.5 : 1;
            if (this.cart[index].qty > step) {
                this.cart[index].qty = parseFloat((this.cart[index].qty - step).toFixed(2));
            }
        },

        updateQty(index, val) {
            const parsed = parseFloat(val);
            if (!isNaN(parsed) && parsed > 0) {
                this.cart[index].qty = parseFloat(parsed.toFixed(2));
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        async searchCustomers() {
            if (this.customerSearch.length < 1) {
                this.customerResults = [];
                this.showDropdown = false;
                return;
            }
            try {
                const res = await fetch(`{{ route('pos.customer.search') }}?q=${encodeURIComponent(this.customerSearch)}`);
                this.customerResults = await res.json();
                this.showDropdown = true;
            } catch (e) {
                console.error(e);
            }
        },

        selectCustomer(c) {
            this.selectedCustomer = c;
            this.showDropdown = false;
            this.customerSearch = '';
        },

        async saveNewCustomer() {
            try {
                const res = await fetch('{{ route("pos.customer.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.newCustomer),
                });
                if (!res.ok) throw new Error('Failed');
                const customer = await res.json();
                this.selectedCustomer = customer;
                this.showNewCustomerModal = false;
                this.newCustomer = { nama: '', no_hp: '', alamat: '' };
            } catch (e) {
                alert('Gagal menyimpan customer. Pastikan data sudah benar.');
                console.error(e);
            }
        },

        async submitOrder() {
            if (!this.selectedCustomer || this.cart.length === 0 || this.isKasirInvalid) return;
            this.submitting = true;

            const payload = {
                customer_id: this.selectedCustomer.id,
                items: this.cart.map(i => ({ layanan_id: i.id, qty: i.qty })),
                payment_method: this.paymentMethod,
                payment_status: this.paymentStatus,
                kasir_name: this.kasirSearch,
                discount: this.discount || 0,
                dibayar: this.paymentMethod === 'tunai' ? (this.cashReceived || 0) : this.totalTagihan,
                kembalian: this.paymentMethod === 'tunai' ? this.changeAmount : 0,
            };

            try {
                const res = await fetch('{{ route("pos.order.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                if (!res.ok) {
                    const errorText = await res.text();
                    console.error('Server response:', errorText);
                    throw new Error('Server returned ' + res.status);
                }

                // Jika sukses, Laravel akan mengembalikan response JSON yang berisi redirect url
                // Tapi PosController@store saat ini mengembalikan `redirect()->route('pos.nota')`
                // Fetch secara default akan mengikuti redirect (res.redirected === true)
                // dan mengembalikan isi HTML dari pos.nota.
                // Untuk amannya, kita paksa window location ke URL dari response tersebut:
                window.location.href = res.url;
                
            } catch (e) {
                this.submitting = false;
                alert('Terjadi kesalahan pada server. Cek console browser untuk detailnya.');
                console.error(e);
            }
        },

        // Service Management Methods
        openAddServiceModal() {
            this.serviceForm = { id: null, nama: '', kategori: 'kiloan', harga: '', satuan: '/kg', estimasi: '', icon: 'bolt', needs_washing: true, needs_ironing: true, needs_packing: true };
            this.showServiceModal = true;
        },

        openEditServiceModal(layanan) {
            this.serviceForm = {
                id: layanan.id,
                nama: layanan.nama,
                kategori: layanan.kategori,
                harga: layanan.harga,
                satuan: layanan.satuan,
                estimasi: layanan.estimasi || '',
                icon: layanan.icon || 'bolt',
                needs_washing: !!layanan.needs_washing,
                needs_ironing: !!layanan.needs_ironing,
                needs_packing: true, // Always force true for packing
            };
            this.showServiceModal = true;
        },

        async saveService() {
            this.savingService = true;
            try {
                const url = this.serviceForm.id
                    ? `/admin/layanan/${this.serviceForm.id}`
                    : '{{ route("admin.layanan.store") }}';

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        ...this.serviceForm,
                        needs_packing: true, // Always force true when saving to DB
                        _method: this.serviceForm.id ? 'PUT' : 'POST'
                    }),
                });

                if (!res.ok) throw new Error('Failed');
                const data = await res.json();

                if (this.serviceForm.id) {
                    // Update existing
                    const idx = this.services.findIndex(s => s.id === data.layanan.id);
                    if (idx !== -1) this.services[idx] = data.layanan;

                    // Update in cart too if exists
                    const cartIdx = this.cart.findIndex(i => i.id === data.layanan.id);
                    if (cartIdx !== -1) {
                        const oldQty = this.cart[cartIdx].qty;
                        this.cart[cartIdx] = { ...data.layanan, qty: oldQty };
                    }
                } else {
                    // Add new
                    this.services.push(data.layanan);
                }

                this.showServiceModal = false;
                // Reload page to update the blade-rendered service grid (if needed)
                // or we can rely on Alpinjs but notice the grid is blade-rendered with a foreach loop.
                // For better UX, let's just reload.
                window.location.reload();
            } catch (e) {
                alert('Gagal menyimpan layanan.');
                console.error(e);
            } finally {
                this.savingService = false;
            }
        }
    };
}
</script>
@endpush
