<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .header-title { font-size: 14pt; font-weight: bold; text-align: center; color: #b91c1c; }
        .table-head { font-weight: bold; background-color: #fee2e2; text-align: center; }
        .sub-total { font-weight: bold; background-color: #fecaca; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="6" class="header-title">RINCIAN BEBAN &amp; PENGELUARAN OPERASIONAL</td>
        </tr>
        <tr>
            <td colspan="6" class="text-center" style="font-size: 10pt; color: #64748b;">
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
        <tr><td colspan="6"></td></tr>

        <tr class="table-head">
            <th width="15">ID Transaksi</th>
            <th width="25">Kategori</th>
            <th width="30">Nama / Keterangan Pengeluaran</th>
            <th width="20">Nominal (Rp)</th>
            <th width="18">Tanggal</th>
            <th width="20">Detail Keterangan</th>
        </tr>
        <?php $grandTotal = 0; ?>
        <?php $__empty_1 = true; $__currentLoopData = $pengeluaranData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $grandTotal += $item->nominal; ?>
            <tr>
                <td class="text-center"><?php echo e($item->id_transaksi ?? 'EXP-'.$item->id); ?></td>
                <td><?php echo e($item->kategori_nama); ?></td>
                <td><?php echo e($item->nama ?? '-'); ?></td>
                <td class="text-right"><?php echo e(number_format($item->nominal, 0, ',', '.')); ?></td>
                <td class="text-center"><?php echo e($item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : $item->created_at->format('d/m/Y')); ?></td>
                <td><?php echo e($item->keterangan ?? '-'); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center" style="color: #94a3b8;">Tidak ada data pengeluaran pada periode ini.</td>
            </tr>
        <?php endif; ?>
        
        <tr class="sub-total">
            <td colspan="3" class="text-center" style="font-weight: bold;">TOTAL PENGELUARAN</td>
            <td class="text-right" style="font-weight: bold;"><?php echo e(number_format($grandTotal, 0, ',', '.')); ?></td>
            <td colspan="2"></td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/exports/pengeluaran_excel.blade.php ENDPATH**/ ?>