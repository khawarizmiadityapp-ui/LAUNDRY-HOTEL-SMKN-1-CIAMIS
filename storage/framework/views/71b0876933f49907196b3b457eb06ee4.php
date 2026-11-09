<?php $__env->startSection('title', 'Detail Pengeluaran - Bening Laundry'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl bg-white border border-gray-200 rounded-2xl p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-5">Detail Pengeluaran</h1>

    <dl class="space-y-3 text-sm">
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">ID Transaksi</dt>
            <dd class="col-span-2 font-semibold"><?php echo e($pengeluaran->id_transaksi); ?></dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Nama</dt>
            <dd class="col-span-2"><?php echo e($pengeluaran->nama); ?></dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Kategori</dt>
            <dd class="col-span-2"><?php echo e($pengeluaran->kategori); ?></dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Tanggal</dt>
            <dd class="col-span-2"><?php echo e(optional($pengeluaran->tanggal)->format('d/m/Y')); ?></dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Nominal</dt>
            <dd class="col-span-2 font-semibold">Rp <?php echo e(number_format($pengeluaran->nominal, 0, ',', '.')); ?></dd>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">Keterangan</dt>
            <dd class="col-span-2"><?php echo e($pengeluaran->keterangan ?? '-'); ?></dd>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <dt class="text-gray-500">File Bon</dt>
            <dd class="col-span-2">
                <?php if($pengeluaran->bon_file): ?>
                    <a href="<?php echo e(asset('storage/' . $pengeluaran->bon_file)); ?>" target="_blank" class="text-blue-600 hover:underline">Lihat File Bon</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </dd>
        </div>
    </dl>

    <div class="mt-6 flex items-center gap-3">
        <a href="<?php echo e(route('admin.pengeluaran.edit', $pengeluaran)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">Edit</a>
        <a href="<?php echo e(route('admin.pengeluaran.index')); ?>" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-xl">Kembali</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pengeluaran\show.blade.php ENDPATH**/ ?>