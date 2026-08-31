

<?php
    $colorMap = [
        'blue'   => ['bg' => 'bg-brand-50',   'icon' => 'text-brand-600',   'ring' => 'bg-brand-100'],
        'green'  => ['bg' => 'bg-emerald-50',  'icon' => 'text-emerald-600', 'ring' => 'bg-emerald-100'],
        'red'    => ['bg' => 'bg-rose-50',     'icon' => 'text-rose-500',    'ring' => 'bg-rose-100'],
        'purple' => ['bg' => 'bg-violet-50',   'icon' => 'text-violet-600',  'ring' => 'bg-violet-100'],
    ];
    $c = $colorMap[$color ?? 'blue'];
?>

<div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-shadow duration-300 p-5 flex flex-col gap-3 animate-fade-up">
    <!-- Icon + Badge -->
    <div class="flex items-start justify-between">
        <div class="w-10 h-10 rounded-xl <?php echo e($c['ring']); ?> flex items-center justify-center <?php echo e($c['icon']); ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($icon); ?>" />
            </svg>
        </div>
        <?php if(isset($badge)): ?>
            <span class="flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                         <?php echo e(($up ?? true) ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500'); ?>">
                <?php if($up ?? true): ?>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                    </svg>
                <?php else: ?>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>
                <?php endif; ?>
                <?php echo e($badge); ?>

            </span>
        <?php endif; ?>
    </div>

    <!-- Value + Label -->
    <div>
        <p class="text-2xl font-display font-700 text-slate-900 leading-tight"><?php echo e($value); ?></p>
        <p class="text-sm font-medium text-slate-500 mt-0.5"><?php echo e($label); ?></p>
    </div>

    <!-- Sub text or progress bar -->
    <?php if(isset($progress)): ?>
        <div>
            <div class="flex justify-between text-xs text-slate-400 mb-1.5">
                <span><?php echo e($sub ?? ''); ?></span>
                <span><?php echo e($progress); ?>%</span>
            </div>
            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-400 transition-all duration-700"
                     style="width: <?php echo e($progress); ?>%"></div>
            </div>
        </div>
    <?php else: ?>
        <p class="text-xs text-slate-400"><?php echo e($sub ?? ''); ?></p>
    <?php endif; ?>
</div><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/components/stat-card.blade.php ENDPATH**/ ?>