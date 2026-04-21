@extends('layouts.petugas_piket')
@section('title', 'Washing')
@section('content')
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] },
      keyframes: {
        fadeUp: { '0%':{ opacity:'0', transform:'translateY(12px)' }, '100%':{ opacity:'1', transform:'translateY(0)' } },
        pulse2: { '0%,100%':{ opacity:'1' }, '50%':{ opacity:'.45' } }
      },
      animation: {
        fadeUp:   'fadeUp .45s ease both',
        pulse2:   'pulse2 2.2s ease-in-out infinite',
      }
    }
  }
}
</script>
<style>
  *, body { font-family: 'Plus Jakarta Sans', sans-serif; }
  .card { transition: box-shadow .2s, transform .2s; }
  .card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.07); }
  .sidebar-link { transition: all .16s ease; }
  .progress { transition: width 1.1s cubic-bezier(.4,0,.2,1); }
  ::-webkit-scrollbar { width:4px }
  ::-webkit-scrollbar-track { background:#f1f5f9 }
  ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:99px }

  /* Stagger animation delays */
  .d1 { animation-delay: .05s }
  .d2 { animation-delay: .12s }
  .d3 { animation-delay: .19s }
  .d4 { animation-delay: .26s }
  .d5 { animation-delay: .33s }
  .d6 { animation-delay: .40s }
  .d7 { animation-delay: .47s }
</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<div class="flex h-screen overflow-hidden">

<!-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════ -->
@include('petugas_piket.sidebar')

