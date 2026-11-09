<?php $__env->startSection('title', 'Manajemen Layanan - Bening Laundry'); ?>
<?php $__env->startSection('content'); ?>

<div x-data="{}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Layanan</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola daftar harga dan jenis jasa pembersihan pakaian.</p>
    </div>
    <button
        @click="$dispatch('open-modal', { name: 'tambah-layanan' })"
        class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 active:scale-95
               text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-brand-600/25
               transition-all duration-150 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Layanan Baru
    </button>
</div>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Jenis Layanan</p>
            <p class="text-3xl font-bold text-slate-800 leading-tight"><?php echo e($totalLayanan); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Layanan Terlaris</p>
            <p class="text-lg font-bold text-slate-800 leading-tight"><?php echo e($layananTerlaris); ?></p>
        </div>
    </div>

    
    <div class="rounded-2xl shadow-md p-5 flex items-center gap-4
                <?php echo e($semuaAktif ? 'bg-brand-600 text-white' : 'bg-amber-500 text-white'); ?>">
        <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wider">Status Operasional</p>
            <p class="text-xl font-bold text-white leading-tight">
                <?php echo e($semuaAktif ? 'Semua Aktif' : 'Ada Nonaktif'); ?>

            </p>
        </div>
    </div>

</div>


<?php $aktivTab = request('kategori', 'semua'); ?>

