@extends('layouts.petugas_piket')

@section('title', 'Inventaris - Staff Portal')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-bold tracking-widest text-indigo-600 uppercase mb-3">
                Logistic Control
            </span>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Manajemen Inventaris</h1>
            <p class="text-slate-500 mt-1 max-w-lg">Pantau stok bahan cuci, pewangi, dan perlengkapan lainnya secara real-time.</p>
            <p class="text-xs text-amber-600 mt-2">Setiap perubahan stok dari petugas akan masuk sebagai permintaan dan wajib konfirmasi admin/guru piket.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white border border-slate-100 rounded-xl shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Total Kategori</p>
                <p class="text-xl font-bold text-slate-900 leading-none">{{ $inventory->count() }}</p>
            </div>
            <div class="px-4 py-2 bg-white border border-slate-100 rounded-xl shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Total Item</p>
                <p class="text-xl font-bold text-slate-900 leading-none">{{ $inventory->flatten()->count() }}</p>
            </div>
        </div>
    </div>

    @if($inventory->isEmpty())
    <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Belum Ada Data Inventaris</h3>
        <p class="text-slate-400 mt-1">Hubungi admin untuk menambahkan data inventaris laundry.</p>
    </div>
    @endif

    @foreach($inventory as $category => $items)
    <div class="space-y-4">
        <!-- Category Header -->
        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-slate-100"></div>
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap">{{ $category }}</h2>
            <div class="h-px flex-1 bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($items as $item)
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl {{ $item->quantity < 5 ? 'bg-rose-50 text-rose-500' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center transition-colors">
                        @if(Str::contains(Str::lower($category), 'detergent'))
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.423 15.621a11.205 11.205 0 01-3.133 2.709m-11.339-4.547a11.205 11.205 0 013.133-2.709m11.339 4.547c.593.593.91 1.396.878 2.194a3.11 3.11 0 01-3.11 3.11c-.798.032-1.601-.285-2.194-.878L13.12 18.23l-1.13-1.13m2.784-2.785a4.416 4.416 0 00-6.244-6.244m6.244 6.244L11.51 16.5m-3.526-3.526L6.5 11.49m0 0a3.11 3.11 0 013.11-3.11c.798-.032 1.601.285 2.194.878L13.23 10.12l1.13 1.13m-3.472-3.472a11.203 11.203 0 013.133-2.709m-3.133 2.709a11.203 11.203 0 01-3.133 2.709" /></svg>
                        @elseif(Str::contains(Str::lower($category), 'fragrance'))
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        @endif
                    </div>
                    @if($item->quantity < 5)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 animate-pulse">
                        STOK RENDAH
                    </span>
                    @endif
                </div>

                <h3 class="font-bold text-slate-800 leading-tight">{{ $item->name }}</h3>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide">{{ $item->type ?? 'Standard Supply' }}</p>

                <div class="mt-6 flex items-end justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jumlah Stok</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black {{ $item->quantity < 5 ? 'text-rose-600' : 'text-slate-900' }}">{{ $item->quantity }}</span>
                            <span class="text-xs font-bold text-slate-400">Unit</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-slate-50 flex items-center justify-between">
                    <form action="{{ route('petugas_piket.inventory.adjust', $item->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="reason" value="Penyesuaian stok oleh petugas {{ auth()->user()->name }}">
                        <button type="submit" name="adjustment" value="-1" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all flex items-center justify-center font-bold"> - </button>
                        <span class="text-sm font-bold text-slate-700 min-w-[20px] text-center">{{ $item->quantity }}</span>
                        <button type="submit" name="adjustment" value="1" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all flex items-center justify-center font-bold"> + </button>
                    </form>
                    <div class="text-[10px] text-slate-300 italic">
                        Update: {{ $item->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.4s ease out forwards;
}
</style>
@endsection
