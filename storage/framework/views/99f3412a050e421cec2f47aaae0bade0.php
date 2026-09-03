<?php $__env->startSection('title', 'Laporan Barang Habis Pakai (BHP)'); ?>
<?php $__env->startSection('page-title', 'Laporan Barang Habis Pakai (BHP)'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">

    
    <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
        <a href="<?php echo e(route('admin.pengeluaran.index')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition">
            <i class="fas fa-receipt mr-1.5 text-slate-400"></i> Pengeluaran Riil
        </a>
        <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition">
            <i class="fas fa-file-invoice-dollar mr-1.5 text-slate-400"></i> Pengajuan Belanja
        </a>
        <a href="<?php echo e(route('admin.pengeluaran.bhp')); ?>"
           class="px-4 py-2.5 text-sm font-bold border-b-2 border-blue-600 text-blue-600 bg-blue-50/50 rounded-t-lg transition flex items-center gap-2">
            <i class="fas fa-boxes text-blue-600"></i> Laporan Barang Habis Pakai (BHP)
            <?php if($itemKritisCount > 0): ?>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-500 text-white rounded-full animate-pulse"><?php echo e($itemKritisCount); ?> Kritis</span>
            <?php endif; ?>
        </a>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Item BHP</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($totalJenisBhp); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Deterjen, pewangi, plastik, dll</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-pump-soap text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kemasan Tersedia</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e(number_format($totalStokUnits, 0, ',', '.')); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Botol / Sachet / Pcs</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                <i class="fas fa-cubes text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Stok Kritis / Menipis</p>
                <p class="text-2xl font-bold text-rose-600 mt-1"><?php echo e($itemKritisCount + $itemMenipisCount); ?></p>
                <p class="text-[11px] text-rose-500/80 mt-0.5"><?php echo e($itemKritisCount); ?> habis, <?php echo e($itemMenipisCount); ?> menipis</p>
            </div>
            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 shadow-sm">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengeluaran BHP Bulan Ini</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($pengeluaranBhpBulanIni, 0, ',', '.')); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e(now()->translatedFormat('F Y')); ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="<?php echo e(route('admin.pengeluaran.bhp')); ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Cari nama barang habis pakai..."
                       class="w-full pl-9 pr-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-10">
            </div>

            <select name="category" onchange="this.form.submit()"
                    class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[140px] cursor-pointer">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e(ucfirst($cat)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="status" onchange="this.form.submit()"
                    class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[130px] cursor-pointer">
                <option value="">Semua Kondisi</option>
                <option value="aman" <?php echo e(request('status') == 'aman' ? 'selected' : ''); ?>>Stok Aman</option>
                <option value="menipis" <?php echo e(request('status') == 'menipis' ? 'selected' : ''); ?>>Perlu Belanja</option>
                <option value="kritis" <?php echo e(request('status') == 'kritis' ? 'selected' : ''); ?>>Stok Kritis / Habis</option>
            </select>

            <?php if(request('search') || request('category') || request('status')): ?>
                <a href="<?php echo e(route('admin.pengeluaran.bhp')); ?>" class="text-xs font-semibold text-rose-500 hover:text-rose-600 transition">Reset</a>
            <?php endif; ?>
        </form>

        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.pengeluaran.bhp.pdf', request()->query())); ?>"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md transition-all active:scale-95">
                <i class="fas fa-file-pdf text-rose-400"></i>
                Cetak Laporan BHP (PDF)
            </a>
            <a href="<?php echo e(route('admin.pengajuan_belanja.create')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition-all active:scale-95">
                <i class="fas fa-cart-plus"></i>
                Ajukan Belanja BHP
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Nama Barang Habis Pakai</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Satuan Kemasan</th>
                        <th class="px-6 py-4">Kapasitas / Isi</th>
                        <th class="px-6 py-4">Stok Minimum</th>
                        <th class="px-6 py-4">Sisa Stok Fisik</th>
                        <th class="px-6 py-4">Status Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $min = $item->minimum_stock ?? 5;
                        $stock = $item->stock_units;
                        
                        if ($stock <= $min) {
                            $badgeClass = 'bg-rose-100 text-rose-700 border-rose-200';
                            $badgeLabel = 'Kritis / Habis';
                            $meterColor = 'bg-rose-500';
                            $percent = max(5, min(100, ($stock / max(1, $min * 2)) * 100));
                        } elseif ($stock <= ($min * 2)) {
                            $badgeClass = 'bg-amber-100 text-amber-700 border-amber-200';
                            $badgeLabel = 'Perlu Belanja';
                            $meterColor = 'bg-amber-500';
                            $percent = max(15, min(100, ($stock / max(1, $min * 2)) * 100));
                        } else {
                            $badgeClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                            $badgeLabel = 'Stok Aman';
                            $meterColor = 'bg-emerald-500';
                            $percent = min(100, ($stock / max(1, $min * 3)) * 100);
                        }
                    ?>
                    <tr class="hover:bg-slate-50/70 transition">
                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900"><?php echo e($item->name); ?></div>
                            <div class="text-xs text-slate-400">ID: #<?php echo e($item->id); ?></div>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-[11px] font-semibold uppercase rounded-lg bg-slate-100 text-slate-700">
                                <?php echo e($item->category); ?>

                            </span>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold capitalize text-slate-700">
                            <?php echo e($item->unit_type); ?>

                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-600">
                            <?php echo e($item->capacity_per_unit); ?> <?php echo e($item->unit_of_measurement); ?>

                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                            <?php echo e($item->minimum_stock); ?> <?php echo e($item->unit_type); ?>

                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="font-bold text-slate-900 font-mono text-sm">
                                    <?php echo e($item->stock_units); ?> <?php echo e($item->unit_type); ?>

                                </div>
                            </div>
                            <?php if($item->unit_of_measurement !== 'pcs' && $item->stock_subunits > 0): ?>
                                <div class="text-[11px] text-blue-600 font-medium mt-0.5">
                                    <i class="fas fa-tint text-[9px] mr-1"></i>+<?php echo e($item->stock_subunits); ?> <?php echo e($item->unit_of_measurement); ?> (kemasan terbuka)
                                </div>
                            <?php endif; ?>
                            <div class="w-28 bg-slate-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                <div class="<?php echo e($meterColor); ?> h-1.5 rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                            </div>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg border <?php echo e($badgeClass); ?>">
                                <?php echo e($badgeLabel); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <i class="fas fa-boxes text-3xl mb-3 text-slate-300 block"></i>
                            Belum ada data barang habis pakai.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($items->hasPages()): ?>
        <div class="px-6 py-4 border-t border-slate-100">
            <?php echo e($items->links('vendor.pagination.custom')); ?>

        </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Riwayat Pemakaian & Penyesuaian Stok BHP Terakhir</h3>
                <p class="text-xs text-slate-400 mt-0.5">Catatan log penyesuaian stok bahan laundry oleh petugas & admin</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase">
                    <tr>
                        <th class="px-4 py-3">Tanggal & Waktu</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Pemohon</th>
                        <th class="px-4 py-3">Penyesuaian</th>
                        <th class="px-4 py-3">Alasan</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $recentAdjustments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap text-slate-500 font-mono"><?php echo e($adj->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="px-4 py-3 font-semibold text-slate-900"><?php echo e($adj->inventory->name ?? 'Barang'); ?></td>
                        <td class="px-4 py-3"><?php echo e($adj->requester->name ?? 'Petugas'); ?></td>
                        <td class="px-4 py-3 font-mono font-bold <?php echo e($adj->adjustment > 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                            <?php echo e($adj->adjustment > 0 ? '+' : ''); ?><?php echo e($adj->adjustment); ?> unit
                        </td>
                        <td class="px-4 py-3 text-slate-600"><?php echo e($adj->reason ?: '-'); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full <?php echo e($adj->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($adj->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700')); ?>">
                                <?php echo e(ucfirst($adj->status)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada riwayat penyesuaian stok.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pengeluaran/bhp.blade.php ENDPATH**/ ?>