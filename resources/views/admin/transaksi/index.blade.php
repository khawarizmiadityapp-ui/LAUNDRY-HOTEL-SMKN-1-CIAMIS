@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.petugas_piket')

@section('title', 'Manajemen Transaksi - Bening Laundry')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Transaksi</h1>
        <p class="text-slate-500 text-xs md:text-sm mt-0.5">Kelola pesanan masuk, rincian pelayanan, dan status pengerjaan laundry.</p>
    </div>
</div>

<!-- Search & Filters -->
<div x-data="transaksiApp()" class="w-full">
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
    <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari transaksi, pelanggan..."
                   class="w-full pl-9 pr-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-10">
        </div>

        <select name="status" onchange="this.form.submit()" 
                class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[140px] cursor-pointer">
            <option value="">Semua Status</option>
            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="disortir" {{ request('status') == 'disortir' ? 'selected' : '' }}>Disortir</option>
            <option value="dicuci" {{ request('status') == 'dicuci' ? 'selected' : '' }}>Dicuci</option>
            <option value="dikeringkan" {{ request('status') == 'dikeringkan' ? 'selected' : '' }}>Dikeringkan</option>
            <option value="disetrika" {{ request('status') == 'disetrika' ? 'selected' : '' }}>Ironing</option>
            <option value="dipacking" {{ request('status') == 'dipacking' ? 'selected' : '' }}>Dipacking</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
        </select>

        <select name="payment_status" onchange="this.form.submit()" 
                class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[150px] cursor-pointer">
            <option value="">Semua Pembayaran</option>
            <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum Lunas</option>
        </select>

        @if(request('search') || request('status') || request('payment_status'))
            <a href="{{ url()->current() }}" class="text-xs font-semibold text-rose-500 hover:text-rose-600 transition">Reset Filter</a>
        @endif
    </form>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto min-h-[300px]">
        <table class="w-full text-sm text-slate-600 min-w-[950px] table-auto">
        <thead class="bg-slate-50 text-slate-700 text-xs font-semibold uppercase">
            <tr>
                <th class="px-6 py-4 text-left whitespace-nowrap">Pelanggan</th>
                <th class="px-6 py-4 text-left whitespace-nowrap">Layanan</th>
                <th class="px-6 py-4 text-left whitespace-nowrap">Berat / Qty</th>
                <th class="px-6 py-4 text-left whitespace-nowrap">Status Proses</th>
                <th class="px-6 py-4 text-left whitespace-nowrap">Pembayaran</th>
                <th class="px-6 py-4 text-left whitespace-nowrap">Metode</th>
                <th class="px-6 py-4 text-left whitespace-nowrap">Total</th>
                <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
    
        <tbody class="divide-y divide-slate-100">
            @forelse($transactions as $trx)
            <tr class="hover:bg-slate-50/70 transition cursor-pointer" @click="if (!$event.target.closest('button, a, form')) openDetail({{ $trx->id }})">
                
                <!-- Pelanggan -->
                <td class="px-6 py-4 align-middle">
                    <div class="font-bold text-slate-900 whitespace-nowrap">
                        {{ $trx->customer_name }}
                    </div>
                    <div class="text-xs font-mono text-blue-600 mt-0.5">
                        {{ $trx->transaksi_code }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-0.5">
                        {{ $trx->created_at->format('d M Y, H:i') }}
                    </div>
                </td>
    
                <!-- Layanan -->
                <td class="px-6 py-4 align-middle">
                    @if(isset($trx->details) && $trx->details->count() > 0)
                        <div class="text-xs font-semibold text-slate-800 space-y-1">
                        @foreach($trx->details as $detail)
                            <div class="whitespace-nowrap flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                {{ $detail->layanan->nama ?? 'Layanan' }} ({{ $detail->qty }}x)
                            </div>
                        @endforeach
                        </div>
                    @else
                        <span class="text-xs font-semibold text-slate-800 capitalize">{{ $trx->service_type }}</span>
                    @endif
                </td>
    
                <!-- Berat -->
                <td class="px-6 py-4 align-middle whitespace-nowrap text-slate-700 font-mono font-bold text-xs">
                    {{ number_format($trx->weight, 2, '.', ',') }} kg
                </td>
    
                <!-- Status -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    @php
                        $statusColor = match($trx->status) {
                            'selesai', 'diambil' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'diterima', 'disortir' => 'bg-slate-100 text-slate-700 border-slate-200',
                            default => 'bg-blue-100 text-blue-700 border-blue-200',
                        };
                    @endphp
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $statusColor }} capitalize">
                        {{ $trx->status }}
                    </span>
                </td>
    
                <!-- Pembayaran -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border 
                        {{ $trx->payment_status == 'lunas' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-rose-100 text-rose-700 border-rose-200' }}">
                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </td>
     
                <!-- Metode -->
                <td class="px-6 py-4 align-middle uppercase font-bold text-xs text-slate-700 whitespace-nowrap">
                    {{ str_replace('_', ' ', $trx->payment_method ?: 'Tunai') }}
                </td>
    
                <!-- Total -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <div class="font-bold text-slate-900 font-mono text-sm">
                        Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                    </div>
                    @if($trx->dibayar > 0)
                    <div class="text-[10px] text-slate-400 mt-0.5 leading-tight font-sans">
                        <span>Bayar:</span> Rp {{ number_format($trx->dibayar, 0, ',', '.') }}
                    </div>
                    @endif
                </td>
    
                <!-- Aksi -->
                <td class="px-6 py-4 align-middle text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Tombol Detail --}}
                        <button type="button" @click="openDetail({{ $trx->id }})"
                                class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-bold rounded-xl transition flex items-center gap-1 shadow-sm">
                            <i class="fas fa-eye text-xs"></i> Detail
                        </button>

                        {{-- Dropdown Actions --}}
                        <div class="relative inline-block text-left">
                            <button onclick="toggleDropdown('dropdown-{{ $trx->id }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-50 
                                           hover:bg-slate-100 text-slate-500 transition focus:outline-none">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                                </svg>
                            </button>
                            
                            <div id="dropdown-{{ $trx->id }}" class="hidden absolute right-0 top-full mt-1 w-44 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 py-1.5">
                                <button type="button" @click="openNota('{{ route('pos.nota', $trx->id) }}'); toggleDropdown('dropdown-{{ $trx->id }}')"
                                   class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    <i class="fas fa-receipt text-slate-400"></i> Cek Nota / Struk
                                </button>

                                @if(auth()->user()->isAdmin())
                                <div class="h-px bg-slate-100 my-1"></div>

                                <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 w-full text-left transition">
                                        <i class="fas fa-trash-alt text-rose-400"></i> Hapus Transaksi
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
    
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-slate-400">
                    <i class="fas fa-receipt text-3xl text-slate-300 block mb-2"></i>
                    Belum ada data transaksi ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $transactions->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL DETAIL TRANSAKSI LENGKAP                                      --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div x-show="showDetailModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showDetailModal = false"
             class="bg-white shadow-2xl w-full max-w-2xl mx-4 overflow-hidden rounded-2xl flex flex-col max-h-[90vh] animate-fade-up border border-slate-100">
            
            {{-- Header --}}
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white">
                        <i class="fas fa-file-invoice text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm tracking-wide uppercase">DETAIL TRANSAKSI</h3>
                            <span class="font-mono text-xs text-blue-400 font-bold" x-text="detailData?.transaksi_code"></span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5" x-text="'Dibuat: ' + detailData?.created_at + ' oleh ' + detailData?.kasir_name"></p>
                    </div>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            {{-- Content Scrollable --}}
            <div class="p-6 overflow-y-auto space-y-6 flex-1 text-slate-700 text-xs" x-show="detailData">
                
                {{-- Status Badges Row --}}
                <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 uppercase font-semibold text-[10px]">Status Pengerjaan:</span>
                        <span class="px-3 py-1 text-xs font-bold rounded-lg bg-blue-100 text-blue-700 capitalize" x-text="detailData?.status"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 uppercase font-semibold text-[10px]">Status Pembayaran:</span>
                        <span class="px-3 py-1 text-xs font-bold rounded-lg uppercase"
                              :class="detailData?.payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                              x-text="detailData?.payment_status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS'"></span>
                    </div>
                </div>

                {{-- Informasi Pelanggan --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-200">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <span class="font-bold text-slate-900 uppercase text-[11px] flex items-center gap-1.5">
                            <i class="fas fa-user-circle text-blue-600"></i> Informasi Pelanggan
                        </span>
                        <template x-if="detailData?.wa_url">
                            <a :href="detailData?.wa_url" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#25D366] hover:bg-[#128C7E] text-white font-bold text-[11px] rounded-lg transition shadow-sm">
                                <i class="fab fa-whatsapp"></i> Chat WhatsApp
                            </a>
                        </template>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nama Lengkap</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5" x-text="detailData?.customer_name"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nomor Handphone</span>
                            <p class="font-mono font-semibold text-slate-800 text-xs mt-0.5" x-text="detailData?.customer_phone || '-'"></p>
                        </div>
                    </div>
                </div>

                {{-- Rincian Item Layanan --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <span class="font-bold text-slate-900 uppercase text-[11px] flex items-center gap-1.5">
                            <i class="fas fa-tshirt text-blue-600"></i> Rincian Layanan Laundry
                        </span>
                        <span class="font-mono text-[11px] text-slate-500 font-semibold" x-text="'Total Berat: ' + detailData?.weight + ' kg'"></span>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                        <thead class="bg-slate-50/50 text-slate-600 font-semibold">
                            <tr>
                                <th class="px-4 py-2.5">Layanan</th>
                                <th class="px-4 py-2.5">Kategori</th>
                                <th class="px-4 py-2.5 text-center">Qty / Satuan</th>
                                <th class="px-4 py-2.5 text-right">Harga Satuan</th>
                                <th class="px-4 py-2.5 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <template x-for="(item, idx) in detailData?.items" :key="idx">
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-2.5 font-bold text-slate-900" x-text="item.nama"></td>
                                    <td class="px-4 py-2.5 uppercase text-[10px] text-slate-500" x-text="item.kategori"></td>
                                    <td class="px-4 py-2.5 text-center font-mono" x-text="item.qty + ' ' + item.satuan"></td>
                                    <td class="px-4 py-2.5 text-right font-mono" x-text="'Rp ' + item.harga.toLocaleString('id-ID')"></td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold text-slate-900" x-text="'Rp ' + item.subtotal.toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Rincian Keuangan & Pembayaran --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                        <span class="font-bold text-slate-900 uppercase text-[11px] block pb-1 border-b border-slate-200">
                            <i class="fas fa-credit-card text-blue-600 mr-1"></i> Rincian Pembayaran
                        </span>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Total Tagihan:</span>
                            <span class="font-mono font-bold text-slate-900 text-sm" x-text="'Rp ' + (detailData?.total_price || 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Metode Pembayaran:</span>
                            <span class="font-semibold text-slate-800 uppercase" x-text="detailData?.payment_method"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Jumlah Dibayar (Tunai):</span>
                            <span class="font-mono font-semibold text-slate-800" x-text="'Rp ' + (detailData?.dibayar || 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Kembalian:</span>
                            <span class="font-mono font-bold text-emerald-600" x-text="'Rp ' + (detailData?.kembalian || 0).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                        <span class="font-bold text-slate-900 uppercase text-[11px] block pb-1 border-b border-slate-200">
                            <i class="fas fa-sticky-note text-amber-500 mr-1"></i> Catatan & Bukti Bayar
                        </span>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Catatan Pesanan</span>
                            <p class="text-slate-700 italic mt-0.5" x-text="detailData?.notes || 'Tidak ada catatan khusus.'"></p>
                        </div>
                        <template x-if="detailData?.bukti_pembayaran_url">
                            <div class="pt-2">
                                <a :href="detailData?.bukti_pembayaran_url" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-sm text-xs">
                                    <i class="fas fa-image"></i> Lihat Bukti Transfer
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Workflow Pengerjaan Petugas (Tasks) --}}
                <template x-if="detailData?.tasks && detailData?.tasks.length > 0">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="font-bold text-slate-900 uppercase text-[11px] block mb-3">
                            <i class="fas fa-tasks text-blue-600 mr-1"></i> Tahapan Pengerjaan Petugas Piket
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <template x-for="(task, tIdx) in detailData?.tasks" :key="tIdx">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="font-bold uppercase text-[11px] text-slate-800 block" x-text="task.stage"></span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md inline-block mt-1 capitalize"
                                          :class="task.status === 'completed' ? 'bg-emerald-100 text-emerald-700' : (task.status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-700')"
                                          x-text="task.status"></span>
                                    <p class="text-[10px] text-slate-500 mt-1.5" x-text="'Petugas: ' + task.petugas"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

            </div>

            {{-- Footer Action Buttons --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 shrink-0">
                <button type="button" @click="showDetailModal = false"
                        class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition text-xs">
                    Tutup
                </button>

                <div class="flex items-center gap-2">
                    <template x-if="detailData?.wa_url">
                        <a :href="detailData?.wa_url" target="_blank"
                           class="px-4 py-2 bg-[#25D366] hover:bg-[#128C7E] text-white font-bold rounded-xl transition shadow-sm flex items-center gap-1.5 text-xs">
                            <i class="fab fa-whatsapp"></i> Chat WhatsApp
                        </a>
                    </template>

                    <button type="button" @click="openNota(detailData?.nota_url)"
                            class="px-4 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl transition shadow-sm flex items-center gap-1.5 text-xs">
                        <i class="fas fa-print"></i> Cetak Struk / Nota
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ NOTA MODAL ═══════════ --}}
    <div x-show="showNotaModal" x-cloak
         class="fixed inset-0 z-[110] flex items-center justify-center modal-overlay bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="closeNotaModal()" class="bg-white shadow-2xl w-full max-w-[400px] mx-4 animate-fade-up overflow-hidden rounded-2xl flex flex-col h-[85vh]">
            {{-- Header --}}
            <div class="px-5 py-4 flex items-center justify-between bg-[#0b172a] text-white shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="font-bold text-xs tracking-widest uppercase">STRUK NOTA / INVOICE TRANSAKSI</h3>
                </div>
                <button @click="closeNotaModal()" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            {{-- Iframe Container --}}
            <div class="flex-1 overflow-hidden relative bg-slate-50">
                <template x-if="notaUrl">
                    <iframe x-ref="notaIframe" :src="notaUrl" class="w-full h-full border-0 absolute inset-0"></iframe>
                </template>
            </div>
            
            {{-- Footer / Actions --}}
            <div class="px-5 py-3.5 border-t border-slate-100 flex gap-2 justify-center bg-white shrink-0">
                <button @click="kirimWa()"
                        class="px-4 py-2 bg-[#25D366] text-white text-xs font-bold rounded-full hover:bg-[#128C7E] transition shadow-md flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Kirim WA
                </button>
                <button @click="printNota()"
                        class="px-5 py-2 bg-black text-white text-xs font-bold rounded-full hover:bg-slate-800 transition shadow-md flex items-center gap-1.5 active:scale-95">
                    <i class="fas fa-print"></i> Cetak
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
    window.toggleDropdown = window.toggleDropdown || function(id) {
        const dropdown = document.getElementById(id);
        if (dropdown.classList.contains('hidden')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    };

    document.addEventListener('click', function(event) {
        const toggleBtn = event.target.closest('button');
        let isDropdownToggle = false;
        if (toggleBtn) {
            const attrs = ['onclick', '@click', 'x-on:click'];
            isDropdownToggle = attrs.some(attr => {
                const val = toggleBtn.getAttribute(attr);
                return val && val.includes('toggleDropdown');
            });
        }
        if (!isDropdownToggle) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (!el.contains(event.target)) {
                    el.classList.add('hidden');
                }
            });
        }
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('transaksiApp', () => ({
            showNotaModal: false,
            notaUrl: '',
            showDetailModal: false,
            detailData: null,

            openDetail(id) {
                fetch(`/admin/transaksi/${id}/detail`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.detailData = data.data;
                            this.showDetailModal = true;
                        }
                    })
                    .catch(err => alert('Gagal memuat detail transaksi.'));
            },
            
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
