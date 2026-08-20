<?php $__env->startSection('title', 'Riwayat Tugas'); ?>
<?php $__env->startSection('content'); ?>

<div class="p-6 max-w-7xl mx-auto animate-fade-in">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Tugas</h1>
        <p class="text-slate-500 mt-1">Daftar tugas yang telah diselesaikan oleh divisi <?php echo e(ucfirst($division ?? 'Piket')); ?>.</p>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Daftar Selesai</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-left px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Transaksi</th>
                        <th class="text-left px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Petugas Piket</th>
                        <th class="text-left px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Selesai</th>
                        <th class="text-left px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(isset($completedTasks) && $completedTasks->count() > 0): ?>
                        <?php $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2">
                                    <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                        #<?php echo e($task->transaksi->transaksi_code ?? '-'); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="font-bold text-slate-800"><?php echo e($task->transaksi->customer_name ?? '-'); ?></span>
                                </td>
                                <td class="px-3 py-2 text-slate-600 font-medium">
                                    <?php echo e($task->petugas_name ?? $task->petugas->nama ?? 'Sistem'); ?>

                                </td>
                                <td class="px-3 py-2 text-slate-500">
                                    <?php echo e($task->completed_at ? $task->completed_at->format('d M Y, H:i') : '-'); ?>

                                </td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider border border-emerald-100/50">
                                        <?php echo e($task->transaksi->status ?? '-'); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-3 py-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada riwayat tugas yang diselesaikan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if(isset($completedTasks) && method_exists($completedTasks, 'hasPages') && $completedTasks->hasPages()): ?>
            <div class="px-6 py-4 border-t border-slate-100">
                <?php echo e($completedTasks->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.petugas_piket', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/petugas_piket/history/index.blade.php ENDPATH**/ ?>