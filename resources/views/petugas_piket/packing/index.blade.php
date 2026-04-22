@extends('layouts.petugas_piket')
@section('title', 'Packing Operations')

@section('content')
<div class="px-4 py-8 max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Packing Operations</h1>
            <p class="text-slate-500 mt-1">Daftar pesanan yang siap untuk proses packing.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($transactions as $trx)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-lg font-bold text-slate-800">{{ $trx->transaksi_code }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ strtoupper($trx->service_type) }}</span>
                        </div>
                        <p class="text-sm text-slate-500 font-medium">{{ $trx->customer_name }} • {{ number_format($trx->weight,1) }} kg</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Current Status</p>
                        <p class="text-sm font-bold text-slate-700 capitalize">{{ $trx->status }}</p>
                    </div>
                    
                    <form action="{{ route('petugas_piket.tasks.complete', $trx->id) }}" method="POST">
                        @csrf
                        {{-- Mark as ready for delivery --}}
                        <input type="hidden" name="stage" value="packing">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Selesai Pack
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center bg-slate-50 border border-slate-200 border-dashed rounded-2xl">
                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                <p class="text-slate-500 font-medium text-center">Tidak ada pesanan untuk dipacking saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
