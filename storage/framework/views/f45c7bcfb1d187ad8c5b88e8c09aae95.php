

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name'        => '',
    'label'       => '',
    'type'        => 'text',
    'placeholder' => '',
    'required'    => false,
    'disabled'    => false,
    'error'       => null,
    'hint'        => null,
    'prefix'      => null,  // icon/text sebelum input
    'suffix'      => null,  // icon/text sesudah input
    'value'       => null,
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
    'name'        => '',
    'label'       => '',
    'type'        => 'text',
    'placeholder' => '',
    'required'    => false,
    'disabled'    => false,
    'error'       => null,
    'hint'        => null,
    'prefix'      => null,  // icon/text sebelum input
    'suffix'      => null,  // icon/text sesudah input
    'value'       => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex flex-col gap-1.5">
    
    <?php if($label): ?>
        <label
            for="<?php echo e($name); ?>"
            class="text-xs font-semibold text-slate-700"
        >
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-red-500 ml-0.5">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    
    <div class="relative flex items-center">
        
        <?php if($prefix): ?>
            <div class="absolute left-3 flex items-center text-slate-400 pointer-events-none">
                <?php echo $prefix; ?>

            </div>
        <?php endif; ?>

        
        <input
            id="<?php echo e($name); ?>"
            name="<?php echo e($name); ?>"
            type="<?php echo e($type); ?>"
            placeholder="<?php echo e($placeholder); ?>"
            value="<?php echo e($value ?? old($name)); ?>"
            <?php if($required): ?> required <?php endif; ?>
            <?php if($disabled): ?> disabled <?php endif; ?>
            <?php echo e($attributes->merge([
                'class' => '
                    w-full text-sm text-slate-700 bg-white
                    border rounded-xl px-3.5 py-2.5
                    transition-all duration-150 outline-none
                    placeholder-slate-400
                    focus:ring-2 focus:ring-sky-500/20 focus:border-sky-400
                    disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed
                    ' . ($error ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400' : 'border-slate-200 hover:border-slate-300')
                    . ($prefix ? ' pl-9' : '')
                    . ($suffix ? ' pr-9' : '')
            ])); ?>

        >

        
        <?php if($suffix): ?>
            <div class="absolute right-3 flex items-center text-slate-400 pointer-events-none">
                <?php echo $suffix; ?>

            </div>
        <?php endif; ?>
    </div>

    
    <?php if($hint && !$error): ?>
        <p class="text-[11px] text-slate-400"><?php echo e($hint); ?></p>
    <?php endif; ?>

    
    <?php if($error): ?>
        <p class="text-[11px] text-red-500 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?php echo e($error); ?>

        </p>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\components\components\input.blade.php ENDPATH**/ ?>