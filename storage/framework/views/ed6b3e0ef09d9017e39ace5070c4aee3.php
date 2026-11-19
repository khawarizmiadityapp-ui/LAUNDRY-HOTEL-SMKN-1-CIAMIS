<?php $__env->startSection('title', 'Laporan Barang Habis Pakai (BHP)'); ?>
<?php $__env->startSection('page-title', 'Laporan Barang Habis Pakai (BHP)'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">

    
    <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
        <a href="<?php echo e(route('admin.pengeluaran.index')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5 inline-block text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg> Pengeluaran Riil
        </a>
        <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5 inline-block text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Pengajuan Belanja
        </a>
        <a href="<?php echo e(route('admin.pengeluaran.bhp')); ?>"
           class="px-4 py-2.5 text-sm font-bold border-b-2 border-blue-600 text-blue-600 bg-blue-50/50 rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg> Laporan Barang Habis Pakai (BHP)
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
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kemasan Tersedia</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e(number_format($totalStokUnits, 0, ',', '.')); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Botol / Sachet / Pcs</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Stok Kritis / Menipis</p>
                <p class="text-2xl font-bold text-rose-600 mt-1"><?php echo e($itemKritisCount + $itemMenipisCount); ?></p>
                <p class="text-[11px] text-rose-500/80 mt-0.5"><?php echo e($itemKritisCount); ?> habis, <?php echo e($itemMenipisCount); ?> menipis</p>
            </div>
            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 shadow-sm">
                <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengeluaran BHP Bulan Ini</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($pengeluaranBhpBulanIni, 0, ',', '.')); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e(now()->translatedFormat('F Y')); ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="<?php echo e(route('admin.pengeluaran.bhp')); ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
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
                <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                Cetak Laporan BHP (PDF)
            </a>
            <a href="<?php echo e(route('admin.pengajuan_belanja.create')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0zm5-9V7m-3 3h6" /></svg>
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
                                    <span class="inline-flex items-center gap-1"><svg class="w-2.5 h-2.5 text-blue-500 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>+<?php echo e($item->stock_subunits); ?> <?php echo e($item->unit_of_measurement); ?> (kemasan terbuka)</span>
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
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pengeluaran\bhp.blade.php ENDPATH**/ ?>