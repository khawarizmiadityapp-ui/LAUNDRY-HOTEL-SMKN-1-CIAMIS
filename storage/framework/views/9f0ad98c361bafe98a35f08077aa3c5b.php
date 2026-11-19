

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title'      => 'Statistik',
    'value'      => '0',
    'change'     => null,
    'changeType' => 'up', // 'up' | 'down' | 'neutral'
    'color'      => 'sky', // 'sky' | 'emerald' | 'violet' | 'amber'
    'icon'       => null,
    'prefix'     => '',
    'suffix'     => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title'      => 'Statistik',
    'value'      => '0',
    'change'     => null,
    'changeType' => 'up', // 'up' | 'down' | 'neutral'
    'color'      => 'sky', // 'sky' | 'emerald' | 'violet' | 'amber'
    'icon'       => null,
    'prefix'     => '',
    'suffix'     => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorMap = [
        'sky'     => ['bg' => 'bg-sky-50',     'icon' => 'text-sky-500',     'ring' => 'ring-sky-100'],
        'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-500', 'ring' => 'ring-emerald-100'],
        'violet'  => ['bg' => 'bg-violet-50',  'icon' => 'text-violet-500',  'ring' => 'ring-violet-100'],
        'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-500',   'ring' => 'ring-amber-100'],
    ];
    $c = $colorMap[$color] ?? $colorMap['sky'];
?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between gap-4">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-slate-500 mb-1"><?php echo e($title); ?></p>
            <p class="text-2xl font-bold text-slate-800 tracking-tight">
                <?php if($prefix): ?><span class="text-base font-semibold text-slate-500"><?php echo e($prefix); ?></span><?php endif; ?>
                <?php echo e($value); ?>

                <?php if($suffix): ?><span class="text-sm font-medium text-slate-500 ml-0.5"><?php echo e($suffix); ?></span><?php endif; ?>
            </p>
            <?php if($change): ?>
                <div class="flex items-center gap-1 mt-2">
                    <?php if($changeType === 'up'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-xs font-medium text-emerald-600"><?php echo e($change); ?></span>
                    <?php elseif($changeType === 'down'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                        <span class="text-xs font-medium text-red-600"><?php echo e($change); ?></span>
                    <?php else: ?>
                        <span class="text-xs font-medium text-slate-400"><?php echo e($change); ?></span>
                    <?php endif; ?>
                    <span class="text-[10px] text-slate-400">vs kemarin</span>
                </div>
            <?php endif; ?>
        </div>

        
        <?php if($icon): ?>
            <div class="w-11 h-11 rounded-xl <?php echo e($c['bg']); ?> ring-1 <?php echo e($c['ring']); ?> flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 <?php echo e($c['icon']); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <?php echo $icon; ?>

                </svg>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\components\components\stat-card.blade.php ENDPATH**/ ?>