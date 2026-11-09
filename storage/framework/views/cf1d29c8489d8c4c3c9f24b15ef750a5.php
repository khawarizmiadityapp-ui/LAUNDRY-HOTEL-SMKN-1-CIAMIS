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
    <div class="flex items-center gap-3 mb-6">
        <img src="<?php echo e(asset('images/logo-bening.png')); ?>" alt="Logo Bening Laundry" class="w-12 h-12 object-contain drop-shadow-sm">
        <div>
            <span class="text-2xl font-extrabold text-blue-700 tracking-tight block leading-none">Bening Laundry</span>
            <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1 block">SMKN 1 Ciamis</span>
        </div>
    </div>

    <!-- Login Card Container -->
    <div class="w-full max-w-[400px]">
        <div class="glass-card rounded-[20px] shadow-2xl p-10 md:p-12">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang</h1>
            </div>

            <!-- Alert Messages -->
            <?php if(session('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg text-red-700 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?php echo e(session('error')); ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Email / Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email-input"
                               value="<?php echo e(old('email')); ?>" 
                               required 
                               autofocus
                               class="w-full pl-12 pr-6 py-4 bg-slate-50/50 border border-slate-200 rounded-[22px] outline-none transition-all focus:border-blue-600 focus:bg-white <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="nama@beninglaundry.com">
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-500 ml-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex justify-between items-end px-1">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password-input"
                               required
                               class="w-full pl-12 pr-14 py-4 bg-slate-50/50 border border-slate-200 rounded-[22px] outline-none transition-all focus:border-blue-600 focus:bg-white <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="••••••••">
                        <button type="button" 
                                onclick="togglePassword()" 
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <span id="toggleIcon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></span>
                        </button>
                    </div>

                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-500 ml-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <svg class="w-4 h-4 text-white group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>

                <!-- Security Text -->
                <div class="pt-6 border-t border-slate-100 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-50 rounded-full">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
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
                icon.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
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
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\auth\login.blade.php ENDPATH**/ ?>