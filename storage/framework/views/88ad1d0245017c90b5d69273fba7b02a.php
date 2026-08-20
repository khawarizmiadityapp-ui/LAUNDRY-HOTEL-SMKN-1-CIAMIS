<?php $__env->startSection('content'); ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Transaksi</h1>
        <p class="text-slate-500">Kelola pesanan masuk dan status pengerjaan.</p>
    </div>
</div>

<!-- Search & Filters -->
<div x-data="transaksiApp()" class="w-full">
<div class="bg-white p-4 rounded-xl border shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
    <form action="<?php echo e(url()->current()); ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                   placeholder="Cari transaksi, pelanggan..."
                   class="w-full pl-9 pr-4 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-10">
        </div>

        <select name="status" onchange="this.form.submit()" 
                class="text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[140px] cursor-pointer">
            <option value="">Semua Status</option>
            <option value="diterima" <?php echo e(request('status') == 'diterima' ? 'selected' : ''); ?>>Diterima</option>
            <option value="disortir" <?php echo e(request('status') == 'disortir' ? 'selected' : ''); ?>>Disortir</option>
            <option value="dicuci" <?php echo e(request('status') == 'dicuci' ? 'selected' : ''); ?>>Dicuci</option>
            <option value="dikeringkan" <?php echo e(request('status') == 'dikeringkan' ? 'selected' : ''); ?>>Dikeringkan</option>
            <option value="disetrika" <?php echo e(request('status') == 'disetrika' ? 'selected' : ''); ?>>Disetrika</option>
            <option value="dipacking" <?php echo e(request('status') == 'dipacking' ? 'selected' : ''); ?>>Dipacking</option>
            <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
            <option value="diambil" <?php echo e(request('status') == 'diambil' ? 'selected' : ''); ?>>Diambil</option>
        </select>

        <select name="payment_status" onchange="this.form.submit()" 
                class="text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 h-10 min-w-[150px] cursor-pointer">
            <option value="">Semua Pembayaran</option>
            <option value="lunas" <?php echo e(request('payment_status') == 'lunas' ? 'selected' : ''); ?>>Lunas</option>
            <option value="belum_bayar" <?php echo e(request('payment_status') == 'belum_bayar' ? 'selected' : ''); ?>>Belum Lunas</option>
        </select>

        <?php if(request('search') || request('status') || request('payment_status')): ?>
            <a href="<?php echo e(url()->current()); ?>" class="text-xs font-semibold text-red-500 hover:text-red-600 transition">Reset Filter</a>
        <?php endif; ?>
    </form>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow-sm border">
    <div class="overflow-x-auto min-h-[300px]">
        <table class="w-full text-sm text-slate-600 min-w-[950px] table-auto">
        <thead class="bg-slate-50 text-slate-700">
            <tr>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[20%]">Pelanggan</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[25%]">Layanan</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[10%]">Berat</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[12%]">Status</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[13%]">Pembayaran</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[10%]">Metode</th>
                <th class="px-6 py-4 text-left whitespace-nowrap font-semibold w-[10%]">Total</th>
                <th class="px-6 py-4 text-right whitespace-nowrap font-semibold w-[5%]">Aksi</th>
            </tr>
        </thead>
    
        <tbody class="divide-y divide-slate-100">
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-slate-50/50 transition">
                
                <!-- Pelanggan -->
                <td class="px-6 py-4 align-middle">
                    <div class="font-semibold text-slate-800 whitespace-nowrap">
                        <?php echo e($trx->customer_name); ?>

                    </div>
                    <div class="text-xs text-slate-400 whitespace-nowrap mt-0.5">
                        <?php echo e($trx->created_at->format('d M Y')); ?>

                    </div>
                </td>
    
                <!-- Layanan -->
                <td class="px-6 py-4 align-middle capitalize">
                    <?php if(isset($trx->details) && $trx->details->count() > 0): ?>
                        <ul class="text-xs list-disc pl-4 text-slate-600 space-y-1">
                        <?php $__currentLoopData = $trx->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="whitespace-nowrap"><?php echo e($detail->layanan->nama ?? 'Layanan'); ?> (<?php echo e($detail->qty); ?>x)</li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <span class="whitespace-nowrap"><?php echo e($trx->service_type); ?></span>
                    <?php endif; ?>
                </td>
    
                <!-- Berat -->
                <td class="px-6 py-4 align-middle whitespace-nowrap text-slate-700 font-medium">
                    <?php echo e(number_format($trx->weight, 2, '.', ',')); ?> kg
                </td>
    
                <!-- Status -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700">
                        <?php echo e($trx->status); ?>

                    </span>
                </td>
    
                <!-- Pembayaran -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg 
                        <?php echo e($trx->payment_status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'); ?>">
                        <?php echo e($trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Lunas'); ?>

                    </span>
                </td>
     
                <!-- Metode -->
                <td class="px-6 py-4 align-middle uppercase font-bold text-xs text-slate-700 whitespace-nowrap">
                    <?php echo e($trx->payment_method ?? 'Cash'); ?>

                </td>
    
                <!-- Total -->
                <td class="px-6 py-4 align-middle whitespace-nowrap">
                    <div class="font-semibold text-slate-800">
                        Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?>

                    </div>
                    <?php if($trx->dibayar > 0): ?>
                    <div class="text-[11px] text-slate-500 mt-1 leading-tight">
                        <span class="text-slate-400">Tunai:</span> Rp <?php echo e(number_format($trx->dibayar, 0, ',', '.')); ?><br>
                        <span class="text-emerald-500 font-medium">Kembali: Rp <?php echo e(number_format($trx->kembalian, 0, ',', '.')); ?></span>
                    </div>
                    <?php endif; ?>
                </td>
    
                <!-- Aksi -->
                <td class="px-6 py-4 align-middle text-right whitespace-nowrap">
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown('dropdown-<?php echo e($trx->id); ?>')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                                       hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                            </svg>
                        </button>
                        
                        <div id="dropdown-<?php echo e($trx->id); ?>" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                            <button type="button" @click="openNota('<?php echo e(route('pos.nota', $trx->id)); ?>'); toggleDropdown('dropdown-<?php echo e($trx->id); ?>')"
                               class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Cek Nota
                            </button>
 
                            <?php if(auth()->user()->role === 'admin'): ?>
                            <div class="h-px bg-slate-50 my-1"></div>
 
                            <form action="<?php echo e(route('admin.transactions.destroy', $trx->id)); ?>" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>
    
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-400">
                    Belum ada data
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($transactions->hasPages()): ?>
    <div class="bg-white rounded-xl shadow-sm border mt-4 p-4">
        <?php echo e($transactions->onEachSide(1)->links('vendor.pagination.custom')); ?>

    </div>
    <?php endif; ?>
</div>

    
    <div x-show="showNotaModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center modal-overlay bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="closeNotaModal()" class="bg-white shadow-2xl w-full max-w-[400px] mx-4 animate-fade-up overflow-hidden rounded-xl flex flex-col h-[85vh]">
            
            <div class="px-5 py-4 flex items-center justify-between bg-[#0b172a] text-white shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="font-bold text-xs tracking-widest uppercase">DETAIL TRANSAKSI</h3>
                </div>
                <button @click="closeNotaModal()" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            
            <div class="flex-1 overflow-hidden relative bg-slate-50">
                <template x-if="notaUrl">
                    <iframe x-ref="notaIframe" :src="notaUrl" class="w-full h-full border-0 absolute inset-0"></iframe>
                </template>
            </div>
            
            
            <div class="px-5 py-4 border-t border-slate-100 flex gap-3 justify-center bg-white shrink-0">
                <button @click="kirimWa()"
                        class="px-6 py-2.5 bg-[#25D366] text-white text-xs font-bold rounded-full hover:bg-[#128C7E] transition shadow-md flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    KIRIM WA
                </button>
                <button @click="printNota()"
                        class="px-6 py-2.5 bg-black text-white text-xs font-bold rounded-full hover:bg-slate-800 transition shadow-md flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    CETAK STRUK
                </button>
                <button @click="closeNotaModal()"
                        class="px-8 py-2.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full hover:bg-slate-200 transition active:scale-95">
                    TUTUP
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    window.toggleDropdown = window.toggleDropdown || function(id) {
        const dropdown = document.getElementById(id);
        if (dropdown.classList.contains('hidden')) {
            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    };

    document.addEventListener('click', function(event) {
        const toggleBtn = event.target.closest('button');
        let isDropdownToggle = false;
        if (toggleBtn) {
            const attrs = ['onclick', '@click', 'x-on:click'];
            isDropdownToggle = attrs.some(attr => {
                const val = toggleBtn.getAttribute(attr);
                return val && val.includes('toggleDropdown');
            });
        }
        if (!isDropdownToggle) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (!el.contains(event.target)) {
                    el.classList.add('hidden');
                }
            });
        }
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('transaksiApp', () => ({
            showNotaModal: false,
            notaUrl: '',
            
            openNota(url) {
                this.notaUrl = url;
                this.showNotaModal = true;
            },
            
            closeNotaModal() {
                this.showNotaModal = false;
                setTimeout(() => {
                    this.notaUrl = '';
                }, 200);
            },
            
            printNota() {
                const iframe = this.$refs.notaIframe;
                if (iframe && iframe.contentWindow) {
                    iframe.contentWindow.print();
                }
            },
            
            kirimWa() {
                const iframe = this.$refs.notaIframe;
                if (iframe && iframe.contentDocument) {
                    const waLink = iframe.contentDocument.getElementById('waLink');
                    if (waLink) {
                        window.open(waLink.href, '_blank');
                    } else {
                        alert('Link WhatsApp tidak ditemukan.');
                    }
                }
            }
        }));
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas_piket', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/transaksi/index.blade.php ENDPATH**/ ?>