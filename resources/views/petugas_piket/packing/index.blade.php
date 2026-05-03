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
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Petugas Piket</label>
                                    <input type="text" name="petugas_name" placeholder="Masukkan nama Anda..." required
                                           class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
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
