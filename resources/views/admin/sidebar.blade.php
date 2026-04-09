{{--resources/views/components/stat_card.blade.php
Usage: @include('components.stat_card', [
        'icon'    => 'svg-path-d',
        'label'   => 'Total Transaksi',
        'value'   => '1,284',
        'sub'     => 'Last 30 days performance',
        'badge'   => '+12.5%',
        'up'      => true,
        'color'   => 'blue',   // blue | green | red | purple
        'progress'=> null,     // int 0–100 or null
    ])  --}}
<aside id="sidebar"
           class="fixed lg:relative z-30 w-64 h-full bg-white flex flex-col border-r border-slate-100 shadow-card
                  transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

        <!-- Logo -->
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="font-display text-base font-700 text-slate-900 leading-none">Bening Laundry</p>
                    <p class="text-[10px] font-500 text-slate-400 tracking-widest uppercase mt-0.5">Management Portal</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            @php
                $menus = [
                    ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard',         'route' => route('admin.dashboard'), 'active' =>  request()->routeIs('admin.dashboard')],
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Transaksi',          'route' => route('admin.transactions.index'), 'active' => request()->routeIs('admin.transactions.index')],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Customer',           'route' => route('admin.customers.index'), 'active' => request()->routeIs('admin.customers.index')],
                    ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Layanan',           'route' => route('admin.layanan.index'), 'active' => request()->routeIs('admin.layanan.index')],
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Petugas',            'route' => route('admin.petugas.index'), 'active' => request()->routeIs('admin.petugas.index')],
                    ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Pembayaran',         'route' => route('admin.pembayaran.index'), 'active' => request()->routeIs('admin.pembayaran.index')],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Laporan Keuangan', 'route' => route('admin.laporan_keuangan.index'), 'active' => request()->routeIs('admin.laporan_keuangan.index')],
                    ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Pengeluaran',        'route' => route('admin.pengeluaran.index'), 'active' => request()->routeIs('admin.pengeluaran.index')],
                ];
            @endphp

            @foreach ($menus as $menu)
                <a href="{{ $menu['route'] }}"
                   class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                          {{ $menu['active'] ? 'active text-white' : 'text-slate-500' }}">
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $menu['active'] ? 'stroke-white' : 'stroke-slate-400' }}"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                    </svg>
                    <span>{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Logout -->
        <div class="px-3 py-4 border-t border-slate-100">
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-rose-500
                      hover:bg-rose-50 transition-all duration-150">
                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span>Logout</span>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>