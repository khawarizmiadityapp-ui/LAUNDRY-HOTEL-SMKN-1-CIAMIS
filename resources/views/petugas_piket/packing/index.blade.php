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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
            <h2 class="text-lg font-bold text-slate-800">Antrean Packing</h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                {{ count($transactions) }} Menunggu
            </span>
        </div>

        <div class="p-6">
            @if(count($transactions) > 0)
                <div class="overflow-visible">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="px-6 py-4 font-semibold rounded-tl-xl">Kode & Pelanggan</th>
                                <th class="px-6 py-4 font-semibold">Layanan & Berat</th>
                                <th class="px-6 py-4 font-semibold">Waktu Masuk</th>
                                <th class="px-6 py-4 font-semibold rounded-tr-xl w-[280px]">Aksi & Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($transactions as $trx)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-5 align-top">
                                        <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-lg mb-2">
                                            #{{ $trx->transaksi_code }}
                                        </span>
                                        <h3 class="font-bold text-lg text-slate-800 leading-tight">{{ $trx->customer_name }}</h3>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="font-bold text-slate-800 capitalize mb-1">{{ $trx->service_type }}</div>
                                        <div class="text-sm text-slate-500">{{ number_format($trx->weight,1) }} kg</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="text-sm font-semibold text-slate-600">{{ $trx->created_at->diffForHumans() }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-wider mt-1">Masuk</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <form action="{{ route('petugas_piket.tasks.complete', $trx->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="stage" value="packing">
                                            
                                            <div class="mb-3" x-data="petugasSearchComponent({{ json_encode($petugasList->map(fn($p) => ['nama' => $p->nama, 'id_petugas' => $p->id_petugas])) }})" @click.outside="showDropdown = false">
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                        </svg>
                                                    </span>
                                                    <input type="text" name="petugas_name" x-model="search" @input="filterPetugas()" @focus="showDropdown = true" required placeholder="Nama petugas..."
                                                           class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors bg-white">
                                                    
                                                    {{-- Autocomplete Dropdown --}}
                                                    <div x-show="showDropdown && filteredList.length > 0" x-cloak
                                                         class="absolute right-0 left-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
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
                                                    class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-xl transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                                Selesai
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
