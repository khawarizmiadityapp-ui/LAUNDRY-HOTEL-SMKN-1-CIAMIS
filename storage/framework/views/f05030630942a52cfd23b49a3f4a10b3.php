<?php $__env->startSection('title', 'Formulir & Pengajuan Belanja'); ?>
<?php $__env->startSection('page-title', 'Pengajuan Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="pengajuanApp()" class="w-full">

    
    <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
        <a href="<?php echo e(route('admin.pengeluaran.index')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5 inline-block text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg> Pengeluaran Riil
        </a>
        <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>"
           class="px-4 py-2.5 text-sm font-bold border-b-2 border-blue-600 text-blue-600 bg-blue-50/50 rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Pengajuan Belanja
            <?php if($menungguApproval > 0): ?>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-500 text-white rounded-full animate-pulse"><?php echo e($menungguApproval); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('admin.pengeluaran.bhp')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5 inline-block text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg> Laporan Barang Habis Pakai (BHP)
        </a>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pengajuan</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($totalPengajuan); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Semua riwayat pengajuan</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Menunggu Approval</p>
                <p class="text-2xl font-bold text-amber-600 mt-1"><?php echo e($menungguApproval); ?></p>
                <p class="text-[11px] text-amber-500/80 mt-0.5">Perlu ditinjau admin</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Disetujui</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($disetujui); ?></p>
                <p class="text-[11px] text-blue-400 mt-0.5">Siap direalisasikan</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Estimasi Bulan Ini</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($totalEstimasiBulanIni, 0, ',', '.')); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Periode <?php echo e(now()->translatedFormat('F Y')); ?></p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="<?php echo e(route('admin.pengajuan_belanja.index')); ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Cari kode, nama pengajuan..."
                       class="w-full pl-9 pr-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-10">
            </div>

            <select name="status" onchange="this.form.submit()"
                    class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[140px] cursor-pointer">
                <option value="">Semua Status</option>
                <option value="diajukan" <?php echo e(request('status') == 'diajukan' ? 'selected' : ''); ?>>Menunggu Approval</option>
                <option value="disetujui" <?php echo e(request('status') == 'disetujui' ? 'selected' : ''); ?>>Disetujui</option>
                <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Direalisasikan (Selesai)</option>
                <option value="ditolak" <?php echo e(request('status') == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
            </select>

            <select name="urgensi" onchange="this.form.submit()"
                    class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[130px] cursor-pointer">
                <option value="">Semua Urgensi</option>
                <option value="biasa" <?php echo e(request('urgensi') == 'biasa' ? 'selected' : ''); ?>>Biasa</option>
                <option value="mendesak" <?php echo e(request('urgensi') == 'mendesak' ? 'selected' : ''); ?>>Mendesak</option>
                <option value="sangat_mendesak" <?php echo e(request('urgensi') == 'sangat_mendesak' ? 'selected' : ''); ?>>Sangat Mendesak</option>
            </select>

            <?php if(request('search') || request('status') || request('urgensi')): ?>
                <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>" class="text-xs font-semibold text-rose-500 hover:text-rose-600 transition">Reset</a>
            <?php endif; ?>
        </form>

        <a href="<?php echo e(route('admin.pengajuan_belanja.create')); ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Buat Formulir Pengajuan Belanja
        </a>
    </div>

    
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Kode & Tanggal</th>
                        <th class="px-6 py-4">Kebutuhan / Pengajuan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Estimasi Biaya</th>
                        <th class="px-6 py-4">Urgensi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/70 transition">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-mono font-bold text-blue-600 text-xs"><?php echo e($item->kode_pengajuan); ?></div>
                            <div class="text-xs text-slate-400 mt-0.5"><?php echo e($item->tanggal_pengajuan->format('d M Y')); ?></div>
                            <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-slate-400 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> <?php echo e($item->user->name ?? 'Petugas'); ?>

                            </div>
                        </td>

                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900"><?php echo e($item->nama_pengajuan); ?></div>
                            <div class="text-xs text-slate-500 line-clamp-1 mt-0.5"><?php echo e($item->alasan); ?></div>
                            <?php if($item->lampiran): ?>
                                <a href="<?php echo e(asset('storage/' . $item->lampiran)); ?>" target="_blank"
                                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 hover:underline mt-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> Lihat Lampiran
                                </a>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-600">
                            <?php echo e($item->kategoriPengeluaran->nama ?? '-'); ?>

                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900">
                            Rp <?php echo e(number_format($item->estimasi_biaya, 0, ',', '.')); ?>

                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border <?php echo e($item->urgensi_badge['class']); ?>">
                                <?php echo e($item->urgensi_badge['label']); ?>

                            </span>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg border <?php echo e($item->status_badge['class']); ?>">
                                <?php echo e($item->status_badge['label']); ?>

                            </span>
                            <?php if($item->status === 'selesai' && $item->pengeluaran): ?>
                                <div class="text-[10px] text-emerald-600 font-mono font-medium mt-1 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg> <?php echo e($item->pengeluaran->id_transaksi); ?>

                                </div>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                
                                <button @click="openDetail(<?php echo e($item->id); ?>)"
                                        class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition"
                                        title="Detail Pengajuan">
                                    <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg> Detail
                                </button>

                                
                                <a href="<?php echo e(route('admin.pengajuan_belanja.export_pdf', $item->id)); ?>" target="_blank"
                                   class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-lg transition"
                                   title="Download PDF">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                </a>

                                
                                <?php if($item->status === 'diajukan' && auth()->user()->isAdmin()): ?>
                                    <button @click="openApproveModal(<?php echo e($item->id); ?>, '<?php echo e($item->kode_pengajuan); ?>', '<?php echo e($item->nama_pengajuan); ?>')"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition"
                                            title="Setujui Pengajuan">
                                        <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Setujui
                                    </button>
                                    <button @click="openRejectModal(<?php echo e($item->id); ?>, '<?php echo e($item->kode_pengajuan); ?>', '<?php echo e($item->nama_pengajuan); ?>')"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-lg transition"
                                            title="Tolak Pengajuan">
                                        <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg> Tolak
                                    </button>
                                <?php endif; ?>

                                
                                <?php if($item->status === 'disetujui'): ?>
                                    <button @click="openConvertModal(<?php echo e($item->id); ?>, '<?php echo e($item->kode_pengajuan); ?>', '<?php echo e($item->nama_pengajuan); ?>', <?php echo e($item->estimasi_biaya); ?>)"
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg> Realisasikan
                                    </button>
                                <?php endif; ?>

                                
                                <?php if(auth()->user()->isAdmin() || $item->status === 'diajukan'): ?>
                                    <form action="<?php echo e(route('admin.pengajuan_belanja.destroy', $item->id)); ?>" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan belanja ini?')"
                                          class="inline-block">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            Belum ada formulir pengajuan belanja ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($pengajuans->hasPages()): ?>
        <div class="px-6 py-4 border-t border-slate-100">
            <?php echo e($pengajuans->links('vendor.pagination.custom')); ?>

        </div>
        <?php endif; ?>
    </div>

    
    <div x-show="showDetailModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showDetailModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden border border-slate-100 animate-fade-up">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <h3 class="text-sm font-bold tracking-wide uppercase">Rincian Pengajuan Belanja</h3>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto" x-show="detailData">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs text-slate-400">Kode Pengajuan</span>
                        <p class="font-mono font-bold text-blue-600 text-base" x-text="detailData?.kode_pengajuan"></p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400">Tanggal Diajukan</span>
                        <p class="text-xs font-semibold text-slate-700" x-text="detailData?.tanggal_pengajuan"></p>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Kebutuhan / Barang</span>
                    <p class="text-base font-bold text-slate-900 mt-0.5" x-text="detailData?.nama_pengajuan"></p>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Estimasi Biaya</span>
                        <p class="text-base font-bold text-slate-900 mt-0.5" x-text="detailData?.estimasi_biaya_format"></p>
                    </div>
                    <div>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Kategori</span>
                        <p class="text-xs font-semibold text-slate-700 mt-1" x-text="detailData?.kategori"></p>
                    </div>
                    <div>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Pemohon</span>
                        <p class="text-xs font-semibold text-slate-700 mt-0.5" x-text="detailData?.pemohon"></p>
                    </div>
                    <div>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Tingkat Urgensi</span>
                        <p class="text-xs font-semibold text-slate-700 capitalize mt-0.5" x-text="detailData?.urgensi?.replace('_', ' ')"></p>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alasan & Rincian Pengajuan</span>
                    <div class="mt-1 p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs text-slate-700 whitespace-pre-line" x-text="detailData?.alasan"></div>
                </div>

                <template x-if="detailData?.lampiran_url">
                    <div class="p-3 bg-blue-50/60 border border-blue-100 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            <span class="text-xs font-semibold text-blue-900">Lampiran Dokumen / Bon</span>
                        </div>
                        <a :href="detailData?.lampiran_url" target="_blank"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <svg class="w-3 h-3 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg> Buka File
                        </a>
                    </div>
                </template>

                <template x-if="detailData?.catatan_approval">
                    <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl">
                        <span class="text-[11px] font-bold text-amber-800 uppercase flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Catatan Penyetuju (<span x-text="detailData?.approver"></span>)
                        </span>
                        <p class="text-xs text-amber-900 mt-1" x-text="detailData?.catatan_approval"></p>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <template x-if="detailData?.id">
                    <a :href="'/admin/pengajuan-belanja/' + detailData.id + '/export-pdf'" target="_blank"
                       class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Download PDF
                    </a>
                </template>
                <button type="button" @click="showDetailModal = false"
                        class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    
    <div x-show="showApproveModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div @click.outside="showApproveModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
            <form :action="approveUrl" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <input type="hidden" name="status" value="disetujui">
                <div class="p-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Setujui Pengajuan Belanja</h3>
                    <p class="text-xs text-slate-500 mt-1">Anda akan menyetujui pengajuan <span class="font-bold text-blue-600" x-text="modalKode"></span> (<span x-text="modalNama"></span>).</p>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Approval (Opsional)</label>
                        <textarea name="catatan_approval" rows="2" placeholder="Contoh: Disetujui, harap sertakan bon nota asli saat realisasi belanja."
                                  class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showApproveModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition">Konfirmasi Setujui</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="showRejectModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div @click.outside="showRejectModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
            <form :action="rejectUrl" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <input type="hidden" name="status" value="ditolak">
                <div class="p-6">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Tolak Pengajuan Belanja</h3>
                    <p class="text-xs text-slate-500 mt-1">Pengajuan <span class="font-bold text-slate-800" x-text="modalKode"></span> akan ditolak.</p>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                        <textarea name="catatan_approval" rows="3" required placeholder="Jelaskan alasan penolakan agar pemohon dapat mengetahuinya..."
                                  class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showRejectModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md transition">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="showConvertModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div @click.outside="showConvertModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
            <form :action="convertUrl" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Realisasi ke Pengeluaran Riil</h3>
                            <p class="text-xs text-slate-500" x-text="modalKode + ' - ' + modalNama"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Riil Belanja (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nominal_riil" :value="modalNominal" required min="0"
                               class="w-full text-sm font-bold text-slate-900 p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                        <p class="text-[11px] text-slate-400 mt-1">Sesuai total pada struk/bon belanja asli.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Realisasi <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="<?php echo e(date('Y-m-d')); ?>" required
                               class="w-full text-xs p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Bukti Bon / Nota Baru (Opsional)</label>
                        <input type="file" name="bon_file" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Tambahan</label>
                        <input type="text" name="keterangan" placeholder="Keterangan belanja..."
                               class="w-full text-xs p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showConvertModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Simpan ke Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('pengajuanApp', () => ({
        showDetailModal: false,
        detailData: null,
        showApproveModal: false,
        showRejectModal: false,
        showConvertModal: false,
        modalId: null,
        modalKode: '',
        modalNama: '',
        modalNominal: 0,
        approveUrl: '',
        rejectUrl: '',
        convertUrl: '',

        openDetail(id) {
            fetch(`/admin/pengajuan-belanja/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.detailData = data.data;
                        this.showDetailModal = true;
                    }
                })
                .catch(err => alert('Gagal memuat detail pengajuan.'));
        },

        openApproveModal(id, kode, nama) {
            this.modalId = id;
            this.modalKode = kode;
            this.modalNama = nama;
            this.approveUrl = `/admin/pengajuan-belanja/${id}/status`;
            this.showApproveModal = true;
        },

        openRejectModal(id, kode, nama) {
            this.modalId = id;
            this.modalKode = kode;
            this.modalNama = nama;
            this.rejectUrl = `/admin/pengajuan-belanja/${id}/status`;
            this.showRejectModal = true;
        },

        openConvertModal(id, kode, nama, nominal) {
            this.modalId = id;
            this.modalKode = kode;
            this.modalNama = nama;
            this.modalNominal = nominal;
            this.convertUrl = `/admin/pengajuan-belanja/${id}/convert`;
            this.showConvertModal = true;
        }
    }));
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pengajuan_belanja\index.blade.php ENDPATH**/ ?>