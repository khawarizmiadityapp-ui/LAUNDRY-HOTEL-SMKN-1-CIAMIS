<?php $__env->startSection('title', 'Kategori Pengeluaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Kategori Pengeluaran</h1>
            <p class="text-slate-600 mt-1">Kelola kategori untuk pengeluaran operasional</p>
        </div>
        <a href="<?php echo e(route('admin.kategori-pengeluaran.create')); ?>" 
           class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 font-medium"><?php echo e(session('success')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700 font-medium"><?php echo e(session('error')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Kategori</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo e($stats['total']); ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Kategori Aktif</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo e($stats['aktif']); ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-500 to-slate-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-100 text-sm font-medium">Tidak Aktif</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo e($stats['tidak_aktif']); ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="GET" action="<?php echo e(route('admin.kategori-pengeluaran.index')); ?>" class="flex gap-3">
            <input type="text" 
                   name="search" 
                   value="<?php echo e(request('search')); ?>" 
                   placeholder="Cari kategori..." 
                   class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                Cari
            </button>
            <?php if(request('search')): ?>
            <a href="<?php echo e(route('admin.kategori-pengeluaran.index')); ?>" class="px-6 py-3 bg-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-300 transition-colors">
                Reset
            </a>
            <?php endif; ?>
        </form>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Jumlah Digunakan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__empty_1 = true; $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-800"><?php echo e($kategori->nama); ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <?php echo e($kategori->deskripsi ?? '-'); ?>

                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?php echo e($kategori->pengeluarans_count); ?> pengeluaran
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($kategori->is_active): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Aktif
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                <span class="w-2 h-2 bg-slate-500 rounded-full mr-2"></span>
                                Nonaktif
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="openEditKategoriModal(<?php echo e($kategori->id); ?>, '<?php echo e(addslashes($kategori->nama)); ?>', '<?php echo e(preg_replace('/\r|\n/', ' ', addslashes($kategori->deskripsi))); ?>', <?php echo e($kategori->is_active ? 'true' : 'false'); ?>)" 
                                   class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <form action="<?php echo e(route('admin.kategori-pengeluaran.toggle-status', $kategori)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" 
                                            class="p-2 <?php echo e($kategori->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50'); ?> rounded-lg transition-colors" 
                                            title="<?php echo e($kategori->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                        <?php if($kategori->is_active): ?>
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        <?php else: ?>
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php endif; ?>
                                    </button>
                                </form>

                                <form action="<?php echo e(route('admin.kategori-pengeluaran.destroy', $kategori)); ?>" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-lg font-medium">Tidak ada kategori ditemukan</p>
                            <p class="text-sm mt-1">Silakan tambahkan kategori pengeluaran baru</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($kategoris->hasPages()): ?>
        <div class="px-6 py-4 border-t border-slate-200">
            <?php echo e($kategoris->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
</div>


<div id="editKategoriModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-all duration-300" onclick="if(event.target === this) closeEditKategoriModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]" style="animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Edit Kategori Pengeluaran</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi kategori</p>
            </div>
            <button onclick="closeEditKategoriModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200/50 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="editKategoriForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_kategori_nama" name="nama" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none bg-slate-50 hover:bg-white focus:bg-white">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea id="edit_kategori_deskripsi" name="deskripsi" rows="3"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none bg-slate-50 hover:bg-white focus:bg-white"></textarea>
                </div>
                <div class="mb-6 bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                    <label class="flex items-start">
                        <div class="flex items-center h-5 mt-0.5">
                            <input type="checkbox" id="edit_kategori_is_active" name="is_active" value="1"
                                   class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition-all">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-bold text-slate-700 block">Aktifkan kategori ini</span>
                            <span class="text-slate-500 text-xs mt-0.5 block">Kategori aktif akan muncul di dropdown pengeluaran</span>
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" onclick="closeEditKategoriModal()"
                            class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function openEditKategoriModal(id, nama, deskripsi, isActive) {
        document.getElementById('editKategoriModal').classList.remove('hidden');
        document.getElementById('editKategoriForm').action = `/admin/kategori-pengeluaran/${id}`;
        document.getElementById('edit_kategori_nama').value = nama;
        document.getElementById('edit_kategori_deskripsi').value = deskripsi || '';
        document.getElementById('edit_kategori_is_active').checked = isActive;
    }

    function closeEditKategoriModal() {
        document.getElementById('editKategoriModal').classList.add('hidden');
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/kategori_pengeluaran/index.blade.php ENDPATH**/ ?>