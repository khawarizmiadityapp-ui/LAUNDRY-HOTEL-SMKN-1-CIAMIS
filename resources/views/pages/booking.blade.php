<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Pesanan — Bening Laundry</title>
    
    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind Play CDN --}}
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
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .step-transition { transition: all 0.3s ease-in-out; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased" x-data="bookingApp()">

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="bg-white border-b border-slate-100 py-5 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-sky-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-900 text-slate-800 font-black tracking-tight uppercase">Bening</p>
            </a>
            
            {{-- Stepper Progress (Desktop) --}}
            <div class="hidden md:flex items-center gap-4">
                <template x-for="i in [1, 2, 3]">
                    <div class="flex items-center gap-4">
                        <div 
                            :class="step >= i ? 'bg-sky-500 text-white shadow-lg shadow-sky-100' : 'bg-slate-100 text-slate-400'"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                            x-text="i"
                        ></div>
                        <div x-show="i < 3" class="w-12 h-1 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-500 transition-all duration-500" :style="'width: ' + (step > i ? '100%' : '0%')"></div>
                        </div>
                    </div>
                </template>
            </div>

            <a href="{{ url('/') }}" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-red-500 transition-colors">Batal</a>
        </div>
    </nav>

    <main class="py-12 px-4">
        <div class="max-w-7xl mx-auto lg:grid lg:grid-cols-12 lg:gap-12 items-start">
            
            {{-- --- LEFT COLUMN: STEPS (Form) --- --}}
            <div class="lg:col-span-8 space-y-8 h-full">
                
                <form action="{{ route('order.store-booking') }}" method="POST" id="booking-form">
                    @csrf
                    <input type="hidden" name="layanan_id" x-model="form.layanan_id">
                    <input type="hidden" name="delivery_type" x-model="form.delivery_type">
                    <input type="hidden" name="payment_method" x-model="form.payment_method">

                    {{-- --- STEP 1: PILIH LAYANAN --- --}}
                    <div x-show="step === 1" x-transition class="space-y-8 step-transition">
                        <div class="space-y-2">
                            <h2 class="text-3xl font-900 font-black text-slate-900 tracking-tight">Pilih Layanan Utama</h2>
                            <p class="text-slate-400 font-medium tracking-wide">Silakan pilih jenis laundry yang paling sesuai untuk Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($layanans as $svc)
                            <button 
                                type="button"
                                @click="selectLayanan(@js($svc->only(['id', 'nama', 'harga'])))"
                                :class="form.layanan_id == {{ $svc->id }} ? 'border-sky-500 bg-white ring-4 ring-sky-50' : 'border-white bg-white hover:border-slate-200'"
                                class="group relative text-left p-8 rounded-[2rem] border-2 shadow-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50"
                            >
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center group-hover:bg-sky-500 group-hover:text-white transition-colors duration-300">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                         </svg>
                                    </div>
                                    <div x-show="form.layanan_id == {{ $svc->id }}" class="w-6 h-6 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-lg transform scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 transition-colors uppercase tracking-tight">{{ $svc->nama }}</h3>
                                <div class="mt-2 flex items-baseline gap-1">
                                    <span class="text-xl font-900 font-black text-sky-500 tracking-tight">Rp{{ number_format($svc->harga, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">/ Kg</span>
                                </div>
                            </button>
                            @endforeach
                        </div>

                        <div class="pt-8 border-t border-slate-100 flex justify-end">
                            <button 
                                type="button" 
                                @click="nextStep()" 
                                :disabled="!form.layanan_id"
                                :class="!form.layanan_id ? 'opacity-50 cursor-not-allowed bg-slate-300' : 'bg-slate-900 hover:bg-slate-800'"
                                class="px-10 py-4 text-white rounded-2xl font-bold transition-all flex items-center gap-3 uppercase tracking-widest text-sm shadow-xl shadow-slate-200"
                            >
                                Lanjut Ke Pengantaran
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- --- STEP 2: JENIS PENGANTARAN --- --}}
                    <div x-show="step === 2" x-transition class="space-y-8 step-transition" x-cloak>
                        <div class="space-y-2">
                            <h2 class="text-3xl font-900 font-black text-slate-900 tracking-tight">Jenis Pengantaran & Durasi</h2>
                            <p class="text-slate-400 font-medium tracking-wide">Pilih kecepatan pengerjaan yang Anda inginkan.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button 
                                    type="button"
                                    @click="form.delivery_type = 'regular'"
                                    :class="form.delivery_type == 'regular' ? 'border-orange-500 bg-white ring-4 ring-orange-50' : 'border-white bg-white hover:border-slate-200'"
                                    class="group p-8 rounded-[2rem] border-2 shadow-sm text-left transition-all duration-300"
                                >
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 uppercase tracking-tight">Reguler</h3>
                                    <p class="text-sm text-slate-400 font-medium mt-1">Estimasi 3 Hari Kerja</p>
                                    <p class="text-xs font-bold text-orange-500 mt-2">Biaya Standar</p>
                                </button>

                                <button 
                                    type="button"
                                    @click="form.delivery_type = 'express'"
                                    :class="form.delivery_type == 'express' ? 'border-sky-500 bg-white ring-4 ring-sky-50' : 'border-white bg-white hover:border-slate-200'"
                                    class="group p-8 rounded-[2rem] border-2 shadow-sm text-left transition-all duration-300"
                                >
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center group-hover:bg-sky-500 group-hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 uppercase tracking-tight">Express</h3>
                                    <p class="text-sm text-slate-400 font-medium mt-1">Estimasi 1-2 Hari Kerja</p>
                                    <p class="text-xs font-bold text-sky-500 mt-2">+ Rp10.000 Surcharge</p>
                                </button>
                        </div>

                        <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" @click="step = 1" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">Kembali</button>
                            <button 
                                type="button" 
                                @click="nextStep()" 
                                class="bg-slate-900 hover:bg-slate-800 px-10 py-4 text-white rounded-2xl font-bold transition-all flex items-center gap-3 uppercase tracking-widest text-sm shadow-xl shadow-slate-200"
                            >
                                Lanjut Ke Biodata
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- --- STEP 3: DATA PRIBADI --- --}}
                    <div x-show="step === 3" x-transition class="space-y-8 step-transition" x-cloak>
                        <div class="space-y-2">
                             <h2 class="text-3xl font-900 font-black text-slate-900 tracking-tight">Data Diri & Penjemputan</h2>
                             <p class="text-slate-400 font-medium tracking-wide">Lengkapi data Anda agar kami dapat menghubungi Anda dengan cepat.</p>
                        </div>

                        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" name="customer_name" required placeholder="Masukkan nama Anda" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-sm font-semibold focus:bg-white focus:border-sky-500/30 outline-none transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                                    <input type="text" name="phone" required placeholder="08..." class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-sm font-semibold focus:bg-white focus:border-sky-500/30 outline-none transition-all">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Alamat Penjemputan</label>
                                <textarea name="address" rows="3" placeholder="Jl. Contoh No. 123..." class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-sm font-semibold focus:bg-white focus:border-sky-500/30 outline-none transition-all"></textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Estimasi Berat (Kg)</label>
                                    <div class="relative">
                                        <input type="number" step="0.1" name="weight_estimate" x-model="form.weight" placeholder="1.0" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-sm font-semibold focus:bg-white focus:border-sky-500/30 outline-none transition-all">
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 font-bold text-xs uppercase">Kg</div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Catatan</label>
                                    <input type="text" name="note" placeholder="Contoh: Titip di pos satpam" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-sm font-semibold focus:bg-white focus:border-sky-500/30 outline-none transition-all">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Metode Pembayaran</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    {{-- Cash --}}
                                    <button type="button" @click="form.payment_method = 'cash'"
                                            :class="form.payment_method === 'cash' ? 'border-sky-500 bg-sky-50/50 ring-2 ring-sky-100' : 'border-slate-100 bg-slate-50 hover:border-slate-200'"
                                            class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all">
                                        <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center shadow-lg shadow-green-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Cash</span>
                                    </button>

                                    {{-- Dana --}}
                                    <button type="button" @click="form.payment_method = 'dana'"
                                            :class="form.payment_method === 'dana' ? 'border-sky-500 bg-sky-50/50 ring-2 ring-sky-100' : 'border-slate-100 bg-slate-50 hover:border-slate-200'"
                                            class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all">
                                        <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-200 uppercase font-black text-[10px]">
                                            Dana
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Dana</span>
                                    </button>

                                    {{-- QRIS --}}
                                    <button type="button" @click="form.payment_method = 'qris'"
                                            :class="form.payment_method === 'qris' ? 'border-sky-500 bg-sky-50/50 ring-2 ring-sky-100' : 'border-slate-100 bg-slate-50 hover:border-slate-200'"
                                            class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all">
                                        <div class="w-10 h-10 rounded-xl bg-purple-500 text-white flex items-center justify-center shadow-lg shadow-purple-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 11v1m4-12h1m-1 10h1m-12-10h1m-1 10h1m1-9h4v4h-4v-4zm1 1h2v2h-2v-2zm-4 7h4v4h-4v-4zm1 1h2v2h-2v-2zm7 0h4v4h-4v-4zm1 1h2v2h-2v-2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">QRIS</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" @click="step = 2" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">Kembali</button>
                            @auth
                            <button 
                                type="submit" 
                                class="bg-slate-900 hover:bg-slate-800 px-12 py-5 text-white rounded-2xl font-black text-sm uppercase tracking-[0.1em] shadow-2xl shadow-slate-900/30 transition-all transform hover:-translate-y-1 active:scale-95"
                            >
                                Selesaikan Pesanan
                            </button>
                            @else
                            <button 
                                type="button" 
                                @click="handleLoginRedirect()"
                                class="bg-slate-900 hover:bg-slate-800 px-12 py-5 text-white rounded-2xl font-black text-sm uppercase tracking-[0.1em] shadow-2xl shadow-slate-900/30 transition-all transform hover:-translate-y-1 active:scale-95"
                            >
                                Login untuk Menyelesaikan
                            </button>
                            @endauth
                        </div>
                    </div>
                </form>
            </div>

            {{-- --- RIGHT COLUMN: ORDER SUMMARY (Sticky Sidebar) --- --}}
            <aside class="lg:col-span-4 mt-12 lg:mt-0 sticky top-32 z-10">
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                    {{-- Receipt Header --}}
                    <div class="bg-slate-900 p-8 text-white relative overflow-hidden">
                        {{-- Decorative pattern for premium feel --}}
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-sky-500/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] text-sky-400 font-bold uppercase tracking-[0.15em] mb-1">Ringkasan Pesanan</p>
                            <h3 class="text-xl font-800 font-extrabold tracking-tight italic">Detail Biaya</h3>
                        </div>
                    </div>

                    {{-- Receipt Content --}}
                    <div class="p-8 space-y-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-0.5">
                                    <p class="text-xs font-bold text-slate-800 uppercase tracking-tight" x-text="form.service_name || 'Belum dipilih'"></p>
                                    <p class="text-[10px] text-slate-400 font-medium">Layanan Laundry</p>
                                </div>
                                <span class="text-sm font-bold text-slate-800" x-text="formatCurrency(form.service_price * form.weight)">Rp0</span>
                            </div>

                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-0.5">
                                    <p class="text-xs font-bold text-slate-800 uppercase tracking-tight" x-text="form.delivery_type == 'express' ? 'Express Speed' : 'Reguler Tempo'"></p>
                                    <p class="text-[10px] text-slate-400 font-medium">Jenis Pengantaran</p>
                                </div>
                                <span class="text-sm font-bold text-slate-800" x-text="form.delivery_type == 'express' ? 'Rp10.000' : 'Rp0'">Rp0</span>
                            </div>

                            <div class="pt-4 border-t border-dashed border-slate-100 flex justify-between items-center">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Estimasi</p>
                                <p class="text-2xl font-900 font-black text-slate-900 tracking-tight" x-text="formatTotal()">Rp0</p>
                            </div>
                        </div>

                        {{-- Tips or Help --}}
                        <div class="p-4 bg-slate-50 rounded-2xl">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-[10px] text-slate-500 font-medium leading-relaxed">
                                    Harga di atas adalah **estimasi**. Harga final akan dikonfirmasi admin setelah berat riil dicek.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </main>

    <script>
        function bookingApp() {
            return {
                step: 1,
                form: {
                    layanan_id: null,
                    service_name: '',
                    service_price: 0,
                    delivery_type: 'regular',
                    payment_method: 'cash',
                    weight: 1,
                },
                selectLayanan(svc) {
                    this.form.layanan_id = svc.id;
                    this.form.service_name = svc.nama;
                    this.form.service_price = svc.harga;
                },
                nextStep() {
                    if (this.step < 3) this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                handleLoginRedirect() {
                    sessionStorage.setItem('booking_draft', JSON.stringify(this.form));
                    window.location.href = "{{ route('customer.login') }}";
                },
                init() {
                    const draft = sessionStorage.getItem('booking_draft');
                    if (draft) {
                        try {
                            const parsed = JSON.parse(draft);
                            this.form = { ...this.form, ...parsed };
                            // Jika ada layanan terpilih, langsung lompat ke step 3 (Biodata)
                            if (this.form.layanan_id) {
                                this.step = 3;
                            }
                        } catch (e) {
                            console.error("Gagal load draft", e);
                        }
                    }
                },
                formatCurrency(val) {
                    return 'Rp' + new Intl.NumberFormat('id-ID').format(val || 0);
                },
                formatTotal() {
                    let total = (this.form.service_price * (this.form.weight || 0));
                    if (this.form.delivery_type === 'express') total += 10000;
                    return this.formatCurrency(total);
                }
            }
        }
    </script>
</body>
</html>
