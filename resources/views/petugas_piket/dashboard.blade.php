{{-- resources/views/petugas_piket/dashboard.blade.php --}}

@extends('layouts.petugas_piket')
@section('title', 'Dashboard Petugas Piket')
@section('content')


{{-- ============================================
                 HERO HEADER CARD
============================================ --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900
                        p-7 md:p-10 fade-up delay-1 shadow-lg shadow-blue-200">
                {{-- Decorative blobs --}}
                <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full
                            bg-blue-500/30 blur-shape"></div>
                <div class="absolute -bottom-16 right-24 w-48 h-48 rounded-full
                            bg-indigo-400/20 blur-shape"></div>
                {{-- Grid decoration --}}
                <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden sm:grid
                            grid-cols-4 gap-2 opacity-20">
                    @for($i = 0; $i < 16; $i++)
                    <div class="w-5 h-5 rounded-sm bg-white"></div>
                    @endfor
                </div>

                <div class="relative z-10 max-w-lg">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">
                        Selamat Datang, Petugas Piket!
                    </h1>
                    <p class="text-blue-200 text-sm md:text-base leading-relaxed mb-6">
                        Everything is in sync. Your orchestra is performing beautifully today.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <button class="px-5 py-2.5 rounded-xl bg-white text-blue-700 font-semibold text-sm
                                       hover:bg-blue-50 transition-colors shadow-sm">
                            View Reports
                        </button>
                        <button class="px-5 py-2.5 rounded-xl bg-blue-500/40 text-white font-semibold text-sm
                                       border border-blue-400/40 hover:bg-blue-500/60 transition-colors backdrop-blur-sm">
                            Daily Sync
                        </button>
                    </div>
                </div>
            </div>

            {{-- ============================================
                 STATS ROW
                 ============================================ --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">

                {{-- Card 1: Current Status --}}
                <div class="stat-card fade-up delay-2 relative overflow-hidden rounded-2xl
                            bg-emerald-100 border border-emerald-200/60 p-6">
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full bg-emerald-300/20 blur-shape"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-emerald-800 uppercase tracking-widest">Current Status</span>
                            <span class="flex items-center gap-1.5 bg-emerald-500 text-white text-xs font-bold
                                         px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-white live-dot"></span>
                                LIVE
                            </span>
                        </div>
                        <p class="text-4xl font-extrabold text-emerald-900 tracking-tight mb-1">PACKING</p>
                        <p class="text-sm text-emerald-700 font-medium mb-5">Shift Duration: 08:00 – 16:00</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-emerald-900">1,240</span>
                            <span class="text-sm font-semibold text-emerald-700">Units</span>
                        </div>
                        {{-- Avatar group --}}
                        <div class="flex items-center gap-1.5 mt-4">
                            @foreach(['#60a5fa','#34d399','#f472b6','#a78bfa'] as $color)
                            <div class="w-7 h-7 rounded-full border-2 border-white shadow-sm"
                                 style="background-color: {{ $color }}"></div>
                            @endforeach
                            <span class="text-xs text-emerald-700 font-semibold ml-1">+5</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Efficiency --}}
                <div class="stat-card fade-up delay-3 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm
                            flex flex-col items-center justify-center text-center gap-4">
                    {{-- Ring --}}
                    <div class="relative w-28 h-28 rounded-full ring-blue flex items-center justify-center">
                        <div class="absolute inset-2.5 rounded-full bg-white flex items-center justify-center">
                            <div>
                                <p class="text-2xl font-extrabold text-blue-600 leading-none">75%</p>
                                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mt-0.5">Efficiency</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">Daily Throughput</p>
                        <p class="text-xs text-slate-400 mt-0.5">Increasing by 12% vs yesterday</p>
                    </div>
                </div>

                {{-- Card 3: Quality --}}
                <div class="stat-card fade-up delay-3 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm
                            flex flex-col items-center justify-center text-center gap-4">
                    <div class="relative w-28 h-28 rounded-full ring-green flex items-center justify-center">
                        <div class="absolute inset-2.5 rounded-full bg-white flex items-center justify-center">
                            <div>
                                <p class="text-2xl font-extrabold text-emerald-700 leading-none">95%</p>
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mt-0.5">Quality</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">Packing Accuracy</p>
                        <p class="text-xs text-slate-400 mt-0.5">Excellent performance maintained</p>
                    </div>
                </div>
            </div>

            {{-- ============================================
                 OPERATIONAL PULSE
                 ============================================ --}}
            <div class="fade-up delay-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Operational Pulse</h2>
                    <a href="#" class="flex items-center gap-1 text-xs font-semibold text-blue-600
                                       uppercase tracking-widest hover:text-blue-700 transition-colors">
                        Detailed Metrics
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2.5" stroke="currentColor" width="12" height="12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">

                    {{-- Washing --}}
                    <div class="stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.8" stroke="#2563eb" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+2.4%</span>
                        </div>
                        <p class="text-2xl font-extrabold text-slate-800">482 kg</p>
                        <p class="text-sm text-slate-500 mt-1">Processed Washing</p>
                    </div>

                    {{-- Inventory --}}
                    <div class="stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.8" stroke="#7c3aed" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">-0.5%</span>
                        </div>
                        <p class="text-2xl font-extrabold text-slate-800">12.5k</p>
                        <p class="text-sm text-slate-500 mt-1">Inventory Items</p>
                    </div>

                    {{-- Delivery --}}
                    <div class="stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.8" stroke="#ea580c" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+8.1%</span>
                        </div>
                        <p class="text-2xl font-extrabold text-slate-800">84 Trips</p>
                        <p class="text-sm text-slate-500 mt-1">Successful Deliveries</p>
                    </div>
                </div>
            </div>

            {{-- Spacer bottom --}}
            <div class="h-10"></div>
        </div>
    </main>
</div>

{{-- ======================================================
     FLOATING ACTION BUTTON
     ====================================================== --}}
<button class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-blue-600 text-white
               shadow-lg shadow-blue-300 hover:bg-blue-700 hover:scale-105
               active:scale-95 transition-all duration-150
               flex items-center justify-center z-40"
        title="New Action">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="2.5" stroke="currentColor" width="22" height="22">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
    </svg>
</button>
@endsection

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>