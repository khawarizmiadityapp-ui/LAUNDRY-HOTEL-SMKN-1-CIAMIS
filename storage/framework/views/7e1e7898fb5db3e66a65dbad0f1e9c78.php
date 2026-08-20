<?php $__env->startSection('title', 'Formulir & Pengajuan Belanja'); ?>
<?php $__env->startSection('page-title', 'Pengajuan Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="pengajuanApp()" class="w-full">

    
    <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
        <a href="<?php echo e(route('admin.pengeluaran.index')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition">
            <i class="fas fa-receipt mr-1.5 text-slate-400"></i> Pengeluaran Riil
        </a>
        <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>"
           class="px-4 py-2.5 text-sm font-bold border-b-2 border-blue-600 text-blue-600 bg-blue-50/50 rounded-t-lg transition flex items-center gap-2">
            <i class="fas fa-file-invoice-dollar text-blue-600"></i> Pengajuan Belanja
            <?php if($menungguApproval > 0): ?>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-500 text-white rounded-full animate-pulse"><?php echo e($menungguApproval); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('admin.pengeluaran.bhp')); ?>"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition">
            <i class="fas fa-boxes mr-1.5 text-slate-400"></i> Laporan Barang Habis Pakai (BHP)
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
                <i class="fas fa-clipboard-list text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Menunggu Approval</p>
                <p class="text-2xl font-bold text-amber-600 mt-1"><?php echo e($menungguApproval); ?></p>
                <p class="text-[11px] text-amber-500/80 mt-0.5">Perlu ditinjau admin</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Disetujui</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($disetujui); ?></p>
                <p class="text-[11px] text-blue-400 mt-0.5">Siap direalisasikan</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Estimasi Bulan Ini</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($totalEstimasiBulanIni, 0, ',', '.')); ?></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Periode <?php echo e(now()->translatedFormat('F Y')); ?></p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                <i class="fas fa-coins text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="<?php echo e(route('admin.pengajuan_belanja.index')); ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
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
            <i class="fas fa-plus"></i>
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
                                <i class="fas fa-user text-[9px] text-slate-400"></i> <?php echo e($item->user->name ?? 'Petugas'); ?>

                            </div>
                        </td>

                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900"><?php echo e($item->nama_pengajuan); ?></div>
                            <div class="text-xs text-slate-500 line-clamp-1 mt-0.5"><?php echo e($item->alasan); ?></div>
                            <?php if($item->lampiran): ?>
                                <a href="<?php echo e(asset('storage/' . $item->lampiran)); ?>" target="_blank"
                                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 hover:underline mt-1">
                                    <i class="fas fa-paperclip text-[10px]"></i> Lihat Lampiran
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
                                <div class="text-[10px] text-emerald-600 font-mono font-medium mt-1">
                                    <i class="fas fa-link text-[9px]"></i> <?php echo e($item->pengeluaran->id_transaksi); ?>

                                </div>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                
                                <button @click="openDetail(<?php echo e($item->id); ?>)"
                                        class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition"
                                        title="Detail Pengajuan">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>

                                
                                <?php if($item->status === 'diajukan' && auth()->user()->role === 'admin'): ?>
                                    <button @click="openApproveModal(<?php echo e($item->id); ?>, '<?php echo e($item->kode_pengajuan); ?>', '<?php echo e($item->nama_pengajuan); ?>')"
                                            class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition"
                                            title="Setujui Pengajuan">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                    <button @click="openRejectModal(<?php echo e($item->id); ?>, '<?php echo e($item->kode_pengajuan); ?>', '<?php echo e($item->nama_pengajuan); ?>')"
                                            class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-lg transition"
                                            title="Tolak Pengajuan">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                <?php endif; ?>

                                
                                <?php if($item->status === 'disetujui'): ?>
                                    <button @click="openConvertModal(<?php echo e($item->id); ?>, '<?php echo e($item->kode_pengajuan); ?>', '<?php echo e($item->nama_pengajuan); ?>', <?php echo e($item->estimasi_biaya); ?>)"
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5">
                                        <i class="fas fa-wallet"></i> Realisasikan
                                    </button>
                                <?php endif; ?>

                                
                                <?php if(auth()->user()->role === 'admin' || $item->status === 'diajukan'): ?>
                                    <form action="<?php echo e(route('admin.pengajuan_belanja.destroy', $item->id)); ?>" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan belanja ini?')"
                                          class="inline-block">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <i class="fas fa-clipboard-list text-3xl mb-3 text-slate-300 block"></i>
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
                    <i class="fas fa-file-invoice text-blue-400"></i>
                    <h3 class="text-sm font-bold tracking-wide uppercase">Rincian Pengajuan Belanja</h3>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times"></i>
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
                            <i class="fas fa-file-alt text-blue-600"></i>
                            <span class="text-xs font-semibold text-blue-900">Lampiran Dokumen / Bon</span>
                        </div>
                        <a :href="detailData?.lampiran_url" target="_blank"
                           class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <i class="fas fa-external-link-alt mr-1 text-[10px]"></i> Buka File
                        </a>
                    </div>
                </template>

                <template x-if="detailData?.catatan_approval">
                    <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl">
                        <span class="text-[11px] font-bold text-amber-800 uppercase flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> Catatan Penyetuju (<span x-text="detailData?.approver"></span>)
                        </span>
                        <p class="text-xs text-amber-900 mt-1" x-text="detailData?.catatan_approval"></p>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
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
                        <i class="fas fa-check-circle text-2xl"></i>
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
                        <i class="fas fa-times-circle text-2xl"></i>
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
                            <i class="fas fa-cash-register text-xl"></i>
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
                        <i class="fas fa-check"></i> Simpan ke Pengeluaran
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pengajuan_belanja/index.blade.php ENDPATH**/ ?>