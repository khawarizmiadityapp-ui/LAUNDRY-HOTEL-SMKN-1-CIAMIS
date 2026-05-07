@extends('layouts.petugas_piket')
@section('title', 'Dashboard Petugas Piket')
@section('content')

<div class="p-6 max-w-7xl mx-auto animate-fade-in">
    {{-- Header Section --}}
    <div class="mb-8 bg-white rounded-2xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
        {{-- Subtle background pattern --}}
        <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/3"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">
                    Selamat Datang, Petugas {{ ucfirst($division ?? 'Piket') }}!
                </h1>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl">
                    Berikut adalah ringkasan pekerjaan Anda hari ini. Tetap semangat dan pastikan kualitas pelayanan terbaik untuk pelanggan Bening Laundry.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-xl border border-blue-100">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    Live Updates
                </span>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Pending Tasks --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Tugas Menunggu</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-extrabold text-slate-800">{{ $pendingTasks ?? 0 }}</h2>
                    <span class="text-sm font-medium text-slate-400">antrean</span>
                </div>
            </div>
        </div>

        {{-- Completed Tasks --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Diselesaikan Hari Ini</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-extrabold text-slate-800">{{ $completedToday ?? 0 }}</h2>
                    <span class="text-sm font-medium text-slate-400">tugas</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Quick Action --}}
    @php
        $validDivisions = ['washing' => 'washing', 'ironing' => 'setrika', 'setrika' => 'setrika', 'packing' => 'packing'];
        $routeName = isset($validDivisions[$division]) ? 'petugas_piket.' . $validDivisions[$division] . '.index' : null;
    @endphp

    @if($routeName)
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="text-white">
            <h3 class="text-xl font-bold mb-1">Siap untuk mulai bekerja?</h3>
            <p class="text-blue-100 text-sm">Masuk ke halaman antrean untuk melihat daftar pekerjaan Anda.</p>
        </div>
        <a href="{{ route($routeName) }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition-colors whitespace-nowrap shadow-sm">
            Lihat Antrean
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
    @else
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="text-white">
            <h3 class="text-xl font-bold mb-1">Selamat Bertugas!</h3>
            <p class="text-blue-100 text-sm">Pastikan untuk mengecek tugas Anda melalui menu di samping.</p>
        </div>
    </div>
    @endif

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