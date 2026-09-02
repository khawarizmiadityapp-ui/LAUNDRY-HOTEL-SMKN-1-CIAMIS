<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .header-title { font-size: 14pt; font-weight: bold; text-align: center; color: #1e3a8a; }
        .table-head { font-weight: bold; background-color: #e2e8f0; text-align: center; }
        .sub-total { font-weight: bold; background-color: #dbeafe; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="8" class="header-title">RINCIAN TRANSAKSI PEMASUKAN LAUNDRY</td>
        </tr>
        <tr>
            <td colspan="8" class="text-center" style="font-size: 10pt; color: #64748b;">
                Periode: 
                <?php if($filter == 'bulanan'): ?>
                    Bulan <?php echo e(\Carbon\Carbon::now()->translatedFormat('F Y')); ?>

                <?php elseif($filter == 'tahunan'): ?>
                    Tahun <?php echo e(\Carbon\Carbon::now()->year); ?>

                <?php elseif($filter == 'custom'): ?>
                    <?php echo e($dari ? \Carbon\Carbon::parse($dari)->format('d/m/Y') : '-'); ?> s/d <?php echo e($sampai ? \Carbon\Carbon::parse($sampai)->format('d/m/Y') : '-'); ?>

                <?php else: ?>
                    Semua Periode
                <?php endif; ?>
            </td>
        </tr>
        <tr><td colspan="8"></td></tr>

        <tr class="table-head">
            <th width="18">Kode Transaksi</th>
            <th width="25">Nama Pelanggan</th>
            <th width="20">Tipe Layanan</th>
            <th width="15">Berat (kg)</th>
            <th width="20">Total Harga (Rp)</th>
            <th width="18">Status Pengerjaan</th>
            <th width="18">Status Pembayaran</th>
            <th width="20">Tanggal</th>
        </tr>
        <?php $grandTotal = 0; ?>
        <?php $__empty_1 = true; $__currentLoopData = $pemasukanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $grandTotal += $trx->total_price; ?>
            <tr>
                <td class="text-center"><?php echo e($trx->transaksi_code); ?></td>
                <td><?php echo e($trx->customer_name); ?></td>
                <td><?php echo e(ucfirst($trx->service_type)); ?></td>
                <td class="text-center"><?php echo e($trx->weight); ?> kg</td>
                <td class="text-right"><?php echo e(number_format($trx->total_price, 0, ',', '.')); ?></td>
                <td class="text-center"><?php echo e(ucfirst($trx->status)); ?></td>
                <td class="text-center"><?php echo e(str_replace('_', ' ', ucfirst($trx->payment_status))); ?></td>
                <td class="text-center"><?php echo e($trx->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center" style="color: #94a3b8;">Tidak ada data pemasukan pada periode ini.</td>
            </tr>
        <?php endif; ?>
        
        <tr class="sub-total">
            <td colspan="4" class="text-center" style="font-weight: bold;">TOTAL PEMASUKAN</td>
            <td class="text-right" style="font-weight: bold;"><?php echo e(number_format($grandTotal, 0, ',', '.')); ?></td>
            <td colspan="3"></td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/exports/pemasukan_excel.blade.php ENDPATH**/ ?>