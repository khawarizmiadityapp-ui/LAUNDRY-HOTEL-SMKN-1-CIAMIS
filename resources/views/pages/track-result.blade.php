<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Order #{{ $order->transaksi_code }} — SMKN 1 Ciamis Laundry</title>
    
    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind Play CDN (Emergency Fix for Vite build issues) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    fontWeight: {
                         '800': '800',
                         '900': '900',
                    }
                }
            }
        }
    </script>


    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased p-6 md:p-12">

    <div class="max-w-3xl mx-auto">
        {{-- Back Button --}}
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-sky-500 font-bold mb-8 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Beranda
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100 mb-10">
            {{-- Header --}}
            <div class="bg-slate-900 p-8 md:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1 text-center md:text-left">
                    <p class="text-[10px] text-sky-400 font-bold uppercase tracking-[0.2em] mb-2">Status Cucian Anda</p>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Order #{{ $order->transaksi_code }}</h1>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 text-center">
                    <p class="text-[10px] text-white/60 font-bold uppercase tracking-widest mb-1">Status Saat Ini</p>
                    <span class="text-sm font-extrabold uppercase tracking-widest text-sky-400">{{ strtoupper($order->status) }}</span>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-8 md:p-12 space-y-12">
                
                {{-- Timeline --}}
                <div class="relative">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight mb-8">Progress Pengerjaan</h3>
                    
                    <div class="space-y-8">
                        @php
                            $stages = [
                                'washing' => 'Pencucian',
                                'ironing' => 'Setrika',
                                'packing' => 'Packing & QC'
                            ];
                        @endphp

                        {{-- Order Created Step (Always Done) --}}
                        <div class="flex gap-6 relative">
                            <div class="absolute left-4 top-8 bottom-[-2rem] w-0.5 bg-sky-500"></div>
                            <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 bg-sky-500 text-white shadow-lg shadow-sky-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="flex-1 pb-2">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">Pesanan Diterima</h4>
                                    <span class="text-[10px] font-bold text-slate-400">{{ $order->created_at->format('H:i') }}</span>
                                </div>
                                <p class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">Oleh: {{ $order->user->name ?? 'Customer Service' }}</p>
                            </div>
                        </div>

                        @foreach($order->tasks as $index => $task)
                        @php
                            $isDone = $task->status === 'completed';
                            $isLast = $index === $order->tasks->count() - 1;
                            $stageLabel = $stages[$task->stage] ?? ucfirst($task->stage);
                        @endphp
                        <div class="flex gap-6 relative">
                            @if(!$isLast)
                            <div class="absolute left-4 top-8 bottom-[-2rem] w-0.5 {{ $isDone ? 'bg-sky-500' : 'bg-slate-100' }}"></div>
                            @endif

                            <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 
                                {{ $isDone ? 'bg-sky-500 text-white shadow-lg shadow-sky-200' : 'bg-slate-100 text-slate-300' }}">
                                @if($isDone)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-200 animate-pulse"></div>
                                @endif
                            </div>

                            <div class="flex-1 {{ !$isLast ? 'pb-2' : '' }}">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-extrabold {{ $isDone ? 'text-slate-800' : 'text-slate-300' }} tracking-tight">
                                        {{ $stageLabel }}
                                        @php 
                                            $prevCompleted = ($index == 0 && true) || ($index > 0 && $order->tasks[$index-1]->status == 'completed');
                                        @endphp
                                        @if($task->status == 'pending' && $prevCompleted)
                                            <span class="ml-2 px-2 py-0.5 bg-sky-50 text-sky-600 rounded text-[10px] font-bold uppercase tracking-widest border border-sky-100">Antrean</span>
                                        @endif
                                    </h4>
                                    @if($isDone)
                                    <span class="text-[10px] font-bold text-slate-400">{{ $task->completed_at->format('H:i') }}</span>
                                    @endif
                                </div>
                                <p class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                    @if($isDone) 
                                        Selesai oleh {{ $task->petugas->name ?? 'Petugas' }}
                                    @else 
                                        Menunggu Proses
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endforeach

                        {{-- Delivery/Ready Step --}}
                        <div class="flex gap-6 relative">
                            @php $isReady = $order->status === 'selesai' || $order->status === 'diambil'; @endphp
                            <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 
                                {{ $isReady ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-slate-100 text-slate-300' }}">
                                @if($isReady)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-extrabold {{ $isReady ? 'text-slate-800' : 'text-slate-300' }} tracking-tight">Siap Diambil</h4>
                                <p class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                    @if($isReady) Pesanan Telah Selesai @else Belum Tersedia @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Details Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-100">
                    <div class="space-y-6">
                        <h3 class="text-sm font-extrabold text-slate-400 uppercase tracking-widest">Informasi Customer</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nama Lengkap</p>
                                <p class="text-sm font-bold text-slate-800">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">No. WhatsApp</p>
                                <p class="text-sm font-bold text-slate-800">{{ $order->customer_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-sm font-extrabold text-slate-400 uppercase tracking-widest">Detail Item</h3>
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 space-y-3">
                            @foreach($order->details as $item)
                            <div class="flex justify-between items-center text-xs font-bold">
                                <span class="text-slate-500">{{ $item->layanan->nama ?? 'Layanan' }}</span>
                                <span class="text-slate-800">{{ $item->qty }} {{ $item->layanan->satuan ?? 'kg' }}</span>
                            </div>
                            @endforeach
                            <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Tagihan</span>
                                <span class="text-sm font-black text-sky-600 tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Help Banner --}}
        @php
            $adminWA = "6282116035029"; // Nomor CS SMKN 1 Ciamis
            $trackMsg = "Halo Admin Bening Laundry, saya ingin menanyakan pesanan saya dengan nomor invoice #" . $order->transaksi_code;
        @endphp
        <div class="bg-emerald-500 rounded-3xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-emerald-100">
            <div class="text-center md:text-left space-y-1">
                <h3 class="text-lg font-extrabold tracking-tight">Butuh bantuan lebih lanjut?</h3>
                <p class="text-sm text-emerald-100 font-medium">Hubungi admin kami melalui WhatsApp untuk konfirmasi.</p>
            </div>
            <a href="https://wa.me/{{ $adminWA }}?text={{ urlencode($trackMsg) }}" target="_blank" class="px-8 py-3 bg-white text-emerald-600 rounded-xl font-bold hover:bg-emerald-50 transition-all shadow-lg whitespace-nowrap">Hubungi Admin</a>
        </div>
    </div>

</body>
</html>