<!-- ═══════════════════════════════ MAIN AREA ═══════════════════════════════ -->
<div class="flex-1 flex flex-col overflow-hidden">



  <!-- ── CONTENT ── -->
  <main class="flex-1 overflow-y-auto p-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-fadeUp d1">
      <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Operasi Pencucian</h1>
        <p class="text-sm text-slate-500 mt-1">Dashboard real-time departemen pencucian hari ini.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="flex items-center gap-1.5 text-sm font-medium text-slate-600 bg-white border border-gray-200 rounded-xl px-3.5 py-2 shadow-sm">
          <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
          </svg>
          24 Mei 2024
        </div>
        <button class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
          </svg>
          Tambah Antrean
        </button>
      </div>
    </div>

    <!-- Two-column layout -->
    <div class="flex gap-6 items-start">

      <!-- Left main -->
      <div class="flex-1 min-w-0 space-y-6">

        <!-- ── STAT CARDS ── -->
        <div class="grid grid-cols-3 gap-5">

          <!-- Card 1: Capacity -->
          <div class="card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-fadeUp d1">
            <div class="text-[10.5px] font-bold text-slate-400 uppercase tracking-widest mb-4">Kapasitas Muatan</div>
            <div class="text-5xl font-extrabold text-blue-600 mb-1">85%</div>
            <div class="text-sm text-slate-500 mb-4">Optimal: Tingkat efisiensi tinggi</div>
            <div class="w-full bg-slate-100 rounded-full h-2">
              <div class="progress bg-blue-500 h-2 rounded-full" style="width:85%"></div>
            </div>
          </div>

          <!-- Card 2: Weight -->
          <div class="card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-fadeUp d2">
            <div class="flex items-start justify-between mb-4">
              <div class="text-[10.5px] font-bold text-slate-400 uppercase tracking-widest">Total Diproses Hari Ini</div>
              <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
              </div>
            </div>
            <div class="text-4xl font-extrabold text-slate-800 mb-2">1,240 <span class="text-xl font-bold text-slate-500">kg</span></div>
            <div class="flex items-center gap-1 text-sm text-emerald-600 font-semibold">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>
              </svg>
              +12% dari kemarin
            </div>
          </div>


        </div>

        <!-- ── MACHINE STATUS ── -->
        <div>
          <div class="flex items-center gap-3 mb-4">
            <h2 class="text-base font-bold text-slate-800">Status Mesin Cuci</h2>
            <span class="text-[11px] font-bold bg-slate-100 text-slate-500 px-2.5 py-1 rounded-lg uppercase tracking-wide">12 Unit Total</span>
          </div>

          <div class="grid grid-cols-2 gap-4">

            <!-- Mesin #01 – AKTIF -->
            <div class="card bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 p-5 animate-fadeUp d4">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <div class="font-bold text-slate-800 text-sm">Mesin #01</div>
                  <div class="text-xs text-slate-400 mt-0.5">Industrial Series X-500</div>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700">• Aktif</span>
              </div>
              <div class="flex items-baseline gap-2 mb-3">
                <span class="text-2xl font-extrabold text-slate-800">45</span>
                <span class="text-sm text-slate-500">menit tersisa</span>
                <span class="ml-auto text-xs text-slate-400 font-medium">Siklus: Heavy Cotton</span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 mb-4">
                <div class="progress bg-blue-500 h-2 rounded-full" style="width:55%"></div>
              </div>
              <div class="flex gap-2">
                <div class="flex-1 text-center">
                  <div class="text-[10px] font-bold uppercase tracking-wide text-blue-600">Pencucian</div>
                  <div class="mt-1.5 h-1 rounded-full bg-blue-500"></div>
                </div>
                <div class="flex-1 text-center">
                  <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Pembilasan</div>
                  <div class="mt-1.5 h-1 rounded-full bg-slate-100"></div>
                </div>
                <div class="flex-1 text-center">
                  <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Pengeringan</div>
                  <div class="mt-1.5 h-1 rounded-full bg-slate-100"></div>
                </div>
              </div>
            </div>

            <!-- Mesin #02 – IDLE -->
            <div class="card bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-slate-200 p-5 animate-fadeUp d5">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <div class="font-bold text-slate-800 text-sm">Mesin #02</div>
                  <div class="text-xs text-slate-400 mt-0.5">Industrial Series X-500</div>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500">Idle</span>
              </div>
              <div class="flex flex-col items-center justify-center py-6 gap-3">
                <button class="w-12 h-12 rounded-full bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600 transition-colors">
                  <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
                <span class="text-sm text-slate-400">Siap untuk muatan baru</span>
              </div>
            </div>

            <!-- Mesin #03 – PEMELIHARAAN -->
            <div class="card bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-orange-400 p-5 animate-fadeUp d6">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <div class="font-bold text-slate-800 text-sm">Mesin #03</div>
                  <div class="text-xs text-slate-400 mt-0.5">Heavy Duty Turbo</div>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-lg bg-orange-100 text-orange-600">Pemeliharaan</span>
              </div>
              <div class="flex items-start gap-2.5 mt-2 bg-orange-50 rounded-xl p-3.5">
                <svg class="w-4 h-4 text-orange-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/>
                </svg>
                <div>
                  <div class="text-sm font-semibold text-orange-700">Penggantian filter terjadwal.</div>
                  <div class="text-xs text-orange-500 mt-0.5">Estimasi selesai: 14:00 WIB</div>
                </div>
              </div>
            </div>

            <!-- Mesin #04 – AKTIF -->
            <div class="card bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 p-5 animate-fadeUp d7">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <div class="font-bold text-slate-800 text-sm">Mesin #04</div>
                  <div class="text-xs text-slate-400 mt-0.5">Industrial Series X-500</div>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700">• Aktif</span>
              </div>
              <div class="flex items-baseline gap-2 mb-3">
                <span class="text-2xl font-extrabold text-slate-800">12</span>
                <span class="text-sm text-slate-500">menit tersisa</span>
                <span class="ml-auto text-xs text-slate-400 font-medium">Siklus: Delicates</span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 mb-4">
                <div class="progress bg-blue-500 h-2 rounded-full" style="width:82%"></div>
              </div>
              <div class="flex gap-2">
                <div class="flex-1 text-center">
                  <div class="text-[10px] font-bold uppercase tracking-wide text-blue-400">Selesai</div>
                  <div class="mt-1.5 h-1 rounded-full bg-blue-300"></div>
                </div>
                <div class="flex-1 text-center">
                  <div class="text-[10px] font-bold uppercase tracking-wide text-blue-400">Selesai</div>
                  <div class="mt-1.5 h-1 rounded-full bg-blue-300"></div>
                </div>
                <div class="flex-1 text-center">
                  <div class="text-[10px] font-bold uppercase tracking-wide text-blue-600">Pengeringan</div>
                  <div class="mt-1.5 h-1 rounded-full bg-blue-500"></div>
                </div>
              </div>
            </div>
                    <!-- Tips Card -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-md animate-fadeUp d5">
          <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
              </svg>
            </div>
            <div class="font-bold text-sm">Tips Efisiensi Hari Ini</div>
          </div>
          <p class="text-blue-100 text-xs leading-relaxed">
            Suhu air diatur ke 40°C untuk mesin #01 &amp; #04 guna menghemat energi tanpa mengurangi kualitas kebersihan bahan katun.
          </p>
        </div>

          </div>
        </div>
      </div><!-- /left -->



      </div><!-- /right -->

    </div><!-- /two-col -->

  </main>
</div><!-- /main area -->

</div><!-- /flex wrapper -->

</body>
@endsection