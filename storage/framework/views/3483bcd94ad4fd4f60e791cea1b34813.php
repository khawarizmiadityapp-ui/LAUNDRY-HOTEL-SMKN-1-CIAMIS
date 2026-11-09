

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name'     => 'modal',
    'title'    => 'Modal',
    'maxWidth' => 'md', // 'sm' | 'md' | 'lg' | 'xl'
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
    'name'     => 'modal',
    'title'    => 'Modal',
    'maxWidth' => 'md', // 'sm' | 'md' | 'lg' | 'xl'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $maxWidthClass = match($maxWidth) {
        'sm'  => 'max-w-sm',
        'lg'  => 'max-w-lg',
        'xl'  => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
?>

<div
    x-data="{ open: false }"
    x-on:open-modal.window="$event.detail === '<?php echo e($name); ?>' && (open = true)"
    x-on:close-modal.window="$event.detail === '<?php echo e($name); ?>' && (open = false)"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    x-cloak
>
    
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
    ></div>

    
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full <?php echo e($maxWidthClass); ?> bg-white rounded-2xl shadow-2xl shadow-slate-300/40 overflow-hidden"
    >
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-800"><?php echo e($title); ?></h3>
            <button
                @click="open = false"
                class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                aria-label="Tutup modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <div class="px-6 py-5">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\components\components\modal.blade.php ENDPATH**/ ?>