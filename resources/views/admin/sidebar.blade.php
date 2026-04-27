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
                $sidebarOnDutyCount = $sidebarOnDutyCount ?? 0;
                $sidebarOnDutyPetugas = $sidebarOnDutyPetugas ?? collect();

                $menus = [
                    ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard',         'route' => route('admin.dashboard'), 'active' =>  request()->routeIs('admin.dashboard')],
                    ['icon' => 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Pesanan Baru',      'route' => route('admin.pos.index'), 'active' => request()->routeIs('admin.pos.index')],
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Transaksi',          'route' => route('admin.transactions.index'), 'active' => request()->routeIs('admin.transactions.index')],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Customer',           'route' => route('admin.customers.index'), 'active' => request()->routeIs('admin.customers.index')],
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Petugas',            'route' => route('admin.petugas.index'), 'active' => request()->routeIs('admin.petugas.index'), 'badge' => $sidebarOnDutyCount > 0 ? $sidebarOnDutyCount : null],
                    ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Pembayaran',         'route' => route('admin.pembayaran.index'), 'active' => request()->routeIs('admin.pembayaran.index')],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Laporan Keuangan', 'route' => route('admin.laporan_keuangan.index'), 'active' => request()->routeIs('admin.laporan_keuangan.index')],
                    ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Pengeluaran',        'route' => route('admin.pengeluaran.index'), 'active' => request()->routeIs('admin.pengeluaran.index')],
                    ['icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125', 'label'=>'Inventory', 'route' => route('admin.inventory.index'), 'active' => request()->routeIs('admin.inventory.index')],
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
                    <span class="flex-1">{{ $menu['label'] }}</span>
                    @if(!empty($menu['badge']))
                        <span class="inline-flex min-w-6 h-6 items-center justify-center px-1.5 rounded-full text-[11px] font-semibold {{ $menu['active'] ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $menu['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach

            <div class="mt-5 mx-1 p-3 rounded-xl border border-emerald-100 bg-emerald-50/70">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700">Sedang Bertugas</p>
                    <span class="text-[11px] font-semibold text-emerald-700">{{ $sidebarOnDutyCount }}</span>
                </div>

                @if($sidebarOnDutyPetugas->isEmpty())
                    <p class="mt-2 text-xs text-slate-500">Belum ada petugas aktif saat ini.</p>
                @else
                    <ul class="mt-2 space-y-1.5">
                        @foreach($sidebarOnDutyPetugas as $petugas)
                            <li class="flex items-start gap-2 text-xs text-slate-700">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span class="leading-tight">
                                    <span class="font-medium">{{ $petugas->nama }}</span>
                                    <span class="text-slate-500">({{ $petugas->shift }})</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
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
