<?php $__env->startSection('title', 'Edit Pengeluaran - Bening Laundry'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pengeluaran</h1>

    <div class="mt-6 bg-white border border-gray-200 rounded-2xl p-6">
        <form action="<?php echo e(route('admin.pengeluaran.update', $pengeluaran)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengeluaran</label>
                <input type="text" name="nama" value="<?php echo e(old('nama', $pengeluaran->nama)); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                        <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kategori->id); ?>" <?php echo e(old('kategori_id', $pengeluaran->kategori_id) == $kategori->id ? 'selected' : ''); ?>><?php echo e($kategori->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="text-indigo-600 hover:underline">Kelola kategori</a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo e(old('tanggal', optional($pengeluaran->tanggal)->format('Y-m-d'))); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" min="0" name="nominal" value="<?php echo e(old('nominal', $pengeluaran->nominal)); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2.5"><?php echo e(old('keterangan', $pengeluaran->keterangan)); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Bon Baru (opsional)</label>
                <input type="file" name="bon_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-white">
                <?php if($pengeluaran->bon_file): ?>
                    <a href="<?php echo e(asset('storage/' . $pengeluaran->bon_file)); ?>" target="_blank" class="inline-block mt-2 text-sm text-blue-600 hover:underline">Lihat bon saat ini</a>
                <?php endif; ?>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold">Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.pengeluaran.index')); ?>" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pengeluaran\edit.blade.php ENDPATH**/ ?>