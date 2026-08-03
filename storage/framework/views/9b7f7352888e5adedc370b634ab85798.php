<?php $__env->startSection('title', 'Tambah Pengeluaran - Bening Laundry'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengeluaran</h1>
    <p class="text-sm text-gray-500 mt-1">Kategori pengeluaran dibatasi ke 3 item utama dan dapat dilengkapi file bon.</p>

    <div class="mt-6 bg-white border border-gray-200 rounded-2xl p-6">
        <p class="text-xs uppercase tracking-wider text-gray-500">ID Transaksi</p>
        <p class="text-lg font-semibold text-gray-800 mb-5"><?php echo e($idTransaksi); ?></p>

        <form action="<?php echo e(route('admin.pengeluaran.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengeluaran</label>
                <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                        <option value="">Pilih kategori</option>
                        <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kategori->id); ?>" <?php echo e(old('kategori_id') == $kategori->id ? 'selected' : ''); ?>><?php echo e($kategori->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <a href="<?php echo e(route('admin.kategori-pengeluaran.index')); ?>" class="text-indigo-600 hover:underline">Kelola kategori</a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo e(old('tanggal', now()->toDateString())); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" min="0" name="nominal" value="<?php echo e(old('nominal')); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2.5" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2.5"><?php echo e(old('keterangan')); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Bon (JPG/PNG/PDF, max 2MB)</label>
                <input type="file" name="bon_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-white">
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold">Simpan</button>
                <a href="<?php echo e(route('admin.pengeluaran.index')); ?>" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pengeluaran/create.blade.php ENDPATH**/ ?>