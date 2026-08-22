<?php $__env->startSection('title', 'Dashboard Petugas Piket'); ?>
<?php $__env->startSection('content'); ?>

<div class="p-6 max-w-7xl mx-auto space-y-8 animate-fade-in" x-data="piketCheckinManager()">
    
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/3"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100 uppercase tracking-wider">
                        Jadwal Piket Siswa
                    </span>
                    <span class="text-xs text-slate-400">
                        <?php echo e(\Carbon\Carbon::today()->translatedFormat('l, d F Y')); ?>

                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
                    <?php if($activePiket): ?>
                        Halo, <span class="text-blue-600"><?php echo e($activePiket->nama); ?></span>!
                    <?php else: ?>
                        Selamat Datang di Lab Laundry SMKN 1 Ciamis
                    <?php endif; ?>
                </h1>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl mt-1">
                    Silakan pilih nama dan bagian tugas Anda hari ini untuk memulai aktivitas pengerjaan laundry.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <?php if($activePiket && $activePiket->selected_station !== 'none'): ?>
                    <div class="px-4 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Stasiun Aktif: <?php echo e(ucfirst($activePiket->selected_station)); ?></span>
                    </div>
                <?php else: ?>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 text-xs font-semibold rounded-xl border border-blue-100">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Pilih Stasiun Tugas
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div><?php echo session('success'); ?></div>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-sm">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <div><?php echo session('error'); ?></div>
    </div>
    <?php endif; ?>

    
    <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-sm">1</span>
                    Pilih Stasiun Tugas Hari Ini
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pilih nama Anda dari jadwal hari ini, lalu klik stasiun kerja yang ingin Anda ambil.
                </p>
            </div>

            <?php if($jadwalHariIni->isEmpty()): ?>
            <div class="px-3.5 py-1.5 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                Jadwal hari ini belum di-import oleh Admin
            </div>
            <?php endif; ?>
        </div>

        <form action="<?php echo e(route('petugas_piket.checkin.station')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            
            <div class="max-w-md">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Siswa / Petugas Piket Hari Ini
                </label>
                <?php if($jadwalHariIni->isNotEmpty()): ?>
                    <select name="jadwal_id" x-model="selectedJadwalId" required
                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 focus:border-blue-500 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/10 shadow-sm transition">
                        <option value="" disabled>-- Pilih Nama Anda --</option>
                        <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($j->id); ?>" <?php echo e(($activePiket && $activePiket->id === $j->id) ? 'selected' : ''); ?>>
                                <?php echo e($j->nama); ?> (<?php echo e($j->shift); ?>) <?php echo e($j->selected_station !== 'none' ? '— [' . ucfirst($j->selected_station) . ']' : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php else: ?>
                    <div class="p-3 bg-slate-100 rounded-xl text-xs text-slate-500 font-medium">
                        Tidak ada siswa yang terjadwal piket di database untuk hari ini. Hubungi admin untuk import jadwal Excel.
                    </div>
                <?php endif; ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                    Pilih Stasiun Tugas Kerja
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="washing" x-model="selectedStation" class="sr-only">
                        <div :class="selectedStation === 'washing' ? 'border-blue-600 bg-blue-50/70 ring-2 ring-blue-600 ring-offset-2' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50'"
                             class="p-5 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">🌊</span>
                                <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                      :class="selectedStation === 'washing' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300'">
                                    <template x-if="selectedStation === 'washing'">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-base">Washing / Pencucian</h3>
                                <p class="text-xs text-slate-500 mt-1">Menimbang cucian, mencuci pakaian dengan deterjen, dan operasional mesin cuci.</p>
                            </div>
                        </div>
                    </label>

                    
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="setrika" x-model="selectedStation" class="sr-only">
                        <div :class="selectedStation === 'setrika' ? 'border-amber-600 bg-amber-50/70 ring-2 ring-amber-600 ring-offset-2' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50'"
                             class="p-5 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">♨️</span>
                                <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                      :class="selectedStation === 'setrika' ? 'border-amber-600 bg-amber-600 text-white' : 'border-slate-300'">
                                    <template x-if="selectedStation === 'setrika'">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-base">Setrika / Ironing</h3>
                                <p class="text-xs text-slate-500 mt-1">Menyetrika dan merapikan pakaian pelanggan menggunakan setrika uap.</p>
                            </div>
                        </div>
                    </label>

                    
                    <label class="cursor-pointer">
                        <input type="radio" name="station" value="packing" x-model="selectedStation" class="sr-only">
                        <div :class="selectedStation === 'packing' ? 'border-purple-600 bg-purple-50/70 ring-2 ring-purple-600 ring-offset-2' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50'"
                             class="p-5 rounded-2xl border-2 transition-all duration-200 h-full flex flex-col justify-between shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-3xl">📦</span>
                                <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                      :class="selectedStation === 'packing' ? 'border-purple-600 bg-purple-600 text-white' : 'border-slate-300'">
                                    <template x-if="selectedStation === 'packing'">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-base">Packing & Quality</h3>
                                <p class="text-xs text-slate-500 mt-1">Membungkus rapi pakaian, menempelkan nota/label, dan siap diambil pelanggan.</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <div class="text-xs text-slate-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-2.18-8.587A18.01 18.01 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018a17.95 17.95 0 006.126 13.567A18.058 18.058 0 0012 21c2.196 0 4.29-.395 6.126-1.124A17.95 17.95 0 0021.75 12.76V6.741c0-1.602-1.123-2.995-2.707-3.228A17.93 17.93 0 0012 3.5c-.61 0-1.21.03-1.8.087z" />
                    </svg>
                    <span>Nama Anda akan otomatis terisi saat mengerjakan tugas di stasiun tersebut.</span>
                </div>
                <button type="submit" :disabled="!selectedJadwalId || !selectedStation"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>Mulai Bertugas</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Rekan Piket Hari Ini</h3>
                <p class="text-xs text-slate-400">Pembagian stasiun kerja siswa pada shift hari ini</p>
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs">
                Total: <?php echo e($jadwalHariIni->count()); ?> Siswa
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-blue-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🌊</span> Washing (Cuci)
                    </span>
                    <span class="px-2 py-0.5 bg-blue-200 text-blue-800 rounded-lg text-xs font-black">
                        <?php echo e($jadwalHariIni->where('selected_station', 'washing')->count()); ?>

                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni->where('selected_station', 'washing'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-blue-100/60 font-semibold">
                            <span><?php echo e($w->nama); ?></span>
                            <span class="text-[10px] text-slate-400 font-mono"><?php echo e($w->shift); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    <?php endif; ?>
                </ul>
            </div>

            
            <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>♨️</span> Setrika (Ironing)
                    </span>
                    <span class="px-2 py-0.5 bg-amber-200 text-amber-800 rounded-lg text-xs font-black">
                        <?php echo e($jadwalHariIni->where('selected_station', 'setrika')->count()); ?>

                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni->where('selected_station', 'setrika'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-amber-100/60 font-semibold">
                            <span><?php echo e($s->nama); ?></span>
                            <span class="text-[10px] text-slate-400 font-mono"><?php echo e($s->shift); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    <?php endif; ?>
                </ul>
            </div>

            
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-purple-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📦</span> Packing
                    </span>
                    <span class="px-2 py-0.5 bg-purple-200 text-purple-800 rounded-lg text-xs font-black">
                        <?php echo e($jadwalHariIni->where('selected_station', 'packing')->count()); ?>

                    </span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni->where('selected_station', 'packing'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="flex items-center justify-between p-2 bg-white rounded-xl shadow-2xl shadow-slate-100 border border-purple-100/60 font-semibold">
                            <span><?php echo e($p->nama); ?></span>
                            <span class="text-[10px] text-slate-400 font-mono"><?php echo e($p->shift); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="text-slate-400 italic text-[11px] py-1">Belum ada yang memilih</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function piketCheckinManager() {
        return {
            selectedJadwalId: '<?php echo e($activePiket ? $activePiket->id : ""); ?>',
            selectedStation: '<?php echo e(($activePiket && $activePiket->selected_station !== "none") ? $activePiket->selected_station : "washing"); ?>',
        };
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.petugas_piket', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/petugas_piket/dashboard.blade.php ENDPATH**/ ?>