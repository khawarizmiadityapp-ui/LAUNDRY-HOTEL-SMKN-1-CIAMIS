<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMKN 1 Ciamis Laundry — Jasa Laundry Profesional & Terpercaya</title>
    
    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind & AlpineJS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-white text-slate-900 antialiased" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    {{-- ===================== NAVBAR ===================== --}}
    <nav 
        class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-100 py-5 shadow-sm"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logobening.jpeg') }}" alt="Bening Laundry Logo" class="w-12 h-12 object-contain group-hover:scale-110 transition-transform duration-300">
                <div class="hidden sm:block">
                    <p class="text-sm font-900 text-slate-800 font-black tracking-tight uppercase">Bening</p>
                    <p class="text-[10px] text-sky-600 font-bold uppercase tracking-widest">Laundry Services</p>
                </div>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="#tracking" class="text-xs font-semibold text-slate-600 hover:text-sky-500 transition-colors uppercase tracking-wider">Lacak Order</a>
                <a href="#services" class="text-xs font-semibold text-slate-600 hover:text-sky-500 transition-colors uppercase tracking-wider">Layanan</a>
                
                {{-- Customer Auth removed --}}
            </div>

            {{-- Mobile Button --}}
            <button class="md:hidden p-2 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="relative pt-16 pb-12 md:pt-24 md:pb-20 overflow-hidden">

        @if(Session::has('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-50 mb-8 mt-4">
                <div class="bg-emerald-500 text-white rounded-2xl p-6 shadow-xl flex flex-col md:flex-row items-center gap-4">
                    <div class="bg-white/20 p-3 rounded-xl shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-1 tracking-tight">Pemesanan Berhasil!</h4>
                        <p class="text-emerald-50 font-medium">{{ Session::get('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Decoration --}}
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full opacity-10 pointer-events-none">
            <svg viewBox="0 0 100 100" class="w-full h-full text-sky-500 fill-current">
                <circle cx="100" cy="0" r="80" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center md:text-left md:flex items-center gap-12">
                <div class="flex-1 space-y-6">
                    <h1 class="text-4xl md:text-6xl font-800 text-slate-900 font-extrabold tracking-tight leading-[1.1]">
                        Pakaian Bersih, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-cyan-400 uppercase">Cepat & Rapih.</span>
                    </h1>
                    <p class="text-base text-slate-500 max-w-lg leading-relaxed">
                        Nikmati layanan laundry premium dengan standar kebersihan tertinggi. Kami pastikan setiap helai pakaian Anda kembali bersih, rapi, dan sejuk dipandang.
                    </p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                        <a href="https://wa.me/628123456789?text=Halo%20Bening%20Laundry,%20saya%20ingin%20memesan%20layanan%20laundry." target="_blank" class="px-8 py-4 bg-sky-500 text-white rounded-2xl font-bold shadow-xl shadow-sky-500/30 hover:bg-sky-600 hover:-translate-y-1 transition-all">Pesan Layanan</a>
                        <a href="#tracking" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all">Lacak Cucian</a>
                    </div>
                </div>

                <div class="hidden md:block flex-1 relative">
                    {{-- Mockup or Image --}}
                    <div class="relative w-full aspect-square rounded-[3rem] bg-slate-100 overflow-hidden shadow-2xl rotate-3 transition-transform hover:rotate-0 duration-500">
                         <div class="absolute inset-0 bg-gradient-to-br from-sky-400/20 to-transparent"></div>
                         {{-- Placeholder for actual image --}}
                         <div class="flex items-center justify-center h-full text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                         </div>
                    </div>
                    {{-- Floating Checkmark --}}
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-50 flex items-center justify-center">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SERVICES SECTION ===================== --}}
    <section id="services" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 space-y-4">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Layanan Kami</h2>
                <div class="w-20 h-1.5 bg-sky-500 mx-auto rounded-full"></div>
                <p class="text-slate-500 max-w-2xl mx-auto">Kami menawarkan berbagai pilihan layanan laundry yang disesuaikan dengan kebutuhan Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Service Cards --}}
                @forelse($layanans as $svc)
                <div class="group bg-white p-8 rounded-3xl border border-slate-100 hover:border-sky-100 hover:shadow-2xl hover:shadow-sky-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-sky-50 text-sky-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-sky-500 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $svc->nama }}</h3>
                    <div class="flex items-baseline gap-1 mb-4">
                        <span class="text-2xl font-800 text-sky-500 font-extrabold">Rp{{ number_format($svc->harga, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">/ Kg</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-400 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Estimasi {{ $svc->estimasi }} Hari
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10 text-slate-400 font-medium italic">
                    Daftar layanan sedang diperbarui...
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ===================== TRACKING SECTION ===================== --}}
    <section id="tracking" class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-8 md:p-12 border border-slate-100">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Lacak Status Cucian</h2>
                    <p class="text-sm text-slate-400 font-medium">Masukkan nomor nota Anda untuk melihat progres pengerjaan.</p>
                </div>

                <form action="{{ route('track.status') }}" method="GET" class="space-y-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-300 group-focus-within:text-sky-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="nota_number"
                            name="nota_number" 
                            required
                            value="{{ old('nota_number') }}"
                            placeholder="Contoh: TRX-20260401-ABCD" 
                            class="block w-full pl-14 pr-6 py-5 bg-slate-50 border-2 border-transparent rounded-2xl text-slate-700 font-semibold placeholder-slate-300 focus:bg-white focus:border-sky-500/30 focus:ring-0 focus:outline-none transition-all"
                        >
                    </div>
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-900/25 hover:bg-slate-800 hover:shadow-2xl hover:shadow-slate-900/40 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-xs">
                        Cek Status Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                    @if(Session::has('error'))
                        <p class="text-center text-sm font-semibold text-red-500 mt-4">{{ Session::get('error') }}</p>
                    @endif
                </form>
            </div>
        </div>
    </section>


    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-slate-900 pt-20 pb-10 text-white overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-20">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logobening.jpeg') }}" alt="Bening Laundry Logo" class="w-12 h-12 object-contain bg-white rounded-xl shadow-lg">
                        <p class="text-xl font-800 font-extrabold tracking-tight">Bening<span class="text-sky-500"> Laundry</span></p>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                        Hadir untuk memberikan solusi kebersihan pakaian dengan hasil yang bening, wangi, dan terpercaya untuk seluruh keluarga.
                    </p>
                </div>

                <div class="space-y-6">
                    <h4 class="text-lg font-bold tracking-tight">Lokasi Kami</h4>
                    <div class="flex gap-4 text-slate-400 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Jl. K.H. Ahmad Dahlan No.14, Ciamis, Jawa Barat. <br>
                        Gedung Hotel SMKN 1 Ciamis
                    </div>
                </div>

                <div class="space-y-6">
                    <h4 class="text-lg font-bold tracking-tight">Hubungi Kami</h4>
                    <a href="https://wa.me/628123456789" target="_blank" class="flex items-center gap-4 p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all group">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">WhatsApp Chat</p>
                            <p class="text-sm font-bold">+62 812-3456-789</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">© {{ date('Y') }} SMKN 1 Ciamis Laundry. Semua hak dilindungi.</p>
                <div class="flex gap-6 text-[10px] uppercase font-bold tracking-widest text-slate-500">
                    <a href="#" class="hover:text-white">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    @if(Session::has('error') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackingSection = document.getElementById('tracking');
            if (trackingSection) {
                trackingSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            const inputField = document.getElementById('nota_number');
            if (inputField) {
                inputField.focus();
                // Put cursor at the end of the text
                const val = inputField.value;
                inputField.value = '';
                inputField.value = val;
            }
        });
    </script>
    @endif

</body>
</html>
