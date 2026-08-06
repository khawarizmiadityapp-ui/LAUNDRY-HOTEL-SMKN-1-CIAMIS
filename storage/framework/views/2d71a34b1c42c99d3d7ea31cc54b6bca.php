<?php $__env->startSection('title', 'Washing'); ?>
<?php $__env->startSection('content'); ?>

<div class="p-6 max-w-5xl mx-auto animate-fade-in">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Operasi Pencucian</h1>
        <p class="text-slate-500 mt-1">Selesaikan tugas pencucian yang berada di antrean.</p>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
            <h2 class="text-lg font-bold text-slate-800">Antrean Cucian</h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                <?php echo e(count($transactions)); ?> Menunggu
            </span>
        </div>

        <div class="p-6" x-data="{ 
            selectedIds: [], 
            allSelected: false,
            transactionIds: <?php echo e(json_encode($transactions->pluck('id'))); ?>,
            toggleAll() {
                if (this.allSelected) {
                    this.selectedIds = [...this.transactionIds];
                } else {
                    this.selectedIds = [];
                }
            }
        }">
            <?php if(count($transactions) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-500 font-semibold">
                                <th class="px-5 py-3 rounded-tl-xl w-14 text-center">
                                    <input type="checkbox" x-model="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="px-5 py-3 w-32">Invoice / Masuk</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3 w-48">Detail Layanan</th>
                                <th class="px-5 py-3 w-72">Form Petugas & Bahan</th>
                                <th class="px-5 py-3 rounded-tr-xl w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group cursor-pointer" :class="{ 'bg-blue-50/50': selectedIds.includes(<?php echo e($trx->id); ?>) }" @click="const cb = $el.querySelector('input[type=checkbox]'); if(cb && $event.target.tagName !== 'INPUT' && $event.target.tagName !== 'BUTTON' && $event.target.tagName !== 'SELECT') { cb.click(); }">
                                <td class="px-5 py-4 align-top text-center" @click.stop>
                                    <input type="checkbox" value="<?php echo e($trx->id); ?>" x-model="selectedIds" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer mt-1">
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-lg mb-1.5">
                                        #<?php echo e($trx->transaksi_code); ?>

                                    </span>
                                    <div class="text-xs font-bold text-slate-500"><?php echo e($trx->created_at->diffForHumans()); ?></div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="font-bold text-sm text-slate-800"><?php echo e($trx->customer_name); ?></div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <ul class="space-y-1.5">
                                        <?php $__currentLoopData = $trx->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="text-xs flex flex-col">
                                                <span class="text-slate-600 font-medium"><?php echo e($detail->layanan->nama ?? 'Layanan'); ?></span>
                                                <span class="font-bold text-slate-800 text-[11px]">(<?php echo e($detail->qty); ?> <?php echo e($detail->layanan->satuan ?? 'kg'); ?>)</span>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div x-data="{ 
                                        materials: [],
                                        availableMaterials: <?php echo e(json_encode($inventories->map(fn($i) => ['id' => $i->id, 'name' => $i->name, 'category' => $i->category, 'unit' => $i->unit_of_measurement]))); ?>,
                                        addMaterial() { this.materials.push({ id: '', quantity: 1 }); },
                                        removeMaterial(index) { this.materials.splice(index, 1); },
                                        confirmSubmission(e) {
                                            if (this.materials.length > 0) {
                                                let msg = 'Anda akan mencatat penggunaan bahan tambahan:\n';
                                                this.materials.forEach((m) => {
                                                    let item = this.availableMaterials.find(x => x.id == m.id);
                                                    if(item) {
                                                        let unit = item.unit || 'ml';
                                                        msg += `- ${item.name}: ${m.quantity} ${unit}\n`;
                                                    }
                                                });
                                                msg += '\nApakah jumlah bahan yang dipakai ini sudah sesuai?';
                                                if (!confirm(msg)) {
                                                    return;
                                                }
                                            }
                                            e.target.submit();
                                        }
                                    }">
                                        <form id="form-<?php echo e($trx->id); ?>" action="<?php echo e(route('petugas_piket.tasks.complete', $trx->id)); ?>" method="POST" @submit.prevent="confirmSubmission($event)">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="stage" value="washing">
                                        
                                        <div class="mb-3" x-data="petugasSearchComponent(<?php echo e(json_encode($petugasList->map(fn($p) => ['nama' => $p->nama, 'id_petugas' => $p->id_petugas]))); ?>)" @click.outside="showDropdown = false">
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-2.5 flex items-center pointer-events-none">
                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                    </svg>
                                                </span>
                                                <input type="text" name="petugas_name" x-model="search" @input="filterPetugas()" @focus="showDropdown = true" required placeholder="Petugas Piket..."
                                                       class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors bg-white">
                                                
                                                
                                                <div x-show="showDropdown && filteredList.length > 0" x-cloak
                                                     class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
                                                    <template x-for="p in filteredList" :key="p.id_petugas">
                                                        <button type="button" @click="select(p)"
                                                                class="w-full flex items-center gap-2 px-3 py-2 hover:bg-slate-50 transition text-left">
                                                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold" x-text="p.nama.charAt(0).toUpperCase()"></div>
                                                            <div class="min-w-0">
                                                                <p class="text-xs font-semibold text-slate-800 truncate" x-text="p.nama"></p>
                                                            </div>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                            <div x-show="isInvalid" x-cloak class="mt-1 text-[10px] text-rose-500 flex items-center gap-1">
                                                <span>Tidak terdaftar</span>
                                            </div>
                                        </div>

                                        
                                        <div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Penggunaan Bahan</span>
                                                <button type="button" @click="addMaterial()" class="text-[10px] text-blue-600 font-bold hover:text-blue-700 flex items-center gap-1 bg-blue-50 px-2 py-1 rounded-md transition-colors">
                                                    + Tambah
                                                </button>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <template x-for="(material, index) in materials" :key="index">
                                                    <div class="flex gap-1.5 items-start bg-white p-1.5 rounded-lg border border-slate-100 shadow-sm">
                                                        <select x-model="material.id" :name="`materials[${index}][id]`" required
                                                            class="w-full px-2 py-1.5 border border-slate-200 rounded-md text-[11px] text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50">
                                                            <option value="" disabled>Pilih...</option>
                                                            <template x-for="item in availableMaterials" :key="item.id">
                                                                <option :value="item.id" x-text="item.name"></option>
                                                            </template>
                                                        </select>
                                                        <div class="w-16 shrink-0 relative">
                                                            <input type="number" x-model="material.quantity" :name="`materials[${index}][quantity]`" required min="0.1" step="0.1" placeholder="Qty"
                                                                class="w-full px-2 py-1.5 border border-slate-200 rounded-md text-[11px] text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 text-center">
                                                        </div>
                                                        <button type="button" @click="removeMaterial(index)" class="p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors shrink-0">
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <button type="submit" form="form-<?php echo e($trx->id); ?>"
                                            class="w-full flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold py-2 px-3 rounded-lg transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        Selesai
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak ada antrean cucian</h3>
                    <p class="text-slate-500 text-sm">Semua pakaian telah selesai dicuci. Kerja bagus!</p>
                </div>
            <?php endif; ?>

            
            <div x-show="selectedIds.length > 0" x-transition.opacity
                 class="fixed bottom-6 inset-x-0 mx-auto w-[90%] max-w-3xl bg-slate-900 text-white rounded-2xl shadow-2xl p-4 flex flex-wrap md:flex-nowrap items-center justify-between gap-4 z-50 border border-slate-700/50">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="bg-blue-500/20 text-blue-300 w-10 h-10 rounded-full flex items-center justify-center font-bold text-base shrink-0">
                        <span x-text="selectedIds.length"></span>
                    </div>
                    <div>
                        <p class="font-bold text-sm">Tugas Dipilih</p>
                        <p class="text-xs text-slate-400">Pilih petugas (Bahan opsional diabaikan)</p>
                    </div>
                </div>
                
                <form action="<?php echo e(route('petugas_piket.tasks.completeBulk')); ?>" method="POST" class="flex items-center gap-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="stage" value="washing">
                    <template x-for="id in selectedIds">
                        <input type="hidden" name="transaction_ids[]" :value="id">
                    </template>
                    
                    <div x-data="petugasSearchComponent(<?php echo e(json_encode($petugasList->map(fn($p) => ['nama' => $p->nama, 'id_petugas' => $p->id_petugas]))); ?>)" @click.outside="showDropdown = false" class="relative min-w-[200px]">
                        <input type="text" name="petugas_name" x-model="search" @input="filterPetugas()" @focus="showDropdown = true" required placeholder="Nama petugas..."
                               class="w-full pl-3 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-xl text-sm text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        
                        <div x-show="showDropdown && filteredList.length > 0" x-cloak
                             class="absolute bottom-full mb-1 left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
                            <template x-for="p in filteredList" :key="p.id_petugas">
                                <button type="button" @click="select(p)"
                                        class="w-full flex items-center gap-2 px-3 py-2 hover:bg-slate-50 transition text-left text-slate-800">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold" x-text="p.nama.charAt(0).toUpperCase()"></div>
                                    <span class="text-xs font-semibold" x-text="p.nama"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold py-2 px-4 rounded-xl transition-colors shrink-0">
                        Selesaikan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.petugas_piket', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Adobe\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/petugas_piket/washing/index.blade.php ENDPATH**/ ?>