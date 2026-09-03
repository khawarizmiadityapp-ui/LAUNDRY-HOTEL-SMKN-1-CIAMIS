<?php $__env->startSection('title', 'Manajemen & Rekapan Pembayaran - Bening Laundry'); ?>
<?php $__env->startSection('content'); ?>

<div class="p-6 md:p-8" x-data="pembayaranApp()">
    <div class="max-w-7xl mx-auto">

        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen & Rekapan Pembayaran</h1>
                <p class="text-slate-500 text-xs md:text-sm mt-0.5">Kelola pelunasan transaksi, rekapan harian & bulanan beserta rincian pelayanan laundry.</p>
            </div>

            
            <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 self-start md:self-auto">
                <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'daftar'])); ?>"
                   class="px-4 py-2 text-xs font-bold rounded-lg transition <?php echo e($tab === 'daftar' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'); ?>">
                    <i class="fas fa-list mr-1"></i> Daftar Transaksi
                </a>
                <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'harian'])); ?>"
                   class="px-4 py-2 text-xs font-bold rounded-lg transition <?php echo e($tab === 'harian' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'); ?>">
                    <i class="fas fa-calendar-day mr-1"></i> Rekapan Harian
                </a>
                <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'bulanan'])); ?>"
                   class="px-4 py-2 text-xs font-bold rounded-lg transition <?php echo e($tab === 'bulanan' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'); ?>">
                    <i class="fas fa-calendar-alt mr-1"></i> Rekapan Bulanan
                </a>
            </div>
        </div>

        
        
        
        <?php if($tab === 'daftar'): ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                <div class="bg-white rounded-2xl shadow-card p-6 border-l-4 border-blue-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs uppercase font-semibold">Total Pendapatan Diterima Hari Ini</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($totalPendapatanHariIni, 0, ',', '.')); ?></p>
                        </div>
                        <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-wallet text-lg"></i>
                        </span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-500 border-t border-slate-100 pt-3">
                        <span>Pemasukan kas langsung hari ini</span>
                        <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'harian'])); ?>" class="text-blue-600 font-semibold hover:underline">
                            Lihat Rincian Harian <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </a>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-card p-6 border-l-4 border-rose-500 border border-slate-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs uppercase font-semibold">Transaksi Belum Lunas (Piutang)</p>
                            <p class="text-2xl font-bold text-rose-600 mt-1"><?php echo e($transaksiBelumLunas); ?> Transaksi</p>
                        </div>
                        <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <i class="fas fa-clock text-lg"></i>
                        </span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-500 border-t border-slate-100 pt-3">
                        <span>Menunggu pembayaran pelanggan</span>
                        <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => 'belum_bayar'])); ?>" class="text-rose-600 font-semibold hover:underline">
                            Filter Belum Lunas <i class="fas fa-filter ml-1 text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'daftar'])); ?>" 
                       class="px-4 py-2 text-xs font-semibold rounded-xl transition <?php echo e(!request('status') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">
                        Semua Status
                    </a>
                    <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => 'lunas'])); ?>" 
                       class="px-4 py-2 text-xs font-semibold rounded-xl transition <?php echo e(request('status') == 'lunas' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">
                        Lunas
                    </a>
                    <a href="<?php echo e(route('admin.pembayaran.index', ['tab' => 'daftar', 'status' => 'belum_bayar'])); ?>" 
                       class="px-4 py-2 text-xs font-semibold rounded-xl transition <?php echo e(request('status') == 'belum_bayar' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">
                        Belum Lunas
                    </a>
                </div>

                <form action="<?php echo e(route('admin.pembayaran.index')); ?>" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="daftar">
                    <?php if(request('status')): ?> <input type="hidden" name="status" value="<?php echo e(request('status')); ?>"> <?php endif; ?>
                    <div class="relative w-64">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="Cari pelanggan / kode..."
                               class="w-full pl-9 pr-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition h-9">
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">ID Transaksi</th>
                                <th class="px-6 py-4">Pelanggan & Rincian Layanan</th>
                                <th class="px-6 py-4">Total Tagihan</th>
                                <th class="px-6 py-4">Metode Bayar</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-xs text-slate-900">
                                    <?php echo e($trx->transaksi_code); ?>

                                    <div class="text-[10px] text-slate-400 font-sans mt-0.5"><?php echo e($trx->created_at->format('d/m/Y H:i')); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900"><?php echo e($trx->customer_name); ?></div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        <?php if($trx->details && $trx->details->count() > 0): ?>
                                            <?php echo e($trx->details->map(fn($d) => ($d->layanan->nama ?? 'Layanan') . ' (' . $d->qty . 'x)')->join(', ')); ?>

                                        <?php else: ?>
                                            <?php echo e(ucfirst($trx->service_type)); ?> - <?php echo e($trx->weight); ?> kg
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900 font-mono">
                                    Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?>

                                    <?php if($trx->dibayar > 0): ?>
                                        <div class="text-[10px] text-slate-400 font-sans mt-0.5">
                                            Bayar: Rp <?php echo e(number_format($trx->dibayar, 0, ',', '.')); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap uppercase text-xs font-semibold text-slate-600">
                                    <?php echo e(str_replace('_', ' ', $trx->payment_method ?: 'Tunai')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($trx->payment_status == 'lunas'): ?>
                                        <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="fas fa-check-circle mr-1 mt-0.5"></i> Lunas
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg bg-rose-100 text-rose-800 border border-rose-200">
                                            <i class="fas fa-hourglass-half mr-1 mt-0.5"></i> Belum Lunas
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if($trx->payment_status != 'lunas'): ?>
                                        <button onclick="openPaymentModal('<?php echo e($trx->transaksi_code); ?>', <?php echo e($trx->total_price); ?>)"
                                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5">
                                            <i class="fas fa-cash-register"></i> Bayar
                                        </button>
                                        <?php endif; ?>
                                        <button type="button" @click="openNota('<?php echo e(route('pos.nota', $trx->id)); ?>')"
                                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                            <i class="fas fa-receipt text-slate-400"></i> Struk
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada data transaksi ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-100">
                    <?php echo e($transactions->links('vendor.pagination.custom')); ?>

                </div>
            </div>

        
        
        
        <?php elseif($tab === 'harian'): ?>
            
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <form action="<?php echo e(route('admin.pembayaran.index')); ?>" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="tab" value="harian">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pilih Tanggal:</label>
                        <input type="date" name="tanggal" value="<?php echo e($tanggalHarian); ?>" onchange="this.form.submit()"
                               class="text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                    </div>
                </form>

                <a href="<?php echo e(route('admin.pembayaran.rekap_harian.pdf', ['tanggal' => $tanggalHarian])); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md transition active:scale-95">
                    <i class="fas fa-file-pdf text-rose-400"></i> Cetak Rekapan Harian (PDF)
                </a>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Omzet Hari Ini</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($harianTotalPendapatan, 0, ',', '.')); ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e(\Carbon\Carbon::parse($tanggalHarian)->translatedFormat('d F Y')); ?></p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jumlah Transaksi Lunas</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($harianTotalTransaksi); ?> Order</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Selesai / Lunas</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Penerimaan Tunai (Cash)</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">Rp <?php echo e(number_format($harianTotalTunai, 0, ',', '.')); ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Fisik di laci kasir</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Penerimaan Non-Tunai</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">Rp <?php echo e(number_format($harianTotalNonTunai, 0, ',', '.')); ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">QRIS & Transfer Bank</p>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rincian Performa Jenis Pelayanan (Hari Ini)</h2>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Nama Layanan Laundry</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Total Qty / Berat</th>
                                <th class="px-6 py-3">Jumlah Transaksi</th>
                                <th class="px-6 py-3">Total Pendapatan</th>
                                <th class="px-6 py-3">Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $harianServiceBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $percent = $harianTotalPendapatan > 0 ? round(($srv['total'] / $harianTotalPendapatan) * 100, 1) : 0;
                            ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 font-bold text-slate-900"><?php echo e($srv['nama']); ?></td>
                                <td class="px-6 py-3.5 whitespace-nowrap uppercase text-xs text-slate-600 font-semibold"><?php echo e($srv['kategori']); ?></td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-800">
                                    <?php echo e($srv['qty']); ?> <?php echo e($srv['kategori'] == 'kiloan' ? 'kg' : 'pcs'); ?>

                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-xs font-semibold text-slate-600"><?php echo e($srv['count']); ?>x order</td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp <?php echo e(number_format($srv['total'], 0, ',', '.')); ?>

                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600"><?php echo e($percent); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi layanan pada tanggal ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Daftar Transaksi Pelanggan (<?php echo e($tanggalHarian); ?>)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">No. Transaksi</th>
                                <th class="px-6 py-3">Pelanggan</th>
                                <th class="px-6 py-3">Rincian Layanan</th>
                                <th class="px-6 py-3">Metode Bayar</th>
                                <th class="px-6 py-3">Nominal</th>
                                <th class="px-6 py-3 text-right">Struk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $harianTrx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-bold text-xs text-slate-900"><?php echo e($trx->transaksi_code); ?></td>
                                <td class="px-6 py-3 whitespace-nowrap font-semibold text-slate-900"><?php echo e($trx->customer_name); ?></td>
                                <td class="px-6 py-3 text-xs text-slate-600">
                                    <?php if($trx->details && $trx->details->count() > 0): ?>
                                        <?php echo e($trx->details->map(fn($d) => ($d->layanan->nama ?? 'Layanan') . ' (' . $d->qty . 'x)')->join(', ')); ?>

                                    <?php else: ?>
                                        <?php echo e(ucfirst($trx->service_type)); ?> (<?php echo e($trx->weight); ?> kg)
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap uppercase text-xs font-semibold text-slate-600">
                                    <?php echo e(str_replace('_', ' ', $trx->payment_method ?: 'Tunai')); ?>

                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?>

                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <button type="button" @click="openNota('<?php echo e(route('pos.nota', $trx->id)); ?>')"
                                            class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                                        <i class="fas fa-receipt mr-1 text-slate-400"></i> Nota
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada transaksi pada tanggal ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        
        
        
        <?php elseif($tab === 'bulanan'): ?>
            
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <form action="<?php echo e(route('admin.pembayaran.index')); ?>" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="tab" value="bulanan">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pilih Bulan & Tahun:</label>
                        <select name="bulan" onchange="this.form.submit()"
                                class="text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                            <?php for($m = 1; $m <= 12; $m++): ?>
                                <option value="<?php echo e($m); ?>" <?php echo e($bulanBulanan == $m ? 'selected' : ''); ?>>
                                    <?php echo e(\Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F')); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                        <select name="tahun" onchange="this.form.submit()"
                                class="text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                            <?php for($y = now()->year - 2; $y <= now()->year + 1; $y++): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($tahunBulanan == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </form>

                <a href="<?php echo e(route('admin.pembayaran.rekap_bulanan.pdf', ['bulan' => $bulanBulanan, 'tahun' => $tahunBulanan])); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md transition active:scale-95">
                    <i class="fas fa-file-pdf text-rose-400"></i> Cetak Rekapan Bulanan (PDF)
                </a>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Omzet Bulan Ini</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">Rp <?php echo e(number_format($bulananTotalPendapatan, 0, ',', '.')); ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e(\Carbon\Carbon::create($tahunBulanan, $bulanBulanan, 1)->translatedFormat('F Y')); ?></p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($bulananTotalTransaksi); ?> Order</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Sepanjang bulan ini</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-rata Omzet Harian</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">Rp <?php echo e(number_format($rataRataHarian, 0, ',', '.')); ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Per hari kerja</p>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Layanan Terlaris</p>
                    <p class="text-xl font-bold text-indigo-600 mt-1 truncate"><?php echo e($layananTerlaris); ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Paling diminati pelanggan</p>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">1. Ringkasan Performa Seluruh Jenis Layanan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Nama Layanan Laundry</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Total Volume (Kg/Pcs)</th>
                                <th class="px-6 py-3">Total Order</th>
                                <th class="px-6 py-3">Total Omzet</th>
                                <th class="px-6 py-3">Porsi %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $bulananServiceBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $percent = $bulananTotalPendapatan > 0 ? round(($srv['total'] / $bulananTotalPendapatan) * 100, 1) : 0;
                            ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 font-bold text-slate-900"><?php echo e($srv['nama']); ?></td>
                                <td class="px-6 py-3.5 whitespace-nowrap uppercase text-xs font-semibold text-slate-600"><?php echo e($srv['kategori']); ?></td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-800">
                                    <?php echo e($srv['qty']); ?> <?php echo e($srv['kategori'] == 'kiloan' ? 'kg' : 'pcs'); ?>

                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-xs font-semibold text-slate-600"><?php echo e($srv['count']); ?>x</td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp <?php echo e(number_format($srv['total'], 0, ',', '.')); ?>

                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600"><?php echo e($percent); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi layanan pada bulan ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">2. Rincian Penerimaan Kas Harian (1 - <?php echo e(count($dailyBreakdown)); ?>)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Jumlah Transaksi</th>
                                <th class="px-6 py-3">Tunai (Cash)</th>
                                <th class="px-6 py-3">Non-Tunai</th>
                                <th class="px-6 py-3">Total Omzet Harian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__currentLoopData = $dailyBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/70 transition <?php echo e($day['total'] > 0 ? '' : 'text-slate-400'); ?>">
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-semibold"><?php echo e($day['tanggal']); ?></td>
                                <td class="px-6 py-3 whitespace-nowrap"><?php echo e($day['hari']); ?></td>
                                <td class="px-6 py-3 whitespace-nowrap"><?php echo e($day['count'] > 0 ? $day['count'] . ' order' : '-'); ?></td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono">
                                    <?php echo e($day['tunai'] > 0 ? 'Rp ' . number_format($day['tunai'], 0, ',', '.') : '-'); ?>

                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono">
                                    <?php echo e($day['non_tunai'] > 0 ? 'Rp ' . number_format($day['non_tunai'], 0, ',', '.') : '-'); ?>

                                </td>
                                <td class="px-6 py-3 whitespace-nowrap font-mono font-bold <?php echo e($day['total'] > 0 ? 'text-slate-900' : 'text-slate-400'); ?>">
                                    <?php echo e($day['total'] > 0 ? 'Rp ' . number_format($day['total'], 0, ',', '.') : 'Rp 0'); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>

    
    <div id="paymentModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
        <div class="relative mx-auto p-6 border w-full max-w-md shadow-2xl rounded-2xl bg-white animate-fade-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-slate-900">Proses Pelunasan Pembayaran</h3>
                <button onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="paymentForm" action="<?php echo e(route('admin.pembayaran.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="transaksi_id" id="modal_transaksi_id">
                <input type="hidden" name="tanggal_bayar" value="<?php echo e(date('Y-m-d')); ?>">
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">ID Transaksi</label>
                    <input type="text" id="modal_transaksi_code_display" class="w-full border border-slate-200 rounded-xl bg-slate-50 p-2.5 text-xs font-mono font-bold" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Tagihan</label>
                    <div class="flex items-center">
                        <span class="bg-slate-100 border border-slate-200 border-r-0 px-3 py-2.5 rounded-l-xl text-slate-500 font-bold text-xs">Rp</span>
                        <input type="text" id="modal_total_price_display" class="w-full border border-slate-200 rounded-r-xl bg-slate-50 p-2.5 text-xs font-bold text-slate-900" readonly>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Uang Pelanggan (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_bayar" id="modal_jumlah_bayar" required min="0"
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="metode_pembayaran" class="w-full border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer" required>
                        <option value="Tunai">Tunai (Cash)</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer BCA">Transfer BCA</option>
                        <option value="Transfer Mandiri">Transfer Mandiri</option>
                        <option value="Transfer BRI">Transfer BRI</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Bukti Transfer (Opsional)</label>
                    <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-xs font-bold transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                        <i class="fas fa-check"></i> Proses Pelunasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="showNotaModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="closeNotaModal()" class="bg-white shadow-2xl w-full max-w-[400px] mx-4 animate-fade-up overflow-hidden rounded-2xl flex flex-col h-[85vh]">
            <div class="px-5 py-4 flex items-center justify-between bg-[#0b172a] text-white shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fas fa-receipt text-emerald-400"></i>
                    <h3 class="font-bold text-xs tracking-widest uppercase">STRUK NOTA / INVOICE TRANSAKSI</h3>
                </div>
                <button @click="closeNotaModal()" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-hidden relative bg-slate-50">
                <template x-if="notaUrl">
                    <iframe x-ref="notaIframe" :src="notaUrl" class="w-full h-full border-0 absolute inset-0"></iframe>
                </template>
            </div>
            
            <div class="px-5 py-3.5 border-t border-slate-100 flex gap-2 justify-center bg-white shrink-0">
                <button @click="kirimWa()"
                        class="px-4 py-2 bg-[#25D366] text-white text-xs font-bold rounded-full hover:bg-[#128C7E] transition shadow-sm flex items-center gap-1.5 active:scale-95">
                    <i class="fab fa-whatsapp"></i> WA
                </button>
                <button @click="printNota()"
                        class="px-5 py-2 bg-black text-white text-xs font-bold rounded-full hover:bg-slate-800 transition shadow-sm flex items-center gap-1.5 active:scale-95">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button @click="closeNotaModal()"
                        class="px-5 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-full hover:bg-slate-200 transition active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function openPaymentModal(transaksiCode, totalPrice) {
        document.getElementById('modal_transaksi_id').value = transaksiCode;
        document.getElementById('modal_transaksi_code_display').value = transaksiCode;
        document.getElementById('modal_total_price_display').value = new Intl.NumberFormat('id-ID').format(totalPrice);
        
        let jumlahBayarInput = document.getElementById('modal_jumlah_bayar');
        jumlahBayarInput.value = totalPrice;
        jumlahBayarInput.min = totalPrice;
        
        document.getElementById('paymentModal').classList.remove('hidden');
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('pembayaranApp', () => ({
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pembayaran/index.blade.php ENDPATH**/ ?>