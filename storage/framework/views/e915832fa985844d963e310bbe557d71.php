


<?php if($highlight ?? false): ?>
<div class="bg-brand-600 rounded-2xl p-6 shadow-md shadow-brand-200 flex flex-col justify-between min-h-[130px]">
    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($icon); ?>"/>
        </svg>
    </div>
    <div>
        <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-0.5"><?php echo e($title); ?></p>
        <p class="text-2xl font-extrabold text-white leading-tight"><?php echo e($value); ?></p>
        <?php if($highlightSub ?? null): ?>
            <p class="text-xs text-blue-200 mt-0.5"><?php echo e($highlightSub); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between min-h-[130px]">
    <div class="flex items-start justify-between">
        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($icon); ?>"/>
            </svg>
        </div>
        <div class="flex flex-col items-end gap-1">
            <?php if($badge ?? null): ?>
                <span class="inline-flex items-center gap-0.5 text-xs font-bold px-2 py-0.5 rounded-full
                             <?php echo e(($badgeUp ?? true) ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500'); ?>">
                    <?php if($badgeUp ?? true): ?>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                    <?php else: ?>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    <?php endif; ?>
                    <?php echo e($badge); ?>

                </span>
            <?php endif; ?>
            <?php if($subtitle ?? null): ?>
                <span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1"><?php echo e($title); ?></p>
        <p class="text-3xl font-extrabold text-slate-800"><?php echo e($value); ?></p>
    </div>
</div>

<?php endif; ?><?php /**PATH D:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/components/stat-cardcustomer.blade.php ENDPATH**/ ?>