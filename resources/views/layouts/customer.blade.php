<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaundryPro') — Sistem Manajemen Laundry</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 antialiased" x-data="{ sidebarOpen: false }">

    {{-- ===================== MOBILE SIDEBAR OVERLAY ===================== --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
        x-cloak
    ></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-100 shadow-xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:shadow-none flex flex-col"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-sm shadow-sky-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-800 text-slate-800 font-extrabold tracking-tight">LaundryPro</p>
                <p class="text-[10px] text-slate-400 font-medium">Sistem Manajemen</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            {{-- Label --}}
            <p class="px-3 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Menu Utama</p>

            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                    ['route' => 'orders.index', 'label' => 'Order Masuk', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                    ['route' => 'process.index', 'label' => 'Proses Cucian', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
                    ['route' => 'payments.index', 'label' => 'Pembayaran', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
                    ['route' => 'reports.index', 'label' => 'Laporan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                    ['route' => 'settings.index', 'label' => 'Pengaturan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
                @endphp
                <a
                    href="{{ route($item['route']) }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ $isActive
                            ? 'bg-sky-50 text-sky-600'
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0 {{ $isActive ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }}" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}

                    {{-- Active indicator --}}
                    @if($isActive)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                    @endif
                </a>
            @endforeach

            {{-- Quick Action --}}
            <div class="pt-3 pb-1">
                <p class="px-3 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Aksi Cepat</p>
                <a href="{{ route('orders.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 shadow-sm shadow-sky-200 transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Order Baru
                </a>
            </div>
        </nav>

        {{-- Sidebar Footer --}}
        <div class="px-4 py-4 border-t border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-700 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@laundrypro.id' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===================== MAIN WRAPPER ===================== --}}
    <div class="lg:pl-64 flex flex-col min-h-screen">

        {{-- ===================== HEADER ===================== --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 sm:px-6 py-3">
            <div class="flex items-center gap-4">

                {{-- Mobile menu button --}}
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors"
                    aria-label="Toggle sidebar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Page title --}}
                <div class="flex-1">
                    <h1 class="text-sm font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('page-subtitle', '')</p>
                </div>

                {{-- Search --}}
                <div class="hidden sm:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 w-56 focus-within:ring-2 focus-within:ring-sky-500/30 focus-within:border-sky-300 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="search"
                        placeholder="Cari order, customer..."
                        class="flex-1 text-xs bg-transparent border-none outline-none text-slate-600 placeholder-slate-400"
                        aria-label="Search"
                    >
                </div>

                {{-- Notifikasi --}}
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="relative p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
                        aria-label="Notifikasi"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        {{-- Badge notifikasi --}}
                        <span class="absolute top-0.5 right-0.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    {{-- Dropdown notifikasi --}}
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-10 w-72 bg-white border border-slate-100 rounded-2xl shadow-xl shadow-slate-200/60 overflow-hidden z-50"
                        x-cloak
                    >
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">Notifikasi</p>
                            <span class="text-[10px] font-medium text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full">3 Baru</span>
                        </div>
                        <ul class="divide-y divide-slate-50">
                            <li class="px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                                <p class="text-xs font-medium text-slate-700">Order #LDR-240001 siap diambil</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">5 menit yang lalu</p>
                            </li>
                            <li class="px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                                <p class="text-xs font-medium text-slate-700">Pembayaran #LDR-240002 diterima</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">1 jam yang lalu</p>
                            </li>
                            <li class="px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                                <p class="text-xs font-medium text-slate-700">3 order baru hari ini</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">3 jam yang lalu</p>
                            </li>
                        </ul>
                        <div class="px-4 py-2.5 border-t border-slate-100">
                            <a href="#" class="text-[11px] font-medium text-sky-600 hover:text-sky-700">Lihat semua notifikasi →</a>
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="flex items-center gap-2 rounded-xl hover:bg-slate-50 px-2 py-1.5 transition-colors"
                        aria-label="Profile menu"
                    >
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-[10px] font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <span class="hidden sm:block text-xs font-medium text-slate-600">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-10 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl shadow-slate-200/60 overflow-hidden z-50"
                        x-cloak
                    >
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 px-4 py-3 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <div class="border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-3 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ===================== FLASH MESSAGES ===================== --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                    <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- ===================== MAIN CONTENT ===================== --}}
        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 text-center">
                © {{ date('Y') }} LaundryPro · Sistem Manajemen Laundry v1.0
            </p>
        </footer>
    </div>

    {{-- Alpine.js CDN (jika tidak via npm) --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    @stack('scripts')
</body>
</html>
