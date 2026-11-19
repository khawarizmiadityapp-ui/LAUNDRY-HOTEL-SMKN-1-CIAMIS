<?php $__env->startSection('title', 'Operations Hub'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .progress-step.active .step-icon {
        background-color: #3b82f6; /* blue-500 */
        color: white;
        border-color: #3b82f6;
    }
    .progress-step.completed .step-icon {
        background-color: #10b981; /* emerald-500 */
        color: white;
        border-color: #10b981;
    }
    .progress-step.completed .step-line {
        background-color: #10b981;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-8 max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Operations Hub</h1>
            <p class="text-slate-500 mt-1">Kelola progres cucian pelanggan secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <input type="text" placeholder="Cari Kode Invoice..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $activeTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Map status to 4 major stages
                // Status enum: 'diterima', 'disortir', 'dicuci', 'dikeringkan', 'disetrika', 'dipacking', 'selesai', 'diambil'
                $currentStatus = $trx->status;
                
                $stepWashing = in_array($currentStatus, ['dikeringkan', 'disetrika', 'dipacking', 'selesai', 'diambil']) ? 'completed' : (in_array($currentStatus, ['diterima', 'disortir', 'dicuci']) ? 'active' : '');
                
                $stepIroning = in_array($currentStatus, ['dipacking', 'selesai', 'diambil']) ? 'completed' : ($currentStatus == 'disetrika' || $currentStatus == 'dikeringkan' ? 'active' : '');
                
                $stepPacking = in_array($currentStatus, ['selesai', 'diambil']) ? 'completed' : ($currentStatus == 'dipacking' ? 'active' : '');
                
                $stepDelivery = in_array($currentStatus, ['diambil']) ? 'completed' : ($currentStatus == 'selesai' ? 'active' : '');
            ?>
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-lg font-bold text-slate-800"><?php echo e($trx->transaksi_code); ?></h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700"><?php echo e(strtoupper($trx->service_type)); ?></span>
                        </div>
                        <p class="text-sm text-slate-500 font-medium"><?php echo e($trx->customer_name); ?> • <?php echo e(number_format($trx->weight,1)); ?> kg</p>
                    </div>
                </div>

                
                <div class="flex items-center justify-between mb-8 relative px-2">
                    <!-- Line behind the steps -->
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 z-0 rounded-full"></div>
                    
                    
                    <div class="progress-step <?php echo e($stepWashing); ?> relative z-10 flex flex-col items-center gap-2 w-1/4">
                        <div class="step-icon w-8 h-8 rounded-full border-2 border-slate-200 bg-white flex items-center justify-center transition-colors">
                            <?php if($stepWashing == 'completed'): ?>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            <?php else: ?>
                                <span class="text-xs font-bold">1</span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-slate-600">Cuci</span>
                        
                        <?php if($stepWashing == 'active'): ?>
                            <form action="<?php echo e(route('petugas_piket.tasks.updateStatus', $trx->id)); ?>" method="POST" class="mt-2 text-center absolute top-full">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="dikeringkan">
                                <button type="submit" class="whitespace-nowrap px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">Selesai Cuci</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    
                    <div class="progress-step <?php echo e($stepIroning); ?> relative z-10 flex flex-col items-center gap-2 w-1/4">
                        <div class="step-icon w-8 h-8 rounded-full border-2 border-slate-200 bg-white flex items-center justify-center transition-colors">
                            <?php if($stepIroning == 'completed'): ?>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            <?php else: ?>
                                <span class="text-xs font-bold">2</span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-slate-600">Ironing</span>
                        
                        <?php if($stepIroning == 'active'): ?>
                            <form action="<?php echo e(route('petugas_piket.tasks.updateStatus', $trx->id)); ?>" method="POST" class="mt-2 text-center absolute top-full">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="dipacking">
                                <button type="submit" class="whitespace-nowrap px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">Selesai Ironing</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    
                    <div class="progress-step <?php echo e($stepPacking); ?> relative z-10 flex flex-col items-center gap-2 w-1/4">
                        <div class="step-icon w-8 h-8 rounded-full border-2 border-slate-200 bg-white flex items-center justify-center transition-colors">
                            <?php if($stepPacking == 'completed'): ?>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            <?php else: ?>
                                <span class="text-xs font-bold">3</span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-slate-600">Packing</span>
                        
                        <?php if($stepPacking == 'active'): ?>
                            <form action="<?php echo e(route('petugas_piket.tasks.updateStatus', $trx->id)); ?>" method="POST" class="mt-2 text-center absolute top-full">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="whitespace-nowrap px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">Selesai Pack</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    
                    <div class="progress-step <?php echo e($stepDelivery); ?> relative z-10 flex flex-col items-center gap-2 w-1/4">
                        <div class="step-icon w-8 h-8 rounded-full border-2 border-slate-200 bg-white flex items-center justify-center transition-colors">
                            <?php if($stepDelivery == 'completed'): ?>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            <?php else: ?>
                                <span class="text-xs font-bold">4</span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-slate-600">Delivery</span>
                        
                        <?php if($stepDelivery == 'active'): ?>
                            <form action="<?php echo e(route('petugas_piket.tasks.updateStatus', $trx->id)); ?>" method="POST" class="mt-2 text-center absolute top-full">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="diambil">
                                <button type="submit" class="whitespace-nowrap px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">Diantar/Diambil</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                
                <div class="h-6"></div> 
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-12 flex flex-col items-center justify-center bg-slate-50 border border-slate-200 border-dashed rounded-2xl">
                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                <p class="text-slate-500 font-medium text-center">Belum ada pesanan aktif saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.petugas_piket', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\petugas_piket\tasks\index.blade.php ENDPATH**/ ?>