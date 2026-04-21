@extends('layouts.petugas_piket')
@section('title', 'Delivery Dashboard')

@push('styles')
<style>
    .progress-bar { transition: width 1s cubic-bezier(0.4,0,0.2,1); }
    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
        70% { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); }
    }
    .pulse-ring { animation: pulse-ring 2s infinite; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeInUp 0.5s ease forwards; }
    .fade-in-1 { animation-delay: 0.05s; opacity: 0; }
    .fade-in-2 { animation-delay: 0.12s; opacity: 0; }
    .fade-in-3 { animation-delay: 0.20s; opacity: 0; }
    .fade-in-4 { animation-delay: 0.28s; opacity: 0; }
    .fade-in-5 { animation-delay: 0.36s; opacity: 0; }
</style>
@endpush



@section('content')
<div class="flex flex-col gap-6">
    <div class="flex gap-5">
        <!-- LEFT / CENTER -->
        <div class="flex-1 space-y-5 min-w-0">
                <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">delivery</h1>
                <p class="text-slate-500 mt-1">Daftar pesanan yang siap untuk antar dan jemput</p>
            </div>
        </div>
            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl border border-gray-100 p-5 fade-in fade-in-1 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Tingkat Penyelesaian</p>
                    <div class="flex items-end justify-between">
                        <p class="text-4xl font-extrabold text-gray-900 leading-none">98%</p>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-semibold">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                            </svg>
                            +2.4%
                        </span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl border border-gray-100 p-5 fade-in fade-in-2 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Rerata Waktu Kirim</p>
                    <div class="flex items-end gap-2">
                        <p class="text-4xl font-extrabold text-gray-900 leading-none">42 <span class="text-lg font-semibold text-gray-500">mnt</span></p>
                    </div>
                </div>

                <!-- Card 3 (Orange Gradient) -->
                <div class="rounded-2xl p-5 relative overflow-hidden text-white fade-in fade-in-3 shadow-lg"
                     style="background: linear-gradient(135deg, #f97316 0%, #ea6a08 100%);">
                    <p class="text-[10px] font-bold text-orange-100 uppercase tracking-widest mb-2 relative">Status Armada</p>
                    <p class="text-2xl font-extrabold leading-tight relative">12 Kurir</p>
                </div>
            </div>

            <!-- RUTE PENGIRIMAN AKTIF -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5 fade-in fade-in-3 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-800">Rute Pengiriman Aktif</h2>
                </div>
                <!-- Simplified route display for now -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl border border-orange-200 bg-orange-50/30">
                        <p class="font-bold text-gray-800">Rute A: Jakarta Pusat</p>
                        <p class="text-xs text-gray-500 mb-2">Kurir: Budi Santoso</p>
                        <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl border border-blue-200 bg-blue-50/30">
                        <p class="font-bold text-gray-800">Rute B: Jakarta Barat</p>
                        <p class="text-xs text-gray-500 mb-2">Kurir: Siti Aminah</p>
                        <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500" style="width: 88%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- REAL DATA SECTION -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5 fade-in fade-in-4 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-800">Siap Dikirim / Diambil</h2>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-orange-50 text-orange-600">{{ $transactions->count() }} Pesanan</span>
                </div>
                <div class="space-y-3">
                    @forelse($transactions as $trx)
                        <div class="flex items-center gap-3 p-4 rounded-2xl border border-gray-100 bg-gray-50/50 group hover:bg-white hover:shadow-md transition-all">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-800">{{ $trx->transaksi_code }}</span>
                                    <span class="text-xs text-gray-500">• {{ $trx->customer_name }}</span>
                                </div>
                                <p class="text-xs text-gray-400 truncate">{{ $trx->service_type }} • {{ $trx->weight }}kg</p>
                            </div>
                            <form action="{{ route('petugas_piket.tasks.updateStatus', $trx->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="diambil">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                    Selesai
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-400 text-sm">Tidak ada pesanan siap kirim.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>


    </div>
</div>
@endsection