<div id="kategori-tabs" class="flex items-center gap-2 mb-5">
    <?php $__currentLoopData = [
        ['key' => 'semua',   'label' => 'Semua'],
        ['key' => 'kiloan',  'label' => 'Cuci Kiloan'],
        ['key' => 'satuan',  'label' => 'Cuci Satuan'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.layanan.index', ['kategori' => $tab['key']])); ?>"
           class="px-5 py-2 rounded-xl text-sm font-medium transition-all
                  <?php echo e($aktivTab === $tab['key']
                      ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20'
                      : 'bg-white text-slate-500 border border-slate-200 hover:border-brand-400 hover:text-brand-600'); ?>">
            <?php echo e($tab['label']); ?>

        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">

    <?php $__empty_1 = true; $__currentLoopData = $layanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

    <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-5
                hover:shadow-lg hover:-translate-y-1 transition-all duration-200"
         x-data="{ aktif: <?php echo e($layanan->status ? 'true' : 'false'); ?>, loading: false }">

        
        <div class="flex items-start justify-between mb-3">

            
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                        <?php echo e(match($layanan->icon) {
                            'bolt'     => 'bg-brand-600',
                            'hourglass'=> 'bg-slate-100',
                            default    => 'bg-slate-100',
                        }); ?>">
                <?php switch($layanan->icon):
                    case ('bolt'): ?>
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <?php break; ?>
                    <?php case ('hourglass'): ?>
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?php break; ?>
                    <?php case ('bed'): ?>
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 12H3v8h18v-8h-2M5 12V7a2 2 0 012-2h10a2 2 0 012 2v5M5 12h14" />
                        </svg>
                        <?php break; ?>
                    <?php case ('shoe'): ?>
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 19h18M5 19V10l3-3h8l3 3v9" />
                        </svg>
                        <?php break; ?>
                    <?php case ('droplet'): ?>
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 2C6.5 2 3 8 3 12a9 9 0 0018 0c0-4-3.5-10-9-10z" />
                        </svg>
                        <?php break; ?>
                    <?php default: ?> 
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 3H5L3 9l3 1V21h12V10l3-1-2-6h-4m-6 0a3 3 0 006 0" />
                        </svg>
                <?php endswitch; ?>
            </div>

            
            <div class="flex items-center gap-2">
                <?php if($layanan->badge): ?>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                 <?php echo e($layanan->badge === 'Populer'
                                     ? 'bg-orange-100 text-orange-600'
                                     : 'bg-emerald-100 text-emerald-600'); ?>">
                        <?php echo e($layanan->badge); ?>

                    </span>
                <?php endif; ?>

                
                <button @click="toggleStatus(<?php echo e($layanan->id); ?>, $data)"
                        :class="aktif ? 'bg-brand-600' : 'bg-slate-200'"
                        :disabled="loading"
                        class="relative inline-flex items-center w-10 h-5 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-500/30 cursor-pointer">
                    <span :class="aktif ? 'translate-x-5' : 'translate-x-1'"
                          class="inline-block w-3.5 h-3.5 bg-white rounded-full shadow transition-transform duration-200"></span>
                </button>
            </div>
        </div>

        
        <h3 class="font-semibold text-slate-800 text-sm leading-snug mb-1"><?php echo e($layanan->nama); ?></h3>

        <?php if($layanan->estimasi): ?>
            <div class="flex items-center gap-1 text-slate-400 text-xs mb-3">
                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <?php echo e($layanan->estimasi_label); ?>

            </div>
        <?php endif; ?>

        
        <div class="mb-4">
            <span class="text-xs text-slate-400 font-medium">Rp</span>
            <span class="text-xl font-bold text-slate-800">
                <?php echo e(number_format($layanan->harga, 0, ',', '.')); ?>

            </span>
            <span class="text-xs text-slate-400 font-medium"><?php echo e($layanan->satuan); ?></span>
        </div>

        
        <div class="relative inline-block text-left">
            <button onclick="toggleDropdown('dropdown-layanan-<?php echo e($layanan->id); ?>')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                           hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                </svg>
            </button>
            
            <div id="dropdown-layanan-<?php echo e($layanan->id); ?>" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                <button @click="$dispatch('open-modal', { name: 'edit-layanan', data: <?php echo e(json_encode(['id' => $layanan->id, 'nama' => $layanan->nama, 'harga' => $layanan->harga, 'estimasi' => $layanan->estimasi, 'badge' => $layanan->badge])); ?> })"
                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors w-full text-left">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Harga
                </button>

                <div class="h-px bg-slate-50 my-1"></div>

                <form action="<?php echo e(route('admin.layanan.destroy', $layanan)); ?>" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit"
                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

    </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
        <svg class="w-14 h-14 mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg>
        <p class="text-sm font-medium">Belum ada layanan</p>
        <p class="text-xs mt-1">Tambahkan layanan pertama Anda</p>
    </div>
    <?php endif; ?>

</div>


<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col sm:flex-row items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-brand-600 flex items-center justify-center shrink-0 shadow-lg shadow-brand-600/30">
        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
    </div>
    <div class="flex-1 text-center sm:text-left">
        <h3 class="font-bold text-slate-800 text-base">Butuh jenis layanan khusus?</h3>
        <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">
            Anda dapat menambahkan kategori baru seperti "Cuci Sepatu"
            atau "Perawatan Kulit" untuk memperluas jangkauan bisnis Anda.
        </p>
    </div>
    <a href="#kategori-tabs" 
       class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 active:scale-95
                   text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-brand-600/20
                   transition-all whitespace-nowrap">
        Konfigurasi Kategori
    </a>
</div>

<?php echo $__env->make('admin.layanan.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('admin.layanan.edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<script>
    function toggleStatus(id) {
        const components = document.querySelectorAll(`[data-layanan-id="${id}"]`);

        fetch(`/admin/layanan/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Gagal');
        })
        .catch(() => {
            // Revert jika gagal
            // Alpine akan menangani UI state-nya
        });
    }
</script>


<script>
    window.toggleStatus = function(id, component) {
        component.loading = true;
        fetch(`/admin/layanan/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                component.aktif = data.status;
            } else {
                throw new Error('Gagal memperbarui status');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal mengubah status layanan');
        })
        .finally(() => {
            component.loading = false;
        });
    };
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\layanan\index.blade.php ENDPATH**/ ?>