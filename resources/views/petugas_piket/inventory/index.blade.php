{{-- resources/views/petugas_piket/inventory/index.blade.php --}}

@extends('petugas_piket.sidebar')
@section('title', 'Inventory Management - Staff Portal')
@section('content')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        mono: ['"DM Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .sidebar-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 70%;
            background: #2563eb;
            border-radius: 0 4px 4px 0;
        }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up   { animation: fadeSlideUp .5s ease both; }
        .delay-1   { animation-delay: .08s; }
        .delay-2   { animation-delay: .16s; }
        .delay-3   { animation-delay: .24s; }
        .delay-4   { animation-delay: .32s; }
        .progress-bar { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
    </style>
</head>
<body class="bg-slate-100 min-h-screen antialiased">

<div class="flex min-h-screen">

    <!-- ═══════════════════════════════════ SIDEBAR ═══════════════════════════════════ -->
    @include('petugas_piket.sidebar')

    <!-- ═══════════════════════════════════ MAIN ═══════════════════════════════════ -->
    <main class="flex-1 ml-64 p-8">

        <!-- Header -->
        <div class="mb-8 fade-up">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-[10px] font-bold tracking-widest text-blue-500 uppercase mb-3">
                Inventaris Kecil
            </span>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Supply Management</h1>
            <p class="mt-1.5 text-sm text-slate-400 max-w-lg leading-relaxed">
                Curated list of essential laundry supplies. Monitor detergent levels and scent reserves with high-precision conductors.
            </p>
        </div>

        <!-- 3-column grid -->
        <div class="grid grid-cols-3 gap-6 items-start">

            <!-- ── 2-col content ── -->
            <div class="col-span-2 space-y-6">

                <!-- Premium Detergents -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 fade-up delay-1">
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <h2 class="font-bold text-slate-800 text-lg">Premium Detergents</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Active wash agents and surfactants</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Product cards -->
                    <div class="grid grid-cols-2 gap-4">

                        <!-- Product 1: Bio-Enzyme Blue -->
                        <div class="bg-white/80 backdrop-blur-sm border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="relative bg-gradient-to-br from-cyan-50 via-teal-50 to-sky-50 h-44 flex items-center justify-center p-4">
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-red-50 border border-red-200 text-red-500 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    Low Stock
                                </span>
                                <!-- Bottle SVG -->
                                <svg viewBox="0 0 80 130" class="h-32 drop-shadow-lg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="28" y="1" width="24" height="13" rx="5" fill="#67e8f9" opacity=".8"/>
                                    <rect x="22" y="12" width="36" height="7" rx="4" fill="#22d3ee"/>
                                    <rect x="12" y="17" width="56" height="92" rx="16" fill="url(#b1)"/>
                                    <rect x="16" y="21" width="48" height="84" rx="12" fill="url(#b2)" opacity=".6"/>
                                    <ellipse cx="32" cy="46" rx="7" ry="7" fill="white" opacity=".25"/>
                                    <ellipse cx="50" cy="38" rx="4" ry="4" fill="white" opacity=".15"/>
                                    <path d="M25 70 Q40 65 55 70" stroke="white" stroke-width="1.5" stroke-linecap="round" opacity=".4"/>
                                    <path d="M22 80 Q40 74 58 80" stroke="white" stroke-width="1.5" stroke-linecap="round" opacity=".25"/>
                                    <defs>
                                        <linearGradient id="b1" x1="12" y1="17" x2="68" y2="109" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#22d3ee"/>
                                            <stop offset="1" stop-color="#0891b2"/>
                                        </linearGradient>
                                        <linearGradient id="b2" x1="16" y1="21" x2="64" y2="105" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#a5f3fc"/>
                                            <stop offset="1" stop-color="#06b6d4" stop-opacity=".5"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div class="px-4 pt-3 pb-4">
                                <p class="text-[9px] font-bold tracking-widest text-blue-400 uppercase mb-0.5">Heavy Duty</p>
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-base leading-snug">Bio-Enzyme Blue</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">5000ml Industrial Grade</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="text-xl font-bold text-slate-800" id="qty1">12</span>
                                        <span class="text-xs text-slate-400 ml-0.5">units</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-3">
                                    <button onclick="const el=document.getElementById('qty1'); el.textContent=Math.max(0,+el.textContent-1)"
                                            class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm transition-colors">−</button>
                                    <span class="min-w-[2rem] text-center font-semibold text-slate-700 text-sm" id="qty1b">12</span>
                                    <button onclick="const el=document.getElementById('qty1'); const el2=document.getElementById('qty1b'); el.textContent=+el.textContent+1; el2.textContent=el.textContent"
                                            class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm transition-colors">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2: Silk & Wool Mist -->
                        <div class="bg-white/80 backdrop-blur-sm border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="relative bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 h-44 flex items-center justify-center p-4">
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-blue-600 text-white shadow-sm">
                                    Lead Conductor
                                </span>
                                <!-- Bottle 2 SVG -->
                                <svg viewBox="0 0 80 140" class="h-32 drop-shadow-lg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="30" y="0" width="20" height="8" rx="3" fill="#93c5fd" opacity=".8"/>
                                    <path d="M26 8 Q40 4 54 8 L58 20 H22 Z" fill="#60a5fa"/>
                                    <rect x="14" y="19" width="52" height="100" rx="14" fill="url(#b3)"/>
                                    <rect x="18" y="23" width="44" height="92" rx="10" fill="url(#b4)" opacity=".5"/>
                                    <circle cx="31" cy="52" r="8" fill="white" opacity=".2"/>
                                    <!-- Fabric Work label area -->
                                    <rect x="20" y="68" width="40" height="28" rx="5" fill="white" opacity=".15"/>
                                    <text x="40" y="80" text-anchor="middle" fill="white" font-size="4.5" font-weight="bold" opacity=".7">FABRIC</text>
                                    <text x="40" y="87" text-anchor="middle" fill="white" font-size="4.5" font-weight="bold" opacity=".7">WORK</text>
                                    <!-- Pump top -->
                                    <rect x="36" y="-8" width="8" height="12" rx="2" fill="#93c5fd" opacity=".9"/>
                                    <ellipse cx="40" cy="-8" rx="6" ry="3" fill="#bfdbfe"/>
                                    <defs>
                                        <linearGradient id="b3" x1="14" y1="19" x2="66" y2="119" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#3b82f6"/>
                                            <stop offset="1" stop-color="#1d4ed8"/>
                                        </linearGradient>
                                        <linearGradient id="b4" x1="18" y1="23" x2="62" y2="115" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#bfdbfe"/>
                                            <stop offset="1" stop-color="#3b82f6" stop-opacity=".3"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div class="px-4 pt-3 pb-4">
                                <p class="text-[9px] font-bold tracking-widest text-indigo-400 uppercase mb-0.5">Delicate</p>
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-base leading-snug">Silk & Wool Mist</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">2500ml Care Formula</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="text-xl font-bold text-slate-800" id="qty2">48</span>
                                        <span class="text-xs text-slate-400 ml-0.5">units</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-3">
                                    <button onclick="const el=document.getElementById('qty2'); el.textContent=Math.max(0,+el.textContent-1)"
                                            class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm transition-colors">−</button>
                                    <span class="min-w-[2rem] text-center font-semibold text-slate-700 text-sm">48</span>
                                    <button onclick="const el=document.getElementById('qty2'); el.textContent=+el.textContent+1"
                                            class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm transition-colors">+</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Fragrance Library -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 fade-up delay-2">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-bold text-slate-800 text-lg">Fragrance Library</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Signature olfactory profiles for finished loads</p>
                        </div>
                        <a href="#" class="text-xs font-semibold text-blue-500 hover:text-blue-700 transition-colors flex items-center gap-1">
                            View All Collections
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-4 gap-3">

                        <!-- Morning Meadow -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                            <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm leading-snug">Morning Meadow</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-tight">Concentrated Essential Oil</p>
                            <div class="flex items-center gap-2 mt-3">
                                <button onclick="const s=this.nextElementSibling; s.textContent=String(Math.max(0,parseInt(s.textContent)-1)).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">−</button>
                                <span class="text-sm font-mono font-semibold text-slate-700 min-w-[1.75rem] text-center">08</span>
                                <button onclick="const s=this.previousElementSibling; s.textContent=String(parseInt(s.textContent)+1).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">+</button>
                            </div>
                        </div>

                        <!-- Arctic Breeze -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm leading-snug">Arctic Breeze</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-tight">Cool Refreshing Tone</p>
                            <div class="flex items-center gap-2 mt-3">
                                <button onclick="const s=this.nextElementSibling; s.textContent=String(Math.max(0,parseInt(s.textContent)-1)).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">−</button>
                                <span class="text-sm font-mono font-semibold text-slate-700 min-w-[1.75rem] text-center">14</span>
                                <button onclick="const s=this.previousElementSibling; s.textContent=String(parseInt(s.textContent)+1).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">+</button>
                            </div>
                        </div>

                        <!-- Sunset Vanilla -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                            <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm leading-snug">Sunset Vanilla</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-tight">Warm & Creamy Finish</p>
                            <div class="flex items-center gap-2 mt-3">
                                <button onclick="const s=this.nextElementSibling; s.textContent=String(Math.max(0,parseInt(s.textContent)-1)).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">−</button>
                                <span class="text-sm font-mono font-semibold text-slate-700 min-w-[1.75rem] text-center">04</span>
                                <button onclick="const s=this.previousElementSibling; s.textContent=String(parseInt(s.textContent)+1).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">+</button>
                            </div>
                        </div>

                        <!-- Pure Unscented -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm leading-snug">Pure Unscented</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-tight">Hypoallergenic Neutral</p>
                            <div class="flex items-center gap-2 mt-3">
                                <button onclick="const s=this.nextElementSibling; s.textContent=String(Math.max(0,parseInt(s.textContent)-1)).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">−</button>
                                <span class="text-sm font-mono font-semibold text-slate-700 min-w-[1.75rem] text-center">22</span>
                                <button onclick="const s=this.previousElementSibling; s.textContent=String(parseInt(s.textContent)+1).padStart(2,'0')" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-xs font-bold transition-colors">+</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ── RIGHT PANEL ── -->
            <div class="col-span-1 space-y-4 fade-up delay-3">

                <!-- Inventory Health -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-400 rounded-2xl p-5 shadow-lg shadow-blue-200 relative overflow-hidden">
                    <div class="absolute -top-6 -right-6 w-28 h-28 bg-white/10 rounded-full pointer-events-none"></div>
                    <div class="absolute -bottom-8 -left-4 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

                    <h2 class="text-white font-bold text-base mb-4 relative z-10">Inventory Health</h2>

                    <div class="space-y-3.5 relative z-10">
                        <!-- Liquid Supplies -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-xs font-medium text-blue-100">Liquid Supplies</span>
                                <span class="text-xs font-bold text-white">84%</span>
                            </div>
                            <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full progress-bar" style="width: 84%"></div>
                            </div>
                        </div>
                        <!-- Packaging -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-xs font-medium text-blue-100">Packaging</span>
                                <span class="text-xs font-bold text-white">32%</span>
                            </div>
                            <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-300 rounded-full progress-bar" style="width: 32%"></div>
                            </div>
                        </div>
                    </div>

                    <button class="relative z-10 mt-5 w-full bg-white text-blue-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-blue-50 transition-colors shadow-sm">
                        Generate Order Report
                    </button>
                </div>

                <!-- Essential Hangers -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 fade-up delay-4">
                    <h3 class="text-[9.5px] font-bold tracking-widest text-slate-400 uppercase mb-4">Essential Hangers</h3>
                    <div class="space-y-3">

                        <!-- Wooden Suit Hanger -->
                        <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0 0L4 19h16L12 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700 leading-tight">Wooden Suit Hanger</p>
                                    <p class="text-xs text-slate-400">Qty: 240</p>
                                </div>
                            </div>
                            <button class="w-7 h-7 rounded-full border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-colors flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Standard Wire -->
                        <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0 0L4 19h16L12 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700 leading-tight">Standard Wire</p>
                                    <p class="text-xs text-slate-400">Qty: 1,200</p>
                                </div>
                            </div>
                            <button class="w-7 h-7 rounded-full border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-colors flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </main>

</div>

</body>
@endsection