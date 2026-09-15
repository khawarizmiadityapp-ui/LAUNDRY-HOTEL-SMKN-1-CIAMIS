<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA - Bening Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-login {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(219, 234, 254, 0.6) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(219, 234, 254, 0.4) 0px, transparent 50%),
                url('/images/background.jpeg');
            background-size: cover;
            background-position: center;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .otp-box {
            width: 3.25rem;
            height: 4.1rem;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 700;
            border-radius: 1.15rem;
            border: 2px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            color: #0f172a;
        }
        .otp-box:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            transform: translateY(-2px);
        }
        .otp-box.filled {
            border-color: #3b82f6;
            background-color: #eff6ff;
            color: #1d4ed8;
        }
        @media (max-width: 480px) {
            .otp-box {
                width: 2.65rem;
                height: 3.5rem;
                font-size: 1.35rem;
                border-radius: 0.85rem;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-login flex flex-col items-center justify-center py-8 px-4">

    <!-- Header Logo -->
    <div class="flex items-center gap-3 mb-6">
        <img src="<?php echo e(asset('images/logo-bening.png')); ?>" alt="Logo Bening Laundry" class="w-11 h-11 object-contain drop-shadow-sm">
        <div>
            <span class="text-2xl font-extrabold text-blue-700 tracking-tight block leading-none">Bening Laundry</span>
            <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1 block">SECURITY PORTAL • 2FA</span>
        </div>
    </div>

    <!-- 2FA Main Card -->
    <div class="w-full max-w-[530px]">
        <div class="glass-card rounded-[28px] shadow-2xl p-6 sm:p-8 border border-white/80">
            
            <!-- PILIH METODE VERIFIKASI -->
            <div class="mb-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">PILIH METODE VERIFIKASI:</p>
                <div class="w-full py-3 px-4 rounded-2xl bg-white border-2 border-blue-600 shadow-xs flex items-center justify-center gap-2.5 text-blue-700 font-bold text-sm cursor-default">
                    <i class="fa-solid fa-mobile-screen-button text-base text-blue-600"></i>
                    <span>1. Authenticator (QR)</span>
                </div>
            </div>

            <!-- Error / Alert Message -->
            <?php if(session('error')): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl text-rose-700 text-sm flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                <span class="font-medium text-xs sm:text-sm"><?php echo e(session('error')); ?></span>
            </div>
            <?php endif; ?>

            <!-- QR Code & Setup Guide Box -->
            <div class="bg-white/90 rounded-2xl border border-slate-200/90 p-4 sm:p-5 mb-5 shadow-xs">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5">
                    
                    <!-- QR Code Frame -->
                    <div class="flex flex-col items-center shrink-0">
                        <div class="p-2 bg-white rounded-2xl border border-slate-200 shadow-2xs">
                            <img src="<?php echo e($qrCodeUrl); ?>" alt="QR Code Google Authenticator" class="w-32 h-32 sm:w-36 sm:h-36 object-contain rounded-lg">
                        </div>
                        <span class="text-[11px] text-blue-600 font-semibold mt-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-camera text-[10px]"></i> Scan dari HP Anda
                        </span>
                    </div>

                    <!-- Steps Guide -->
                    <div class="flex-1 space-y-3 text-slate-700 text-xs sm:text-sm">
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">1</span>
                            <p class="leading-snug">
                                Buka <strong class="text-slate-900 font-bold">Google Authenticator</strong> atau <strong class="text-slate-900 font-bold">Microsoft Authenticator</strong> di HP.
                            </p>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">2</span>
                            <div class="space-y-1.5 w-full">
                                <p class="leading-snug">Scan barcode atau masukkan kunci manual:</p>
                                <div class="flex items-center gap-2">
                                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 font-mono text-xs font-bold text-blue-700 tracking-wider flex-1 text-center truncate select-all">
                                        <?php echo e($formattedSecret); ?>

                                    </div>
                                    <button type="button" onclick="copySecretKey('<?php echo e($rawSecret); ?>', this)" 
                                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition shrink-0 active:scale-95">
                                        <i class="fa-regular fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OTP Input Form -->
            <form id="otpForm" action="<?php echo e(route('login.2fa.verify')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="code" id="fullCode">

                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <label class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-800">
                            MASUKKAN 6 DIGIT KODE VERIFIKASI
                        </label>
                        <span class="text-[11px] text-slate-400">Dari Authenticator</span>
                    </div>

                    <!-- 6 Digit Input Boxes -->
                    <div class="flex items-center justify-between gap-1.5 sm:gap-2.5" id="otpBoxContainer">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" autofocus data-index="0">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" data-index="1">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" data-index="2">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" data-index="3">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" data-index="4">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" data-index="5">
                    </div>
                </div>

                <!-- Footer Countdown & Info -->
                <div class="flex items-center justify-between text-xs text-slate-500 pt-0.5">
                    <div class="flex items-center gap-1.5 font-medium">
                        <i class="fa-regular fa-clock text-blue-600"></i>
                        <span>Batas Sesi: <strong id="countdown" class="text-blue-700 font-mono font-bold">02:00</strong></span>
                    </div>
                    <span class="text-slate-400 italic text-[11px]">Verifikasi otomatis saat 6 digit terisi</span>
                </div>

                <!-- Action Buttons -->
                <div class="pt-2 flex items-center justify-between gap-3">
                    <a href="<?php echo e(route('login')); ?>" 
                       onclick="event.preventDefault(); document.getElementById('cancelForm').submit();"
                       class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition py-2 px-1">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Batal / Login Kembali
                    </a>
                    
                    <button type="submit" id="submitBtn"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white font-bold rounded-2xl transition shadow-md shadow-blue-600/25 text-xs sm:text-sm flex items-center gap-2">
                        <span>Verifikasi & Masuk</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>

            <form id="cancelForm" action="<?php echo e(route('login.2fa.cancel')); ?>" method="POST" class="hidden">
                <?php echo csrf_field(); ?>
            </form>

        </div>

        <p class="text-center text-xs text-slate-400 mt-5">
            &copy; <?php echo e(date('Y')); ?> Bening Laundry SMKN 1 Ciamis • Sistem Keamanan Terpadu
        </p>
    </div>

    <!-- Interactive Scripts for OTP & Countdown -->
    <script>
        const inputs = document.querySelectorAll('.otp-box');
        const fullCodeInput = document.getElementById('fullCode');
        const otpForm = document.getElementById('otpForm');

        function updateFullCode() {
            let code = '';
            inputs.forEach(input => {
                code += input.value;
                if (input.value) {
                    input.classList.add('filled');
                } else {
                    input.classList.remove('filled');
                }
            });
            fullCodeInput.value = code;
            if (code.length === 6 && /^\d{6}$/.test(code)) {
                setTimeout(() => {
                    otpForm.submit();
                }, 150);
            }
        }

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value;
                if (val.length > 1) {
                    handlePasteData(val);
                    return;
                }
                
                if (val && /^\d$/.test(val)) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                } else {
                    input.value = '';
                }
                updateFullCode();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                    updateFullCode();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    inputs[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                handlePasteData(pasteData);
            });
        });

        function handlePasteData(text) {
            const digits = text.replace(/\D/g, '').slice(0, 6);
            if (digits) {
                digits.split('').forEach((char, idx) => {
                    if (idx < inputs.length) {
                        inputs[idx].value = char;
                    }
                });
                const nextFocus = Math.min(digits.length, inputs.length - 1);
                inputs[nextFocus].focus();
                updateFullCode();
            }
        }

        // Copy Secret Key to Clipboard
        function copySecretKey(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600 mr-1"></i> Tersalin!';
                btn.classList.add('bg-emerald-50', 'text-emerald-700');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700');
                }, 2000);
            }).catch(err => {
                alert('Kunci: ' + text);
            });
        }

        // Countdown Timer (Default 120s = 2 menit)
        let secondsLeft = <?php echo e($expiresIn ?? 120); ?>;
        const countdownEl = document.getElementById('countdown');

        function formatTimer(sec) {
            const mins = Math.floor(sec / 60);
            const remainingSec = sec % 60;
            return `${String(mins).padStart(2, '0')}:${String(remainingSec).padStart(2, '0')}`;
        }

        countdownEl.textContent = formatTimer(secondsLeft);

        const timerInterval = setInterval(() => {
            secondsLeft--;
            if (secondsLeft <= 0) {
                clearInterval(timerInterval);
                countdownEl.textContent = "00:00";
                alert("Sesi verifikasi 2 menit telah kedaluwarsa. Anda akan diarahkan kembali ke halaman login.");
                document.getElementById('cancelForm').submit();
            } else {
                countdownEl.textContent = formatTimer(secondsLeft);
            }
        }, 1000);
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/auth/2fa.blade.php ENDPATH**/ ?>