<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="<?php echo e(__('Pagination Navigation')); ?>" class="flex items-center gap-1.5">
        
        <?php if($paginator->onFirstPage()): ?>
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed border border-slate-100 transition-all duration-200" aria-disabled="true" aria-label="<?php echo e(__('pagination.previous')); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 bg-white hover:text-brand-600 hover:bg-brand-50 border border-slate-200 hover:border-brand-200 transition-all duration-200 active:scale-90" aria-label="<?php echo e(__('pagination.previous')); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <span class="inline-flex items-center justify-center w-8 h-8 text-slate-300 text-xs font-bold" aria-disabled="true"><?php echo e($element); ?></span>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-600 text-white text-xs font-bold shadow-md shadow-brand-200 ring-2 ring-brand-100 transition-all duration-300 pointer-events-none" aria-current="page"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 bg-white text-xs font-bold border border-slate-200 hover:text-brand-600 hover:bg-brand-50 hover:border-brand-200 transition-all duration-200 active:scale-90" aria-label="<?php echo e(__('Go to page :page', ['page' => $page])); ?>">
                            <?php echo e($page); ?>

                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 bg-white hover:text-brand-600 hover:bg-brand-50 border border-slate-200 hover:border-brand-200 transition-all duration-200 active:scale-90" aria-label="<?php echo e(__('pagination.next')); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        <?php else: ?>
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed border border-slate-100 transition-all duration-200" aria-disabled="true" aria-label="<?php echo e(__('pagination.next')); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>