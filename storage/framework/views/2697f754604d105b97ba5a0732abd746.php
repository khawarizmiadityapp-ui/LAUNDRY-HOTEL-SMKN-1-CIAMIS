    

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status' => 'pending', 'size' => 'sm']));

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

foreach (array_filter((['status' => 'pending', 'size' => 'sm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $statusMap = [
        'pending'  => ['label' => 'Menunggu',     'class' => 'bg-slate-100 text-slate-600 ring-slate-200'],
        'sorting'  => ['label' => 'Pemilahan',    'class' => 'bg-orange-50 text-orange-600 ring-orange-200'],
        'spotting' => ['label' => 'Spotting',     'class' => 'bg-amber-50 text-amber-600 ring-amber-200'],
        'washing'  => ['label' => 'Dicuci',       'class' => 'bg-sky-50 text-sky-600 ring-sky-200'],
        'ironing'  => ['label' => 'Ironing',      'class' => 'bg-violet-50 text-violet-600 ring-violet-200'],
        'packing'  => ['label' => 'Packing',      'class' => 'bg-indigo-50 text-indigo-600 ring-indigo-200'],
        'ready'    => ['label' => 'Siap Diambil', 'class' => 'bg-emerald-50 text-emerald-600 ring-emerald-200'],
        'paid'     => ['label' => 'Lunas',        'class' => 'bg-teal-50 text-teal-600 ring-teal-200'],
        'hold'     => ['label' => 'Hold',         'class' => 'bg-red-50 text-red-600 ring-red-200'],
    ];

    $config = $statusMap[$status] ?? $statusMap['pending'];

    $dotColorMap = [
        'pending'  => 'bg-slate-400',
        'sorting'  => 'bg-orange-500',
        'spotting' => 'bg-amber-500',
        'washing'  => 'bg-sky-500',
        'ironing'  => 'bg-violet-500',
        'packing'  => 'bg-indigo-500',
        'ready'    => 'bg-emerald-500',
        'paid'     => 'bg-teal-500',
        'hold'     => 'bg-red-500',
    ];
    $dotColor = $dotColorMap[$status] ?? 'bg-slate-400';

    $sizeClass = $size === 'xs' ? 'text-[10px] px-2 py-0.5' : 'text-xs px-2.5 py-1';
?>

<span class="inline-flex items-center gap-1.5 font-medium rounded-full ring-1 <?php echo e($config['class']); ?> <?php echo e($sizeClass); ?>">
    <span class="w-1.5 h-1.5 rounded-full <?php echo e($dotColor); ?>

        <?php echo e(in_array($status, ['washing', 'ironing']) ? 'animate-pulse' : ''); ?>

    "></span>
    <?php echo e($config['label']); ?>

</span>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\components\components\badge.blade.php ENDPATH**/ ?>