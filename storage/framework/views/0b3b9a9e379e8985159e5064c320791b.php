<?php $__env->startSection('title', 'Laporan Keuangan - Bening Laundry'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header + Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-500 mt-1">Analisis komprehensif arus kas dan performa bisnis laundry.</p>
        </div>
        <div class="flex flex-col gap-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                        <a href="<?php echo e(route('admin.laporan_keuangan.index', ['filter' => 'bulanan'])); ?>"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition <?php echo e(($filter ?? 'bulanan') == 'bulanan' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-800'); ?>">Bulanan</a>
                        
                        <a href="<?php echo e(route('admin.laporan_keuangan.index', ['filter' => 'tahunan'])); ?>"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition <?php echo e($filter == 'tahunan' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-800'); ?>">Tahunan</a>
                        
                        <button type="button" onclick="toggleCustomFilter(this)"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition <?php echo e($filter == 'custom' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-800'); ?>">Custom</button>
                    </div>
                    <a href="<?php echo e(route('admin.laporan_keuangan.bku_pdf', request()->query())); ?>" 
                       class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl shadow-md transition text-sm">
                        <i class="fas fa-book"></i> Cetak BKU (PDF)
                    </a>
                    <button type="button" onclick="openExportModal()" class="flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium px-4 py-2.5 rounded-xl shadow-md transition text-sm">
                        <i class="fas fa-file-export"></i> Export Data
                    </button>
                </div>
            </div>
            
            <form method="GET" action="<?php echo e(route('admin.laporan_keuangan.index')); ?>" id="customFilterDiv" class="<?php echo e($filter == 'custom' ? 'flex' : 'hidden'); ?> absolute right-0 top-full mt-2 z-50 flex-col gap-3 bg-white p-4 rounded-xl shadow-xl border border-gray-100 w-64">
                <input type="hidden" name="filter" value="custom">
                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="dari" value="<?php echo e(request('dari')); ?>" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="<?php echo e(request('sampai')); ?>" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition w-full mt-1">Terapkan Filter</button>
            </form>

            <?php if($errors->has('dari') || $errors->has('sampai')): ?>
                <div class="text-red-500 text-xs">
                    <?php $__errorArgs = ['dari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['sampai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistik Cards (5) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Pemasukan -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pemasukan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp <?php echo e(number_format($pemasukan, 0, ',', '.')); ?></p>

                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Pengeluaran -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pengeluaran</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp <?php echo e(number_format($pengeluaran, 0, ',', '.')); ?></p>

                </div>
                <div class="bg-red-100 p-3 rounded-xl">
                    <i class="fas fa-receipt text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Laba Bersih (Highlight) -->
        <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl shadow-lg p-6 relative overflow-hidden text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Laba Bersih</p>
                    <p class="text-3xl font-bold mt-1">Rp <?php echo e(number_format($laba, 0, ',', '.')); ?></p>

                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Piutang -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Piutang (Belum Bayar)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp <?php echo e(number_format($piutang ?? 0, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-orange-100 p-3 rounded-xl">
                    <i class="fas fa-hand-holding-dollar text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Utang -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Utang (Kewajiban)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp <?php echo e(number_format($utang ?? 0, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-file-invoice-dollar text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Target Pemasukan Bulanan & Tahunan (Modernized Layout) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden transition-all duration-300 hover:shadow-md">
        <!-- Top Accent Gradient Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500"></div>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shadow-xs shrink-0">
                    <i class="fas fa-bullseye text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-base font-bold text-gray-800 tracking-tight">Target Pemasukan Admin</h3>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Target Aktif
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Monitoring target finansial bulanan & tahunan secara realtime</p>
                </div>
            </div>

            <div class="flex items-center gap-3 self-start md:self-auto">
                <button onclick="document.getElementById('targetModal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 rounded-xl transition-all duration-200 shadow-2xs group">
                    <i class="fas fa-edit text-blue-500 group-hover:scale-110 transition-transform"></i>
                    <span>Edit Target</span>
                </button>

                <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border text-xs font-bold shadow-2xs <?php echo e($persenTargetBulanIni >= 100 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'); ?>">
                    <i class="fas <?php echo e($persenTargetBulanIni >= 100 ? 'fa-circle-check text-emerald-600' : 'fa-chart-line text-amber-600'); ?>"></i>
                    <span><?php echo e(number_format($persenTargetBulanIni, 2)); ?>% Target</span>
                </div>
            </div>
        </div>

        <!-- Target Cards Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-6">
            <!-- Target Tahunan -->
            <div class="bg-gray-50/70 border border-gray-100 rounded-xl p-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-white border border-gray-200/60 text-gray-600 flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fas fa-calendar-alt text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-gray-400">Target Tahunan</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5 truncate">Rp <?php echo e(number_format($annualTarget, 0, ',', '.')); ?></p>
                </div>
            </div>

            <!-- Target Bulanan -->
            <div class="bg-indigo-50/40 border border-indigo-100/60 rounded-xl p-3.5 flex items-center gap-3 hover:bg-indigo-50/60 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-white border border-indigo-100 text-indigo-600 flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fas fa-calendar-check text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-indigo-500">Target Bulanan</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5 truncate">Rp <?php echo e(number_format($limitPemasukanBulanan, 0, ',', '.')); ?></p>
                </div>
            </div>

            <!-- Target Dasar Harian -->
            <div class="bg-blue-50/40 border border-blue-100/60 rounded-xl p-3.5 flex items-center gap-3 hover:bg-blue-50/60 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-white border border-blue-100 text-blue-600 flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fas fa-clock text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-blue-500">
                        Target Harian (<?php echo e(\App\Models\DailyTarget::getTargetDaysInMonth()); ?> Hari)
                    </p>
                    <p class="text-sm font-bold text-blue-700 mt-0.5 truncate">Rp <?php echo e(number_format(\App\Models\DailyTarget::calculateBaseTarget(), 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Progress Bar & Realization Info -->
        <div class="bg-gray-50/60 border border-gray-100 rounded-xl p-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs font-semibold gap-1.5 mb-2.5">
                <div class="flex items-center gap-1.5">
                    <span class="text-gray-500">Realisasi Bulan Ini:</span>
                    <span class="text-gray-900 font-bold text-sm">Rp <?php echo e(number_format($realisasiBulanIni, 0, ',', '.')); ?></span>
                </div>
                <div class="text-gray-500 text-xs">
                    Target: <span class="font-semibold text-gray-700">Rp <?php echo e(number_format($limitPemasukanBulanan, 0, ',', '.')); ?></span>
                </div>
            </div>

            <!-- Sleek Bar -->
            <div class="w-full h-3.5 bg-gray-200/70 rounded-full overflow-hidden p-0.5 relative shadow-inner">
                <div class="h-full rounded-full transition-all duration-700 bg-gradient-to-r <?php echo e($persenTargetBulanIni >= 100 ? 'from-emerald-500 to-teal-400' : 'from-blue-600 via-indigo-600 to-blue-500'); ?> shadow-xs" 
                     style="width: <?php echo e(min(100, max(2, $persenTargetBulanIni))); ?>%"></div>
            </div>
        </div>
    </div>

    
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-md p-6 border border-blue-100">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                    Laporan Keuangan & Target Harian (Bulan <?php echo e(now()->translatedFormat('F Y')); ?>)
                </h3>
                <p class="text-sm text-gray-600 mt-1">Sistem pencatatan harian otomatis. Jika pemasukan minus/defisit, kekurangan target otomatis ditambahkan ke target hari berikutnya.</p>
            </div>
            <div class="bg-white rounded-xl px-4 py-3 border border-blue-200 shadow-sm flex items-center gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pencapaian Bulan Ini</p>
                    <p class="text-2xl font-bold text-blue-600 mt-0.5"><?php echo e($weeklyAchievementRate); ?>%</p>
                </div>
                <div class="text-right border-l border-gray-200 pl-4">
                    <p class="text-xs text-gray-500 font-semibold">Realisasi / Target</p>
                    <p class="text-xs font-bold text-gray-700 mt-1">Rp <?php echo e(number_format($weeklyActualSum, 0, ',', '.')); ?></p>
                    <p class="text-xs text-gray-500">dari Rp <?php echo e(number_format($weeklyTargetSum, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl p-5 mb-6 border-2 border-blue-200 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target Hari Ini</span>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md <?php echo e($todayTarget->is_workday ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600'); ?>">
                            <?php echo e($todayTarget->is_workday ? 'Hari Kerja Aktif' : 'Hari Libur / Weekend'); ?>

                        </span>
                    </div>
                    <p class="text-sm font-bold text-slate-800 mt-0.5"><?php echo e($todayTarget->date->translatedFormat('l, d F Y')); ?></p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <span class="text-[11px] text-slate-400 block uppercase font-semibold">Skema Operasional</span>
                        <span class="text-xs font-bold text-slate-700">
                            <?php if($workdaysMode === 'senin_jumat'): ?>
                                <i class="fas fa-calendar-check text-blue-600 mr-1"></i> Senin - Jumat (<?php echo e($activeWorkDaysCount); ?> Hari Kerja Aktif)
                            <?php elseif($workdaysMode === 'senin_sabtu'): ?>
                                <i class="fas fa-calendar-check text-indigo-600 mr-1"></i> Senin - Sabtu (<?php echo e($activeWorkDaysCount); ?> Hari Kerja Aktif)
                            <?php elseif($workdaysMode === 'custom'): ?>
                                <i class="fas fa-sliders-h text-amber-600 mr-1"></i> Custom (<?php echo e($activeWorkDaysCount); ?> Hari Kerja)
                            <?php else: ?>
                                <i class="fas fa-calendar text-emerald-600 mr-1"></i> Setiap Hari (<?php echo e($activeWorkDaysCount); ?> Hari Kalender)
                            <?php endif; ?>
                        </span>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm
                        <?php if(!$todayTarget->is_workday): ?>
                            bg-slate-100 text-slate-600 border border-slate-200
                        <?php elseif($todayTarget->is_achieved): ?>
                            bg-emerald-100 text-emerald-700 border border-emerald-200
                        <?php elseif($todayTarget->status_color === 'yellow'): ?>
                            bg-amber-100 text-amber-700 border border-amber-200
                        <?php else: ?>
                            bg-rose-100 text-rose-700 border border-rose-200
                        <?php endif; ?>">
                        <?php if(!$todayTarget->is_workday): ?>
                            <i class="fas fa-bed mr-1"></i> Non-Operasional
                        <?php elseif($todayTarget->is_achieved): ?>
                            <i class="fas fa-check-circle mr-1"></i> Target Tercapai
                        <?php else: ?>
                            <i class="fas fa-hourglass-half mr-1"></i> Belum Tercapai
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase">Target Dasar</p>
                    <p class="text-lg font-bold text-slate-800 mt-0.5">
                        <?php if($todayTarget->is_workday): ?>
                            Rp <?php echo e(number_format($todayTarget->base_target, 0, ',', '.')); ?>

                        <?php else: ?>
                            <span class="text-slate-400 font-normal text-sm">Rp 0 (Libur)</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase">+ Defisit Berjalan</p>
                    <p class="text-lg font-bold mt-0.5 <?php echo e($todayTarget->carry_forward > 0 ? 'text-rose-600' : 'text-slate-400'); ?>">
                        Rp <?php echo e(number_format($todayTarget->carry_forward, 0, ',', '.')); ?>

                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase">Target Final Hari Ini</p>
                    <p class="text-lg font-bold text-blue-600 mt-0.5">
                        <?php if($todayTarget->is_workday): ?>
                            Rp <?php echo e(number_format($todayTarget->adjusted_target, 0, ',', '.')); ?>

                        <?php else: ?>
                            <span class="text-slate-400 font-normal text-sm">Rp 0 (Libur)</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase">Realisasi Bersih</p>
                    <p class="text-lg font-bold mt-0.5 <?php echo e($todayTarget->net_income >= $todayTarget->adjusted_target && $todayTarget->is_workday ? 'text-emerald-600' : ($todayTarget->net_income > 0 ? 'text-blue-600' : 'text-slate-800')); ?>">
                        Rp <?php echo e(number_format($todayTarget->net_income, 0, ',', '.')); ?>

                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase">Selisih Capaian</p>
                    <p class="text-lg font-bold mt-0.5 <?php echo e($todayTarget->variance >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                        <?php echo e($todayTarget->variance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($todayTarget->variance, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>

            <?php if($todayTarget->is_workday): ?>
            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                <div class="flex justify-between text-xs font-semibold">
                    <span class="text-slate-500">Progress Capaian Hari Ini</span>
                    <span class="text-blue-600 font-bold"><?php echo e($todayTarget->achievement_percentage); ?>%</span>
                </div>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-500 <?php echo e($todayTarget->is_achieved ? 'bg-emerald-500' : 'bg-blue-600'); ?>" 
                         style="width: <?php echo e(min(100, $todayTarget->achievement_percentage)); ?>%"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-blue-600"></i> Rincian Target Harian (Bulan <?php echo e(now()->translatedFormat('F Y')); ?>)
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Target bulanan Rp <?php echo e(number_format($limitPemasukanBulanan, 0, ',', '.')); ?> dibagi <?php echo e($activeWorkDaysCount); ?> hari kerja aktif (Rp <?php echo e(number_format($baseDailyTarget, 0, ',', '.')); ?>/hari).
                    </p>
                </div>
                <span class="text-xs font-bold text-slate-600 bg-white px-3 py-1 rounded-xl border border-slate-200 shadow-2xs">
                    <?php echo e($activeWorkDaysCount); ?> Hari Kerja Aktif
                </span>
            </div>
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-100 border-b border-slate-200 sticky top-0 z-10 text-xs font-semibold text-slate-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Tanggal & Hari</th>
                            <th class="px-4 py-3 text-right">Target Dasar</th>
                            <th class="px-4 py-3 text-right">Defisit Kemarin</th>
                            <th class="px-4 py-3 text-right">Target Final</th>
                            <th class="px-4 py-3 text-right">Pemasukan</th>
                            <th class="px-4 py-3 text-right">Pengeluaran</th>
                            <th class="px-4 py-3 text-right">Realisasi Bersih</th>
                            <th class="px-4 py-3 text-right">Selisih</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <?php $__currentLoopData = $dailyTargets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isWorkday = $dt->is_workday;
                        ?>
                        <tr class="hover:bg-slate-50/80 transition <?php echo e($dt->date->isToday() ? 'bg-blue-50/70 font-semibold' : (!$isWorkday ? 'bg-slate-50/40 text-slate-500' : '')); ?>">
                            
                            <td class="px-4 py-3 font-medium whitespace-nowrap">
                                <span class="<?php echo e($isWorkday ? 'text-slate-800' : 'text-slate-500'); ?>">
                                    <?php echo e($dt->date->translatedFormat('d M Y (D)')); ?>

                                </span>
                                <?php if($dt->date->isToday()): ?>
                                    <span class="ml-1 text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-full uppercase font-bold">Hari Ini</span>
                                <?php elseif(!$isWorkday): ?>
                                    <span class="ml-1 text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded-md font-semibold">Libur</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono">
                                <?php if($isWorkday): ?>
                                    Rp <?php echo e(number_format($dt->base_target, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono <?php echo e($dt->carry_forward > 0 ? 'text-rose-600 font-bold' : 'text-slate-400'); ?>">
                                <?php if($isWorkday && $dt->carry_forward > 0): ?>
                                    Rp <?php echo e(number_format($dt->carry_forward, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono font-bold <?php echo e($isWorkday ? 'text-blue-600' : 'text-slate-400'); ?>">
                                <?php if($isWorkday): ?>
                                    Rp <?php echo e(number_format($dt->adjusted_target, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono text-emerald-600 font-medium">
                                Rp <?php echo e(number_format($dt->actual_income, 0, ',', '.')); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono text-rose-600 font-medium">
                                Rp <?php echo e(number_format($dt->actual_expense, 0, ',', '.')); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono font-bold <?php echo e($dt->net_income >= $dt->adjusted_target && $isWorkday ? 'text-emerald-600' : ($dt->net_income > 0 ? 'text-blue-600' : 'text-slate-700')); ?>">
                                Rp <?php echo e(number_format($dt->net_income, 0, ',', '.')); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-right font-mono font-bold <?php echo e($dt->variance >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                <?php if($isWorkday): ?>
                                    <?php echo e($dt->variance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($dt->variance, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <?php if($dt->net_income > 0): ?>
                                        <span class="text-emerald-600">+Rp <?php echo e(number_format($dt->net_income, 0, ',', '.')); ?> (Bonus)</span>
                                    <?php else: ?>
                                        <span class="text-slate-400">-</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <?php if(!$isWorkday): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                        Non-Operasional
                                    </span>
                                <?php elseif($dt->is_achieved): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        Tercapai ✓
                                    </span>
                                <?php elseif($dt->status_color === 'yellow'): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        Mendekati
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                        Belum
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50/60 rounded-2xl border border-blue-200/60">
            <p class="text-xs text-slate-600 leading-relaxed">
                <i class="fas fa-info-circle text-blue-600 mr-1.5"></i>
                <strong>Sistem Carry-Forward Defisit & Hari Kerja Kustom:</strong> Target harian hanya dibebankan pada hari kerja aktif operasional (default: <strong>Senin s/d Jumat</strong>). Hari libur / akhir pekan berstatus non-operasional (Target Rp 0). Jika realisasi bersih harian mengalami defisit, defisit tersebut secara otomatis dialihkan dan ditambahkan ke target hari kerja aktif berikutnya.
            </p>
        </div>
    </div>

    
    <div id="targetModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('targetModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 animate-fade-up">
                <form action="<?php echo e(route('admin.update_target')); ?>" method="POST" id="targetForm">
                    <?php echo csrf_field(); ?>
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-bullseye text-xl"></i>
                            </div>
                            <div class="w-full">
                                <h3 class="text-base font-bold text-slate-900" id="modal-title">Konfigurasi Target & Jadwal Hari Kerja</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Atur target bulanan dan skema hari kerja aktif operasional laundry (Senin - Jumat / Hari Libur).</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-4">
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Tipe Target</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2.5 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                        <input type="radio" name="target_type" value="bulanan" checked onchange="toggleTargetLabel('bulanan')" class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block">Target Bulanan</span>
                                            <span class="text-[10px] text-slate-400">Target per bulan</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-2.5 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                        <input type="radio" name="target_type" value="tahunan" onchange="toggleTargetLabel('tahunan')" class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block">Target Tahunan</span>
                                            <span class="text-[10px] text-slate-400">Target per tahun</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            
                            <div>
                                <label for="targetInput" id="targetInputLabel" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nominal Target Bulanan (Rp) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                                    <input type="number" name="target" id="targetInput" value="<?php echo e($limitPemasukanBulanan); ?>" required min="0"
                                           oninput="recalculatePreview()"
                                           class="w-full pl-9 pr-4 py-2.5 text-xs font-bold border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-slate-900">
                                </div>
                            </div>

                            
                            <div class="border-t border-slate-100 pt-4">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Skema Hari Kerja Operasional</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-3">
                                    
                                    <label class="flex items-start gap-2.5 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50 transition">
                                        <input type="radio" name="workdays_mode" value="senin_jumat" <?php echo e($workdaysMode === 'senin_jumat' ? 'checked' : ''); ?> onchange="toggleWorkdaysMode('senin_jumat')" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Senin - Jumat (5 Hari)</span>
                                            <span class="block text-[10px] text-slate-400">Sabtu & Minggu libur otomatis</span>
                                        </div>
                                    </label>

                                    
                                    <label class="flex items-start gap-2.5 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50 transition">
                                        <input type="radio" name="workdays_mode" value="senin_sabtu" <?php echo e($workdaysMode === 'senin_sabtu' ? 'checked' : ''); ?> onchange="toggleWorkdaysMode('senin_sabtu')" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Senin - Sabtu (6 Hari)</span>
                                            <span class="block text-[10px] text-slate-400">Hanya Minggu yang libur</span>
                                        </div>
                                    </label>

                                    
                                    <label class="flex items-start gap-2.5 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50 transition">
                                        <input type="radio" name="workdays_mode" value="setiap_hari" <?php echo e($workdaysMode === 'setiap_hari' ? 'checked' : ''); ?> onchange="toggleWorkdaysMode('setiap_hari')" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Setiap Hari (7 Hari)</span>
                                            <span class="block text-[10px] text-slate-400">Kalender penuh tanpa libur</span>
                                        </div>
                                    </label>

                                    
                                    <label class="flex items-start gap-2.5 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50 transition">
                                        <input type="radio" name="workdays_mode" value="custom" <?php echo e($workdaysMode === 'custom' ? 'checked' : ''); ?> onchange="toggleWorkdaysMode('custom')" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Manual / Custom Hari</span>
                                            <span class="block text-[10px] text-slate-400">Tentukan angka hari sendiri</span>
                                        </div>
                                    </label>
                                </div>

                                
                                <div id="customDaysWrapper" class="mb-3 <?php echo e($workdaysMode === 'custom' ? '' : 'hidden'); ?>">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Hari Kerja Target Per Bulan</label>
                                    <input type="number" name="custom_days" id="customDaysInput" min="1" max="31" value="<?php echo e($customDays); ?>" placeholder="Contoh: 21"
                                           oninput="recalculatePreview()"
                                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                                    <p class="text-[10px] text-slate-400 mt-1">Misal 21 hari kerja aktif dalam sebulan.</p>
                                </div>

                                
                                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            <i class="fas fa-umbrella-beach text-amber-500 mr-1"></i> Jumlah Hari Libur Nasional / Cuti di Bulan Ini
                                        </label>
                                        <input type="number" name="holidays_count" id="holidaysCountInput" min="0" max="31" value="<?php echo e($holidaysCount); ?>"
                                               oninput="recalculatePreview()"
                                               class="w-full border border-slate-200 bg-white rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"
                                               placeholder="0 (jika tidak ada libur tambahan)">
                                        <p class="text-[10px] text-slate-400 mt-1">Jumlah hari libur nasional pada hari kerja (contoh: 1 atau 2 hari) yang akan dikurangi dari pembagi target.</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            Daftar Tanggal Libur Spesifik (Opsional)
                                        </label>
                                        <input type="text" name="holiday_dates" id="holidayDatesInput" value="<?php echo e($holidayDatesString); ?>"
                                               class="w-full border border-slate-200 bg-white rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-mono"
                                               placeholder="Contoh: 2026-08-17, 2026-08-18">
                                        <p class="text-[10px] text-slate-400 mt-1">Format YYYY-MM-DD dipisahkan koma untuk otomatis membebaskan target pada tanggal tersebut.</p>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-blue-50/70 p-3.5 rounded-xl border border-blue-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-blue-600 font-bold uppercase block">Estimasi Target Harian</span>
                                    <span class="text-xs text-slate-600 font-medium" id="previewFormulaText">
                                        Rp <?php echo e(number_format($limitPemasukanBulanan, 0, ',', '.')); ?> ÷ <?php echo e($activeWorkDaysCount); ?> hari
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-extrabold text-blue-700 font-mono" id="previewDailyTargetText">
                                        Rp <?php echo e(number_format($baseDailyTarget, 0, ',', '.')); ?>

                                    </span>
                                    <span class="text-[10px] text-slate-400 block">/ hari kerja aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" onclick="document.getElementById('targetModal').classList.add('hidden')"
                                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition active:scale-95 flex items-center gap-1.5">
                            <i class="fas fa-save"></i> Simpan Target & Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTargetType = 'bulanan';
        const annualTargetVal = <?php echo e($annualTarget); ?>;
        const monthlyTargetVal = <?php echo e($limitPemasukanBulanan); ?>;
        let currentDaysInMonth = <?php echo e(now()->daysInMonth); ?>;
        let currentWeekdaysInMonth = 22; // default approx

        function toggleTargetLabel(type) {
            currentTargetType = type;
            const label = document.getElementById('targetInputLabel');
            const input = document.getElementById('targetInput');
            if (type === 'tahunan') {
                label.innerText = 'Nominal Target Tahunan (Rp) *';
                input.value = annualTargetVal;
            } else {
                label.innerText = 'Nominal Target Bulanan (Rp) *';
                input.value = monthlyTargetVal;
            }
            recalculatePreview();
        }

        function toggleWorkdaysMode(mode) {
            const wrapper = document.getElementById('customDaysWrapper');
            if (mode === 'custom') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }
            recalculatePreview();
        }

        function recalculatePreview() {
            const targetInput = parseFloat(document.getElementById('targetInput').value) || 0;
            let monthlyVal = currentTargetType === 'tahunan' ? Math.ceil(targetInput / 12) : targetInput;

            const mode = document.querySelector('input[name="workdays_mode"]:checked')?.value || 'senin_jumat';
            let workdays = 22;

            if (mode === 'senin_jumat') {
                workdays = 21; // estimated weekdays
            } else if (mode === 'senin_sabtu') {
                workdays = 26;
            } else if (mode === 'setiap_hari') {
                workdays = currentDaysInMonth;
            } else if (mode === 'custom') {
                workdays = parseInt(document.getElementById('customDaysInput').value) || 22;
            }

            const holidaysCount = parseInt(document.getElementById('holidaysCountInput').value) || 0;
            const finalDays = Math.max(1, workdays - holidaysCount);

            const dailyVal = Math.ceil(monthlyVal / finalDays);

            document.getElementById('previewFormulaText').innerText = 
                'Rp ' + new Intl.NumberFormat('id-ID').format(monthlyVal) + ' ÷ ' + finalDays + ' hari kerja';
            document.getElementById('previewDailyTargetText').innerText = 
                'Rp ' + new Intl.NumberFormat('id-ID').format(dailyVal);
        }
    </script>

    <!-- Grafik Tren & Distribusi Pengeluaran (2 kolom + 1 kolom kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Line Chart (2/3 lebar di lg) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-5">
            <div class="flex justify-between items-center flex-wrap mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Tren Pendapatan & Pengeluaran</h2>
            </div>
            <canvas id="trendChart"></canvas>
        </div>

        <!-- Distribusi Pengeluaran + Insight -->
        <div class="space-y-6">
            <!-- Card Distribusi -->
            <div class="bg-white rounded-2xl shadow-md p-5">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2"><i class="fas fa-chart-simple text-blue-500"></i> Distribusi Pengeluaran</h3>
                <div class="mt-4 space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $distribusiPengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700"><?php echo e($item['kategori']); ?></span>
                            <span class="text-gray-600"><?php echo e($item['persen']); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo e($item['persen']); ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500">Belum ada pengeluaran.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Card Insight -->
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-100 rounded-2xl shadow-sm p-5">
                <div class="flex gap-3">
                    <div class="text-amber-600 text-xl"><i class="fas fa-lightbulb"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Insight</h4>
                        <p class="text-sm text-gray-600 mt-1">Pengeluaran operasional meningkat 5% dari bulan lalu karena penambahan shift malam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <table class="min-w-full bg-white border mt-6">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Bulan</th>
                    <th class="px-4 py-2 border">Tahun</th>
                    <th class="px-4 py-2 border">Pemasukan</th>
                    <th class="px-4 py-2 border">Pengeluaran</th>
                    <th class="px-4 py-2 border">Laba</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $laporanBulanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo e($data['bulan']); ?></td>
                    <td class="border px-4 py-2"><?php echo e($data['tahun']); ?></td>
                    <td class="border px-4 py-2 text-green-600">
                        Rp <?php echo e(number_format($data['pemasukan'],0,',','.')); ?>

                    </td>
                    <td class="border px-4 py-2 text-red-600">
                        Rp <?php echo e(number_format($data['pengeluaran'],0,',','.')); ?>

                    </td>
                    <td class="border px-4 py-2 font-bold">
                        Rp <?php echo e(number_format($data['laba'],0,',','.')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    <!-- Detail Transaksi Terbaru -->
    <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="flex justify-between items-center flex-wrap mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Detail Transaksi Terbaru</h2>
            <a href="<?php echo e(route('admin.transactions.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">Lihat Semua Ledger <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentExpenses->merge($recentTransactions)->sortByDesc('created_at')->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isExpense = $item instanceof \App\Models\Pengeluaran;
                        $date = $isExpense ? $item->tanggal : $item->created_at;
                        $description = $isExpense ? $item->nama : 'Transaksi #' . $item->transaksi_code . ' - ' . $item->customer_name;
                        $category = $isExpense ? $item->kategori : 'LAYANAN';
                        $type = $isExpense ? 'PENGELUARAN' : 'PEMASUKAN';
                        $nominal = $isExpense ? $item->nominal : $item->total_price;
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-sm text-gray-700"><?php echo e(\Carbon\Carbon::parse($date)->format('d M Y')); ?></td>
                        <td class="px-5 py-3 text-sm font-medium text-gray-800"><?php echo e($description); ?></td>
                        <td class="px-5 py-3 text-sm text-gray-600"><?php echo e($category); ?></td>
                        <td class="px-5 py-3">
                            <span class="<?php echo e($isExpense ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'); ?> text-xs font-semibold px-2.5 py-1 rounded-full">
                                <?php echo e($type); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3 text-right text-sm font-medium <?php echo e($isExpense ? 'text-red-600' : 'text-green-600'); ?>">
                            <?php echo e($isExpense ? '-' : '+'); ?> Rp <?php echo e(number_format($nominal, 0, ',', '.')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                            Belum ada data transaksi
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.laporan_keuangan.partials.export_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('trendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months, 15, 512) ?>,
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: <?php echo json_encode($dataMasuk, 15, 512) ?>,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Pengeluaran',
                        data: <?php echo json_encode($dataKeluar, 15, 512) ?>,
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.02)',
                        borderWidth: 3,
                        pointBackgroundColor: '#f97316',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true, boxWidth: 10 }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: { color: '#e2e8f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });

    function toggleCustomFilter(btn) {
        const form = document.getElementById('customFilterDiv');
        const isHidden = form.classList.toggle('hidden');
        
        const container = btn.parentElement;
        const links = container.querySelectorAll('a');
        
        if (!isHidden) {
            // Activate Custom button visually
            btn.classList.remove('text-gray-600', 'hover:text-gray-800');
            btn.classList.add('bg-blue-600', 'text-white');
            
            // Visually deactivate others
            links.forEach(l => {
                if (l.classList.contains('bg-blue-600')) {
                    l.dataset.wasActive = 'true';
                    l.classList.remove('bg-blue-600', 'text-white');
                    l.classList.add('text-gray-600', 'hover:text-gray-800');
                }
            });
        } else {
            // Revert Custom button
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('text-gray-600', 'hover:text-gray-800');
            
            // Restore previous active button
            links.forEach(l => {
                if (l.dataset.wasActive === 'true') {
                    l.classList.add('bg-blue-600', 'text-white');
                    l.classList.remove('text-gray-600', 'hover:text-gray-800');
                    delete l.dataset.wasActive;
                }
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/laporan_keuangan/index.blade.php ENDPATH**/ ?>