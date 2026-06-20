@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas_piket')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Transaksi</h1>
        <p class="text-slate-500">Kelola pesanan masuk dan status pengerjaan.</p>
    </div>
</div>

<!-- Search & Filters -->
<div class="bg-white p-4 rounded-xl border shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
    <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari transaksi, pelanggan..."
                   class="w-full pl-9 pr-4 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-10">
        </div>

        <select name="status" onchange="this.form.submit()" 
                class="text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[140px] cursor-pointer">
            <option value="">Semua Status</option>
            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="disortir" {{ request('status') == 'disortir' ? 'selected' : '' }}>Disortir</option>
            <option value="dicuci" {{ request('status') == 'dicuci' ? 'selected' : '' }}>Dicuci</option>
            <option value="dikeringkan" {{ request('status') == 'dikeringkan' ? 'selected' : '' }}>Dikeringkan</option>
            <option value="disetrika" {{ request('status') == 'disetrika' ? 'selected' : '' }}>Disetrika</option>
            <option value="dipacking" {{ request('status') == 'dipacking' ? 'selected' : '' }}>Dipacking</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
        </select>

        <select name="payment_status" onchange="this.form.submit()" 
                class="text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[150px] cursor-pointer">
            <option value="">Semua Pembayaran</option>
            <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum Lunas</option>
        </select>

        @if(request('search') || request('status') || request('payment_status'))
            <a href="{{ url()->current() }}" class="text-xs font-semibold text-red-500 hover:text-red-600 transition">Reset Filter</a>
        @endif
    </form>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow-sm border">
    <div class="overflow-x-auto min-h-[300px]">
        <table class="w-full text-sm text-slate-600 min-w-[950px] table-auto">
        <thead class="bg-slate-50 text-slate-700">
            <tr>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[20%]">Pelanggan</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[25%]">Layanan</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[10%]">Berat</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[12%]">Status</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[13%]">Pembayaran</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[10%]">Metode</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[10%]">Total</th>
                <th class="px-6 py-4 text-right whitespace-nowrap font-semibold w-[5%]">Aksi</th>
            </tr>
        </thead>
    
        <tbody class="divide-y divide-slate-100">
            @forelse($transactions as $trx)
            <tr class="hover:bg-slate-50/50 transition">
                
                <!-- Pelanggan -->
                <td class="px-6 py-4 align-middle">
                    <div class="font-semibold text-slate-800 whitespace-nowrap">
                        {{ $trx->customer_name }}
                    </div>
                    <div class="text-xs text-slate-400 whitespace-nowrap mt-0.5">
                        {{ $trx->created_at->format('d M Y') }}
                    </div>
                </td>
    
                <!-- Layanan -->
                <td class="px-6 py-4 align-middle capitalize">
                    @if(isset($trx->details) && $trx->details->count() > 0)
                        <ul class="text-xs list-disc pl-4 text-slate-600 space-y-1">
                        @foreach($trx->details as $detail)
                            <li class="whitespace-nowrap">{{ $detail->layanan->nama ?? 'Layanan' }} ({{ $detail->qty }}x)</li>
                        @endforeach
                        </ul>
                    @else
                        <span class="whitespace-nowrap">{{ $trx->service_type }}</span>
                    @endif
                </td>
    
                <!-- Berat -->
                <td class="px-6 py-4 align-middle whitespace-nowrap text-slate-700 font-medium">
                    {{ number_format($trx->weight, 2, '.', ',') }} kg
                </td>
    
                <!-- Status -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700">
                        {{ $trx->status }}
                    </span>
                </td>
    
                <!-- Pembayaran -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg 
                        {{ $trx->payment_status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </td>
     
                <!-- Metode -->
                <td class="px-6 py-4 align-middle uppercase font-bold text-xs text-slate-700 whitespace-nowrap">
                    {{ $trx->payment_method ?? 'Cash' }}
                </td>
    
                <!-- Total -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <div class="font-semibold text-slate-800">
                        Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                    </div>
                    @if($trx->dibayar > 0)
                    <div class="text-[11px] text-slate-500 mt-1 leading-tight">
                        <span class="text-slate-400">Tunai:</span> Rp {{ number_format($trx->dibayar, 0, ',', '.') }}<br>
                        <span class="text-emerald-500 font-medium">Kembali: Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </td>
    
                <!-- Aksi -->
                <td class="px-6 py-4 align-middle text-right whitespace-nowrap">
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown('dropdown-{{ $trx->id }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                                       hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                            </svg>
                        </button>
                        
                        <div id="dropdown-{{ $trx->id }}" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                            <a href="{{ route('pos.nota', $trx->id) }}" target="_blank"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Cek Nota
                            </a>
 
                            @if(auth()->user()->role === 'admin')
                            <div class="h-px bg-slate-50 my-1"></div>
 
                            <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
    
            @empty
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-400">
                    Belum ada data
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="bg-white rounded-xl shadow-sm border mt-4 p-4">
        {{ $transactions->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    window.toggleDropdown = window.toggleDropdown || function(id) {
        const dropdown = document.getElementById(id);
        if (dropdown.classList.contains('hidden')) {
            // Close all other dropdowns
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
</script>
@endpush

@endsection
