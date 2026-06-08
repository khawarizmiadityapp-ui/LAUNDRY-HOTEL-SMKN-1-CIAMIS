@extends('layouts.petugas_piket')
@section('title', 'Washing')
@section('content')

<div class="p-6 max-w-5xl mx-auto animate-fade-in">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Operasi Pencucian</h1>
        <p class="text-slate-500 mt-1">Selesaikan tugas pencucian yang berada di antrean.</p>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
            <h2 class="text-lg font-bold text-slate-800">Antrean Cucian</h2>
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

                            {{-- Service List --}}
                            <div class="mb-6 bg-slate-50 rounded-xl p-4">
                                <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Detail Layanan</h4>
                                <ul class="space-y-2">
                                    @foreach($trx->details as $detail)
                                        <li class="flex items-center justify-between text-sm">
                                            <span class="text-slate-600 font-medium">{{ $detail->layanan->nama ?? 'Layanan' }}</span>
                                            <span class="font-bold text-slate-800">{{ $detail->qty }} {{ $detail->layanan->satuan ?? 'kg' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Action Form --}}
                            <div x-data="{ 
                                materials: [],
                                availableMaterials: {{ json_encode($inventories->map(fn($i) => ['id' => $i->id, 'name' => $i->name, 'category' => $i->category, 'unit' => $i->unit_of_measurement])) }},
                                addMaterial() { this.materials.push({ id: '', quantity: 1 }); },
                                removeMaterial(index) { this.materials.splice(index, 1); },
                                confirmSubmission(e) {
                                    if (this.materials.length > 0) {
                                        let msg = 'Anda akan mencatat penggunaan bahan tambahan:\n';
                                        this.materials.forEach((m) => {
                                            let item = this.availableMaterials.find(x => x.id == m.id);
                                            if(item) {
                                                let unit = item.unit || 'ml';
                                                msg += `- ${item.name}: ${m.quantity} ${unit}\n`;
                                            }
                                        });
                                        msg += '\nApakah jumlah bahan yang dipakai ini sudah sesuai?';
                                        if (!confirm(msg)) {
                                            return;
                                        }
                                    }
                                    e.target.submit();
                                }
                            }">
                                <form action="{{ route('petugas_piket.tasks.complete', $trx->id) }}" method="POST" class="mt-auto" @submit.prevent="confirmSubmission($event)">
                                @csrf
                                <input type="hidden" name="stage" value="washing">
                                
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
                                    </div>
                                    
                                    {{-- Error Warning --}}
                                    <div x-show="isInvalid" x-cloak class="mt-1.5 text-xs text-rose-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                        </svg>
                                        <span>Petugas tidak terdaftar</span>
                                    </div>
                                </div>

                                {{-- Multi-select Bahan (Inventory) --}}
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Penggunaan Bahan</label>
                                        <button type="button" @click="addMaterial()" class="text-xs text-blue-600 font-bold hover:text-blue-700 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Tambah Bahan
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <template x-for="(material, index) in materials" :key="index">
                                            <div class="flex gap-2 items-start bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <div class="flex-1">
                                                    <select x-model="material.id" :name="`materials[${index}][id]`" required
                                                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                                                        <option value="" disabled>Pilih Bahan...</option>
                                                        <template x-for="item in availableMaterials" :key="item.id">
                                                            <option :value="item.id" x-text="`${item.name} (${item.category})`"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="w-24">
                                                    <div class="relative">
                                                        <input type="number" x-model="material.quantity" :name="`materials[${index}][quantity]`" required min="0.1" step="0.1" placeholder="Qty"
                                                            class="w-full pl-3 pr-8 py-2 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                                                        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-xs text-slate-400 font-medium" x-text="availableMaterials.find(x => x.id == material.id)?.unit || 'ml'"></span>
                                                    </div>
                                                </div>
                                                <button type="button" @click="removeMaterial(index)" class="p-2 text-rose-500 hover:text-rose-600 hover:bg-rose-100 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <template x-if="materials.length === 0">
                                            <p class="text-xs text-slate-400 text-center py-2 italic border border-dashed border-slate-200 rounded-xl">Belum ada bahan tambahan. (Otomatis potong 1 unit jika kosong)</p>
                                        </template>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-3 rounded-xl transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                    Konfirmasi Selesai Cuci
                                </button>
                            </form>
                            </div>
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
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak ada antrean cucian</h3>
                    <p class="text-slate-500 text-sm">Semua pakaian telah selesai dicuci. Kerja bagus!</p>
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