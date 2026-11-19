<?php
    $maintenance = get_maintenance_config();
    $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();
?>

<?php if($maintenance['enabled']): ?>
<div <?php echo e($attributes->merge(['class' => 'bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 border border-amber-300/80 rounded-2xl p-4 shadow-sm mb-5'])); ?> role="alert">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-400/30 text-amber-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                </svg>
            </div>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-amber-600 text-white">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Sedang Maintenance
                    </span>
                    <h3 class="text-sm font-extrabold text-amber-950"><?php echo e($maintenance['title']); ?></h3>
                </div>
                <p class="text-xs text-amber-900/90 mt-1 leading-relaxed"><?php echo e($maintenance['message']); ?></p>
                <?php if(!empty($maintenance['eta'])): ?>
                    <p class="text-[11px] font-semibold text-amber-800 mt-1.5 inline-flex items-center gap-1.5 bg-white/70 px-2.5 py-1 rounded-lg border border-amber-200/80">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Estimasi selesai: <?php echo e($maintenance['eta']); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>

        <?php if($isSuperAdmin): ?>
            <a href="<?php echo e(route('admin.settings')); ?>#maintenance-panel"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-900 hover:bg-amber-950 text-white text-xs font-bold rounded-xl transition shrink-0 self-start sm:self-center shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Atur / Matikan
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\components\maintenance-banner.blade.php ENDPATH**/ ?>