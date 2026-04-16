{{--resources/views/petugas_piket/sidebar.blade.php
Sidebar untuk petugas piket dengan akses terbatas --}}
<aside id="sidebar"
           class="fixed top-0 left-0 h-full w-64 bg-white border-r border-slate-100 z-30
                  flex flex-col py-6 px-4 gap-6
                  -translate-x-full md:translate-x-0">

        {{-- Logo --}}
        <div class="px-2">
            <span class="text-2xl font-extrabold tracking-tight text-slate-900">BeningLaundry</span>
        </div>

        {{-- Profile --}}
        <div class="flex items-center gap-3 px-2 py-3 bg-slate-50 rounded-xl">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                SP
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate">Staff Portal</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 live-dot"></span>
                    <span class="text-xs text-emerald-600 font-medium">Active Shift</span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-2 space-y-1 overflow-y-auto">
            @php
                $menus = [
                    ['label' => 'Dashboard', 'route' => route('petugas_piket.dashboard'),     'active' => request()->routeIs('petugas_piket.dashboard'),     'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
                    ['label' => 'Customer Service', 'route' => route('petugas.pos.index'),     'active' => request()->routeIs('petugas.pos.index'),           'icon' => 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Washing',   'route' => route('petugas_piket.washing.index'),   'active' => request()->routeIs('petugas_piket.washing.index'),   'icon' => 'M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z'],
                    ['label' => 'Setrika',   'route' => route('petugas_piket.setrika.index'),   'active' => request()->routeIs('petugas_piket.setrika.index'),   'icon' => 'M7 3.25H17A4.25 4.25 0 0121.25 7.5v8a4.25 4.25 0 01-4.25 4.25H7A4.25 4.25 0 012.75 15.5v-8A4.25 4.25 0 017 3.25z M10 11.25h4'],
                    ['label' => 'Packing',   'route' => route('petugas_piket.packing.index'),   'active' => request()->routeIs('petugas_piket.packing.index'),   'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                    ['label' => 'Delivery',  'route' => route('petugas_piket.delivery.index'),  'active' => request()->routeIs('petugas_piket.delivery.index'),  'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12'],
                    ['label' => 'Inventory', 'route' => route('petugas_piket.inventory.index'), 'active' => request()->routeIs('petugas_piket.inventory.index'), 'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125'],
                    ['label' => 'History',   'route' => route('petugas_piket.history.index'),   'active' => request()->routeIs('petugas_piket.history.index'),   'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp

            @foreach ($menus as $menu)
                <a href="{{ $menu['route'] }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                          {{ $menu['active']
                             ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                             : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $menu['active'] ? 'text-white' : 'text-slate-400' }}"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                    </svg>
                    <span>{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- System Status --}}
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">System Status</p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 live-dot"></span>
                <span class="text-sm font-medium text-emerald-700">All nodes operational</span>
            </div>
        </div>

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