@extends('layouts.petugas_piket')
@section('title', 'Packing Operations')
@section('content')

<div class="p-6 max-w-5xl mx-auto animate-fade-in">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Operasi Packing</h1>
        <p class="text-slate-500 mt-1">Selesaikan tugas pengemasan yang berada di antrean.</p>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Antrean Packing</h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                {{ count($transactions) }} Menunggu
            </span>
        </div>

        <div class="p-6">
            @if(count($transactions) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($transactions as $trx)
                        <div class="border border-slate-200 rounded-2xl p-5 hover:shadow-md transition-shadow duration-300 relative bg-white">
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-lg mb-2">
                                        #{{ $trx->transaksi_code }}
                                    </span>
                                    <h3 class="font-bold text-lg text-slate-800 leading-tight">{{ $trx->customer_name }}</h3>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Masuk</span>
                                    <span class="text-xs font-bold text-slate-600">{{ $trx->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="mb-6 bg-slate-50 rounded-xl p-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-600 font-medium">Layanan</span>
                                    <span class="font-bold text-slate-800 capitalize">{{ $trx->service_type }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm mt-2">
                                    <span class="text-slate-600 font-medium">Berat</span>
                                    <span class="font-bold text-slate-800">{{ number_format($trx->weight,1) }} kg</span>
                                </div>
                            </div>

                            {{-- Action Form --}}
                            <form action="{{ route('petugas_piket.tasks.complete', $trx->id) }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="stage" value="packing">
                                
                                <div class="mb-4" x-data="petugasSearchComponent({{ json_encode($petugasList->map(fn($p) => ['nama' => $p->nama, 'id_petugas' => $p->id_petugas])) }})" @click.outside="showDropdown = false">
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Petugas Piket</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </span>
                                        <input type="text" name="petugas_name" x-model="search" @input="filterPetugas()" @focus="showDropdown = true" required placeholder="Cari nama Anda..."
                                               class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors bg-white">
                                    </div>
                                    
                                    {{-- Autocomplete Dropdown --}}
                                    <div x-show="showDropdown && filteredList.length > 0" x-cloak
                                         class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
                                        <template x-for="p in filteredList" :key="p.id_petugas">
                                            <button type="button" @click="select(p)"
                                                    class="w-full flex items-center gap-3 px-4 py-2 hover:bg-slate-50 transition text-left">
                                                <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold" x-text="p.nama.charAt(0).toUpperCase()"></div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-slate-800 truncate" x-text="p.nama"></p>
                                                    <p class="text-xs text-slate-400" x-text="p.id_petugas"></p>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                    
                                    {{-- Error Warning --}}
                                    <div x-show="isInvalid" x-cloak class="mt-1.5 text-xs text-rose-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                        </svg>
                                        <span>Petugas tidak terdaftar</span>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-3 rounded-xl transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                    Konfirmasi Selesai Pack
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak ada antrean packing</h3>
                    <p class="text-slate-500 text-sm">Semua pakaian telah selesai dipacking. Kerja bagus!</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>
@endpush
