<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login admin - Bening Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-login {
            background-color: #ffffffff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(219, 234, 254, 0.5) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(219, 234, 254, 0.3) 0px, transparent 50%),
                url('/images/background.jpeg');
            background-size: cover;
            background-position: center;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .input-focus:focus-within {
            border-color: #0047cc;
            box-shadow: 0 0 0 2px rgba(0, 71, 204, 0.1);
        }
        .primary-button {
            background: #0047cc;
            transition: all 0.3s ease;
        }
        .primary-button:hover:not(:disabled) {
            background: #0037a3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 71, 204, 0.3);
        }
        .primary-button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="min-h-screen bg-login flex flex-col items-center justify-between py-8 px-4">

    <!-- Header Logo -->
    <div class="flex items-center gap-2 mb-4">
        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
            <i class="fa-solid fa-thumbs-up text-lg"></i>
        </div>
        <span class="text-2xl font-bold text-blue-700">Bening Laundry</span>
    </div>

    <!-- Login Card Container -->
    <div class="w-full max-w-[400px]">
        <div class="glass-card rounded-[20px] shadow-2xl p-10 md:p-12">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang</h1>
            </div>

            <!-- Alert Messages -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg text-red-700 text-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Email / Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-at text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email-input"
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full pl-12 pr-6 py-4 bg-slate-50/50 border border-slate-200 rounded-[22px] outline-none transition-all focus:border-blue-600 focus:bg-white @error('email') border-red-500 @enderror"
                               placeholder="nama@beninglaundry.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex justify-between items-end px-1">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password-input"
                               required
                               class="w-full pl-12 pr-14 py-4 bg-slate-50/50 border border-slate-200 rounded-[22px] outline-none transition-all focus:border-blue-600 focus:bg-white @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        <button type="button" 
                                onclick="togglePassword()" 
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fa-regular fa-eye text-lg" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="flex justify-end mt-2">
                        <a href="{{ route('password.request') }}" class="text-[10px] font-extrabold text-[#0047cc] hover:text-blue-800 tracking-wider uppercase bg-blue-50 px-3 py-1 rounded-full transition-colors">
                            Lupa Password?
                        </a>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" name="remember" id="remember" class="w-5 h-5 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer accent-blue-600">
                    <label for="remember" class="text-sm font-medium text-slate-500 cursor-pointer hover:text-slate-700 transition-colors">Ingat Saya</label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            id="login-button"
                            disabled
                            class="primary-button w-full text-white font-bold py-4 rounded-[22px] flex items-center justify-center gap-2 group transition-all duration-300">
                        <span>Masuk</span>
                        <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

                <!-- Security Text -->
                <div class="pt-6 border-t border-slate-100 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-50 rounded-full">
                        <i class="fa-solid fa-shield-halved text-[10px] text-slate-400"></i>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.1em]">2026 RPL_Sentinel. All rights reserved.</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quote -->
        <div class="mt-10 text-center">
            <p class="text-slate-400 italic text-sm tracking-wide">"Kejernihan dalam setiap serat kain."</p>
        </div>
    </div>


    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('toggleIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-regular', 'fa-eye');
                icon.classList.add('fa-solid', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-solid', 'fa-eye-slash');
                icon.classList.add('fa-regular', 'fa-eye');
            }
        }

        // Handle Dynamic Login Button
        const emailInput = document.getElementById('email-input');
        const passwordInput = document.getElementById('password-input');
        const loginButton = document.getElementById('login-button');

        function checkInputs() {
            if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
                loginButton.disabled = false;
            } else {
                loginButton.disabled = true;
            }
        }

        emailInput.addEventListener('input', checkInputs);
        passwordInput.addEventListener('input', checkInputs);

        // Initial check in case of autofill or old input
        window.addEventListener('load', checkInputs);
    </script>
</body>
</html>
