<?php
    $maintenance = $maintenance ?? get_maintenance_config();
    $adminWA = \App\Models\Setting::getValue('admin_wa', '6282116035029');
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($maintenance['title']); ?> — Bening Laundry SMKN 1 Ciamis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col items-center justify-center p-6">

    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl shadow-slate-200/70 border border-slate-100 p-8 sm:p-10 text-center space-y-6">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
            </svg>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Sedang Dalam Perbaikan
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo e($maintenance['title']); ?></h1>
            <p class="text-sm text-slate-600 leading-relaxed"><?php echo e($maintenance['message']); ?></p>
        </div>

        <?php if(!empty($maintenance['eta'])): ?>
            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Estimasi selesai: <?php echo e($maintenance['eta']); ?></span>
            </div>
        <?php endif; ?>

        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="https://wa.me/<?php echo e($adminWA); ?>" target="_blank"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.993.582 1.942.872 2.796.872 3.181 0 5.768-2.586 5.768-5.766 0-3.181-2.587-5.759-5.768-5.759zm3.376 8.204c-.149.418-.752.766-1.042.812-.291.046-.66.082-2.18-.546-1.52-.628-2.502-2.179-2.576-2.278-.074-.099-.607-.808-.607-1.542 0-.734.385-1.096.522-1.245.137-.149.299-.186.398-.186.099 0 .199 0 .285.005.092.005.215-.035.337.257.123.292.42 1.026.457 1.101.037.075.062.162.012.261-.049.099-.074.161-.148.248-.075.087-.157.194-.224.26-.075.075-.153.156-.066.305.087.149.387.638.831 1.032.572.508 1.054.665 1.203.74.149.074.236.062.323-.037.087-.099.373-.434.472-.583.099-.149.199-.124.336-.074.137.05.869.41 1.018.484.149.074.248.112.285.174.037.062.037.36-.112.778zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.434 5.176L2 22l4.957-1.398A9.956 9.956 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
                Hubungi Admin
            </a>
            <a href="<?php echo e(route('login')); ?>"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                Login Super Admin
            </a>
        </div>
    </div>

    <p class="text-xs text-slate-400 mt-6">&copy; <?php echo e(date('Y')); ?> Bening Laundry SMKN 1 Ciamis</p>
</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\errors\maintenance.blade.php ENDPATH**/ ?>