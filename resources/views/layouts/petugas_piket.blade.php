<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard petugas_piket') - beninglaundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Sidebar transition */
        #sidebar { transition: transform 0.3s ease; }

        /* Circle progress */
        .ring-blue {
            background: conic-gradient(#2563eb 0% 75%, #e2e8f0 75% 100%);
        }
        .ring-green {
            background: conic-gradient(#16a34a 0% 95%, #e2e8f0 95% 100%);
        }

        /* Animated LIVE badge */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .live-dot { animation: pulse-dot 1.4s ease-in-out infinite; }

        /* Smooth card hover */
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }

        /* Sidebar overlay */
        #sidebar-overlay { transition: opacity 0.3s ease; }

        /* Fade in page */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .delay-1 { animation-delay: 0.05s; opacity: 0; }
        .delay-2 { animation-delay: 0.10s; opacity: 0; }
        .delay-3 { animation-delay: 0.15s; opacity: 0; }
        .delay-4 { animation-delay: 0.20s; opacity: 0; }
        .delay-5 { animation-delay: 0.25s; opacity: 0; }

        /* Blur decoration */
        .blur-shape {
            filter: blur(60px);
            pointer-events: none;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

{{-- ======================================================
     MOBILE OVERLAY
     ====================================================== --}}
<div id="sidebar-overlay"
     class="fixed inset-0 bg-black/30 z-20 hidden md:hidden"
     onclick="toggleSidebar()"></div>

{{-- ======================================================
     LAYOUT WRAPPER
     ====================================================== --}}
<div class="flex min-h-screen">

    {{-- ====================================================
         SIDEBAR
         ==================================================== --}}
    @include('petugas_piket.sidebar')

    {{-- ====================================================
         MAIN CONTENT
         ==================================================== --}}
    <main class="flex-1 md:ml-64 min-h-screen">

        {{-- Mobile top bar --}}
        <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-slate-100">
            <span class="text-xl font-extrabold tracking-tight text-slate-900">Orchestra</span>
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-slate-100 text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        @yield('sticky_topbar')

        <div class="p-4 md:p-8 space-y-6 max-w-screen-xl mx-auto">
        <div class="p-6">
            {{-- INI YANG DIGANTI-GANTI --}}
            @yield('content')
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

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>

</body>
</html>