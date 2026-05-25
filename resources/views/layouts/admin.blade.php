<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Bening Laundry</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eef4ff',
                            100: '#dae6ff',
                            200: '#bdd2ff',
                            300: '#90b5fd',
                            400: '#5d8ff9',
                            500: '#3568f4',
                            600: '#1f48e9',
                            700: '#1736d6',
                            800: '#192cad',
                            900: '#1a2b88',
                            950: '#141d54',
                        },
                    },
                    boxShadow: {
                        card: '0 2px 12px 0 rgba(21,34,120,0.07)',
                        'card-hover': '0 8px 28px 0 rgba(21,34,120,0.13)',
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.45s cubic-bezier(.22,1,.36,1) both',
                        'fade-in': 'fadeIn 0.35s ease both',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: 0, transform: 'translateY(18px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: 0 },
                            '100%': { opacity: 1 },
                        },
                    }
                }
            }
        }
    </script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar active item */
        .sidebar-link.active {
            background: linear-gradient(135deg, #3568f4 0%, #1736d6 100%);
            color: #fff;
            box-shadow: 0 4px 14px 0 rgba(53,104,244,0.35);
        }
        .sidebar-link.active svg { stroke: #fff; }
        .sidebar-link:not(.active):hover {
            background: #eef4ff;
            color: #1f48e9;
        }
        .sidebar-link:not(.active):hover svg { stroke: #1f48e9; }

        /* Transition */
        .sidebar-link { transition: all .18s ease; }

        /* Stagger children */
        .stagger > *:nth-child(1) { animation-delay: .05s }
        .stagger > *:nth-child(2) { animation-delay: .10s }
        .stagger > *:nth-child(3) { animation-delay: .15s }
        .stagger > *:nth-child(4) { animation-delay: .20s }

        /* Mobile sidebar overlay */
        #sidebar-overlay { display: none; }
        #sidebar-overlay.active { display: block; }
        #sidebar.open { transform: translateX(0) !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<!-- Toast Container -->
<div id="toast-container" class="toast-container"></div>

<!-- Mobile overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-20 lg:hidden" onclick="toggleSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    <!-- ===================== SIDEBAR ===================== -->
    @include('admin.sidebar')
    <!-- =================== END SIDEBAR =================== -->

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- TOPBAR -->
        <header class="h-16 bg-white border-b border-slate-100 flex items-center gap-4 px-5 lg:px-7 shrink-0 shadow-sm z-10">

            <!-- Mobile hamburger -->
            <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <!-- Search -->
            <form action="{{ url()->current() }}" method="GET" class="relative flex-1 max-w-md">
                @foreach (request()->except('search') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search transactions, customers..."
                       class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl
                              focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400
                              placeholder:text-slate-400 transition">
            </form>

            <div class="flex items-center gap-3 ml-auto">
                <!-- Notification Bell -->
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @php
                            $recentActivities = \App\Models\ActivityLog::latest()->limit(5)->get();
                        @endphp
                        @if($recentActivities->count() > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 z-50 max-h-96 overflow-hidden">
                        <div class="p-4 border-b border-slate-100">
                            <h3 class="font-semibold text-slate-800">Recent Activity</h3>
                        </div>
                        <div class="overflow-y-auto max-h-72">
                            @php
                                $recentActivities = \App\Models\ActivityLog::with('causer')
                                    ->latest()
                                    ->limit(5)
                                    ->get();
                            @endphp
                            @if($recentActivities->count() > 0)
                                @foreach($recentActivities as $activity)
                                <div class="block p-4 hover:bg-slate-50 border-b border-slate-100 last:border-0 transition">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-800 truncate">{{ $activity->description }}</p>
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $activity->causer ? $activity->causer->name ?? $activity->causer->email : 'System' }} • {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="p-8 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
                                    <p class="text-sm">No recent activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-px h-7 bg-slate-200 hidden sm:block"></div>

                <!-- Profile -->
                <div class="flex items-center gap-2.5 cursor-pointer group">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-xs font-bold shadow-sm">A</div>
                    <div class="hidden sm:block leading-none">
                        <p class="text-sm font-semibold text-slate-800">Admin Profile</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Super Admin</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>
        </header>
        <!-- END TOPBAR -->

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-5 lg:p-7 animate-fade-in">
            @yield('content')
        </main>

    </div>
    <!-- =================== END MAIN AREA =================== -->

</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }

    function toggleNotifications() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('hidden');
    }

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        if (dropdown.classList.contains('hidden')) {
            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notification-dropdown');
        const button = event.target.closest('button[onclick="toggleNotifications()"]');
        
        if (!button && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }

        // Close all dropdowns when clicking outside
        if (!event.target.closest('button[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (!el.contains(event.target)) {
                    el.classList.add('hidden');
                }
            });
        }
    });

    // Global loading state for forms
    document.addEventListener('submit', function (e) {
        if(e.target.tagName === 'FORM') {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            if(submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

                // Safety timeout to re-enable after 10s if something fails
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        }
    });

    // Toast notification system
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icons = {
            success: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            error: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>',
            warning: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>',
            info: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>'
        };

        toast.innerHTML = `
            ${icons[type] || icons.info}
            <div class="toast-content">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        container.appendChild(toast);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    // Show Laravel flash messages as toasts
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    @if(session('warning'))
        showToast('{{ session('warning') }}', 'warning');
    @endif
    @if(session('info'))
        showToast('{{ session('info') }}', 'info');
    @endif
</script>

@stack('scripts')
</body>
</html>