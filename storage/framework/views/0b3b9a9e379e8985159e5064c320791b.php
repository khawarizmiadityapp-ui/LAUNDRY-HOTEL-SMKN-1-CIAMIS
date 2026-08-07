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
                    <button type="button" onclick="openExportModal()" class="flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
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

    <!-- Target Pemasukan Bulanan & Tahunan -->
    <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-semibold text-gray-800">Target Pemasukan Admin</h3>
                    <button onclick="document.getElementById('targetModal').classList.remove('hidden')" class="text-blue-500 hover:text-blue-700 text-sm transition">
                        <i class="fas fa-edit"></i> Edit Target
                    </button>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mt-1">
                    <span>Target Tahunan: <strong class="text-gray-800">Rp <?php echo e(number_format($annualTarget, 0, ',', '.')); ?></strong></span>
                    <span>•</span>
                    <span>Target Bulanan: <strong class="text-gray-800">Rp <?php echo e(number_format($limitPemasukanBulanan, 0, ',', '.')); ?></strong></span>
                    <span>•</span>
                    <span>Target Dasar Harian: <strong class="text-blue-600">Rp <?php echo e(number_format(ceil($limitPemasukanBulanan / now()->daysInMonth), 0, ',', '.')); ?></strong></span>
                </div>
                <p class="text-sm text-gray-500 mt-2">Realisasi Bulan Ini: <strong class="text-gray-800">Rp <?php echo e(number_format($realisasiBulanIni, 0, ',', '.')); ?></strong> dari Target Rp <?php echo e(number_format($limitPemasukanBulanan, 0, ',', '.')); ?></p>
            </div>
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-semibold <?php echo e($persenTargetBulanIni >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>">
                <?php echo e(number_format($persenTargetBulanIni, 2)); ?>%
            </span>
        </div>
        <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full <?php echo e($persenTargetBulanIni >= 100 ? 'bg-emerald-500' : 'bg-blue-600'); ?>" style="width: <?php echo e(min(100, $persenTargetBulanIni)); ?>%"></div>
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

        
        <div class="bg-white rounded-xl p-5 mb-5 border-2 border-blue-300 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Target Hari Ini</p>
                    <p class="text-xs text-gray-400"><?php echo e($todayTarget->date->translatedFormat('l, d F Y')); ?></p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    <?php echo e($todayTarget->status_color === 'green' ? 'bg-emerald-100 text-emerald-700' : 
                       ($todayTarget->status_color === 'yellow' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')); ?>">
                    <?php echo e($todayTarget->is_achieved ? 'Target Tercapai ✓' : 'Belum Tercapai'); ?>

                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Target Dasar</p>
                    <p class="text-lg font-bold text-gray-800">Rp <?php echo e(number_format($todayTarget->base_target, 0, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">+ Defisit Kemarin</p>
                    <p class="text-lg font-bold <?php echo e($todayTarget->carry_forward > 0 ? 'text-rose-600' : 'text-gray-400'); ?>">
                        Rp <?php echo e(number_format($todayTarget->carry_forward, 0, ',', '.')); ?>

                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Target Final Hari Ini</p>
                    <p class="text-lg font-bold text-blue-600">Rp <?php echo e(number_format($todayTarget->adjusted_target, 0, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Realisasi Bersih</p>
                    <p class="text-lg font-bold <?php echo e($todayTarget->net_income >= $todayTarget->adjusted_target ? 'text-emerald-600' : 'text-amber-600'); ?>">
                        Rp <?php echo e(number_format($todayTarget->net_income, 0, ',', '.')); ?>

                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Selisih (Defisit/Surplus)</p>
                    <p class="text-lg font-bold <?php echo e($todayTarget->variance >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                        <?php echo e($todayTarget->variance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($todayTarget->variance, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between text-xs font-semibold">
                    <span class="text-gray-600">Progress Hari Ini</span>
                    <span class="text-blue-600"><?php echo e($todayTarget->achievement_percentage); ?>%</span>
                </div>
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-500 <?php echo e($todayTarget->is_achieved ? 'bg-emerald-500' : 'bg-blue-600'); ?>" 
                         style="width: <?php echo e(min(100, $todayTarget->achievement_percentage)); ?>%"></div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
            <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fas fa-list text-blue-600"></i> Rincian Per Hari (Bulan <?php echo e(now()->translatedFormat('F Y')); ?>)
                </h4>
                <span class="text-xs text-gray-500 font-medium">Total: <?php echo e($dailyTargets->count()); ?> Hari</span>
            </div>
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Target Dasar</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Defisit Kemarin</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Target Final</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pemasukan</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pengeluaran</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Realisasi Bersih</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Selisih</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $dailyTargets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isToday = $target->date->isToday();
                        ?>
                        <tr class="hover:bg-blue-50/50 transition-colors <?php echo e($isToday ? 'bg-blue-50/80 font-medium' : ''); ?>">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-800">
                                            <?php echo e($target->date->translatedFormat('l')); ?>

                                            <?php if($isToday): ?>
                                                <span class="ml-1 text-[10px] bg-blue-600 text-white px-1.5 py-0.5 rounded font-bold uppercase">Hari Ini</span>
                                            <?php endif; ?>
                                        </span>
                                        <span class="text-xs text-gray-500"><?php echo e($target->date->translatedFormat('d M Y')); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-700 whitespace-nowrap">
                                Rp <?php echo e(number_format($target->base_target, 0, ',', '.')); ?>

                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <?php if($target->carry_forward > 0): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-700">
                                    +Rp <?php echo e(number_format($target->carry_forward, 0, ',', '.')); ?>

                                </span>
                                <?php else: ?>
                                <span class="text-xs text-gray-400">Rp 0</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-blue-700 whitespace-nowrap">
                                Rp <?php echo e(number_format($target->adjusted_target, 0, ',', '.')); ?>

                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-600 whitespace-nowrap">
                                Rp <?php echo e(number_format($target->actual_income, 0, ',', '.')); ?>

                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-rose-600 whitespace-nowrap">
                                Rp <?php echo e(number_format($target->actual_expense, 0, ',', '.')); ?>

                            </td>
                            <td class="px-4 py-3 text-right font-bold <?php echo e($target->net_income >= $target->adjusted_target ? 'text-emerald-700' : ($target->net_income >= 0 ? 'text-amber-700' : 'text-rose-700')); ?> whitespace-nowrap">
                                Rp <?php echo e(number_format($target->net_income, 0, ',', '.')); ?>

                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <span class="font-bold <?php echo e($target->variance >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                    <?php echo e($target->variance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($target->variance, 0, ',', '.')); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                    <?php echo e($target->status_color === 'green' ? 'bg-emerald-100 text-emerald-700' : 
                                       ($target->status_color === 'yellow' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')); ?>">
                                    <?php echo e($target->achievement_percentage); ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-gray-600 leading-relaxed">
                <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                <strong>Sistem Carry-Forward Defisit:</strong> Target harian dihitung dari Target Bulanan ÷ Jumlah Hari. Jika realisasi bersih harian mengalami defisit (minus dari target final), defisit tersebut secara otomatis ditambahkan ke target hari berikutnya agar target keseluruhan admin tetap berlanjut secara konsisten.
            </p>
        </div>
    </div>

    
    <div id="targetModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('targetModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="<?php echo e(route('admin.update_target')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-bullseye text-blue-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit Target Admin</h3>
                                <p class="text-xs text-gray-500 mt-1">Mengatur target pendapatan admin per tahun atau per bulan.</p>
                                
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Target</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                                                <input type="radio" name="target_type" value="tahunan" onchange="toggleTargetLabel('tahunan')" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-semibold text-gray-700">Target Tahunan</span>
                                            </label>
                                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                                                <input type="radio" name="target_type" value="bulanan" checked onchange="toggleTargetLabel('bulanan')" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-semibold text-gray-700">Target Bulanan</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="targetInput" id="targetInputLabel" class="block text-sm font-medium text-gray-700">Nominal Target Bulanan (Rp)</label>
                                        <input type="number" name="target" id="targetInput" value="<?php echo e($limitPemasukanBulanan); ?>" class="mt-1 w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold" required>
                                        <p class="text-xs text-gray-400 mt-1" id="targetHelpText">Target harian dasar akan dihitung otomatis: Target ÷ Jumlah Hari.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm shadow-md">Simpan Target</button>
                        <button type="button" onclick="document.getElementById('targetModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleTargetLabel(type) {
            const label = document.getElementById('targetInputLabel');
            const input = document.getElementById('targetInput');
            const help = document.getElementById('targetHelpText');
            if (type === 'tahunan') {
                label.innerText = 'Nominal Target Tahunan (Rp)';
                input.value = <?php echo e($annualTarget); ?>;
                help.innerText = 'Target bulanan akan dihitung (Target Tahunan ÷ 12) & target harian otomatis disesuaikan.';
            } else {
                label.innerText = 'Nominal Target Bulanan (Rp)';
                input.value = <?php echo e($limitPemasukanBulanan); ?>;
                help.innerText = 'Target harian dasar akan dihitung otomatis: Target ÷ Jumlah Hari.';
            }
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