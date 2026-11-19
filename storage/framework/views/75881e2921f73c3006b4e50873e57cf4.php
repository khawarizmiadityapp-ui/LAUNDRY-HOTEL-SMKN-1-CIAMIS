
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => '',
    'url' => '#',
    'icon' => '',
    'active' => false,
    'badge' => null,
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
    'label' => '',
    'url' => '#',
    'icon' => '',
    'active' => false,
    'badge' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($url); ?>"
   <?php echo e($attributes->merge(['class' => 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 ' . ($active ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900')])); ?>>
    
    <?php if($icon): ?>
        <svg class="w-[18px] h-[18px] shrink-0 <?php echo e($active ? 'text-white' : 'text-slate-400'); ?>" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="1.8" 
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($icon); ?>" />
        </svg>
    <?php endif; ?>
    
    <span class="truncate"><?php echo e($label); ?></span>
    
    <?php if($badge !== null): ?>
        <span class="ml-auto px-2 py-0.5 text-xs font-semibold rounded-full <?php echo e($active ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600'); ?>">
            <?php echo e($badge); ?>

        </span>
    <?php endif; ?>
</a>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\components\sidebar-menu-item.blade.php ENDPATH**/ ?>