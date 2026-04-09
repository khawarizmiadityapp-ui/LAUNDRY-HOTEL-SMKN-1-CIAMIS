<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Customer - Bening Laundry</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    fontWeight: { '800': '800', '900': '900' }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center p-4 antialiased text-slate-800">

    <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
        
        {{-- Left Side: Branding / Graphic --}}
        <div class="relative hidden md:flex flex-col justify-between p-12 bg-slate-900 text-white overflow-hidden">
            {{-- Decorative Background Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/20 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-600/20 rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
            
            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center shadow-lg shadow-sky-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-900 tracking-tight uppercase">Bening</span>
                </a>
                
                <h1 class="text-4xl font-900 leading-tight tracking-tight mt-8">
                    Kembali Segar.<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-400">Siap Dikenakan.</span>
                </h1>
                <p class="mt-4 text-slate-300 font-medium leading-relaxed max-w-sm">
                    Layanan laundry premium kami menyajikan kebersihan maksimal dengan perlakuan eksklusif.
                </p>
            </div>
            
            <div class="relative z-10 grid grid-cols-2 gap-4 mt-12">
                <div class="bg-white/10 backdrop-blur-sm px-4 py-3 rounded-2xl border border-white/10">
                    <div class="text-sky-400 mb-1"><i class="fa-solid fa-shirt"></i></div>
                    <div class="text-sm font-bold">Cuci & Lipat</div>
                    <div class="text-[10px] text-slate-400">Pakaian sehari-hari</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm px-4 py-3 rounded-2xl border border-white/10">
                    <div class="text-sky-400 mb-1"><i class="fa-solid fa-hands-bubbles"></i></div>
                    <div class="text-sm font-bold">Dry Clean</div>
                    <div class="text-[10px] text-slate-400">Perawatan premium</div>
                </div>
            </div>
        </div>

        {{-- Right Side: Login Form --}}
        <div class="p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white relative">
            
            {{-- Mobile Logo --}}
            <div class="mb-10 block md:hidden">
                <div class="inline-flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center shadow-lg shadow-sky-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-900 tracking-tight uppercase text-slate-900">Bening</span>
                </div>
            </div>

            <div class="space-y-3 mb-10">
                <h2 class="text-3xl font-900 tracking-tight text-slate-900">Selamat Datang Kembali</h2>
                <p class="text-slate-500 font-medium text-sm">Masuk untuk melanjutkan pesanan Anda dan melihat status pengerjaan.</p>
                <div class="inline-block px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[11px] font-bold border border-amber-200 mt-2">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Jika akun tidak terdaftar, wajib daftar terlebih dahulu.
                </div>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm font-semibold">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 text-sm font-semibold">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('customer.login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Email --}}
                <div class="space-y-2">
                    <label class="text-[11px] text-slate-500 font-bold uppercase tracking-widest ml-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                             <i class="fa-solid fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: agus@mail.com" class="block w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-slate-700 font-semibold placeholder-slate-300 focus:bg-white focus:border-sky-500/30 focus:ring-0 focus:outline-none transition-all">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-semibold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="text-[11px] text-slate-500 font-bold uppercase tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                             <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" class="block w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl text-slate-700 font-semibold placeholder-slate-300 focus:bg-white focus:border-sky-500/30 focus:ring-0 focus:outline-none transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-900/25 hover:bg-slate-800 hover:shadow-2xl hover:shadow-slate-900/40 hover:-translate-y-0.5 transition-all text-sm uppercase tracking-widest mt-2">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-8 text-center bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <p class="text-sm font-medium text-slate-600">
                    Belum punya akun? 
                    <a href="{{ route('customer.register') }}" class="text-sky-500 font-bold hover:underline transition-all">Daftar Sekarang</a>
                </p>
            </div>

        </div>
    </div>
</body>
</html>
