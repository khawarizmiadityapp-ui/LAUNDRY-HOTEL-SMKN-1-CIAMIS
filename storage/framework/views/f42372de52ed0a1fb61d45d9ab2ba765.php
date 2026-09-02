<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .header-title { font-size: 16pt; font-weight: bold; text-align: center; color: #1e3a8a; }
        .header-subtitle { font-size: 11pt; text-align: center; color: #4b5563; }
        .section-header { font-size: 12pt; font-weight: bold; background-color: #1e40af; color: #ffffff; text-align: left; }
        .table-head { font-weight: bold; background-color: #e2e8f0; text-align: center; }
        .sub-total { font-weight: bold; background-color: #f1f5f9; }
        .grand-total { font-weight: bold; background-color: #dbeafe; font-size: 11pt; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>

    <!-- KOP &amp; HEADER LAPORAN NERACA -->
    <table>
        <tr>
            <td colspan="4" class="header-title">BENING LAUNDRY</td>
        </tr>
        <tr>
            <td colspan="4" class="header-subtitle">SMKN 1 CIAMIS - LAPORAN NERACA &amp; KEUANGAN</td>
        </tr>
        <tr>
            <td colspan="4" class="text-center" style="font-size: 10pt; color: #64748b;">
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
        <tr><td colspan="4"></td></tr>

        <!-- SUMMARY NERACA KEUANGAN -->
        <tr>
            <td colspan="4" class="section-header"> I. IKHTISAR KEUANGAN &amp; NERACA LABA RUGI</td>
        </tr>
        <tr class="table-head">
            <th width="35">Komponen Keuangan</th>
            <th width="20">Jumlah Transaksi</th>
            <th width="25">Nominal (Rp)</th>
            <th width="20">Persentase / Catatan</th>
        </tr>
        <tr>
            <td>Total Pendapatan / Pemasukan (Lunas)</td>
            <td class="text-center"><?php echo e(number_format($jumlahPemasukan, 0, ',', '.')); ?> Transaksi</td>
            <td class="text-right"><?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></td>
            <td class="text-center">100.00%</td>
        </tr>
        <tr>
            <td>Total Beban / Pengeluaran Operasional</td>
            <td class="text-center"><?php echo e(number_format($jumlahPengeluaran, 0, ',', '.')); ?> Item</td>
            <td class="text-right"><?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?></td>
            <td class="text-center"><?php echo e($totalPemasukan > 0 ? number_format(($totalPengeluaran / $totalPemasukan) * 100, 2) : 0); ?>% dari Pemasukan</td>
        </tr>
        <tr class="grand-total">
            <td class="text-bold">LABA BERSIH (SURPLUS NERACA)</td>
            <td class="text-center text-bold">-</td>
            <td class="text-right text-bold"><?php echo e(number_format($labaBersih, 0, ',', '.')); ?></td>
            <td class="text-center text-bold">Margin <?php echo e(number_format($marginLaba, 2)); ?>%</td>
        </tr>
        <tr><td colspan="4"></td></tr>

        <!-- POSISI AKTIVA &amp; PASIVA / NERACA RINGKAS -->
        <tr>
            <td colspan="4" class="section-header"> II. POSISI NERACA KAS &amp; EKUITAS</td>
        </tr>
        <tr class="table-head">
            <th colspan="2">AKTIVA (PENERIMAAN KAS)</th>
            <th colspan="2">PASIVA &amp; EKUITAS (BEBAN &amp; LABA DITAHAN)</th>
        </tr>
        <tr>
            <td class="text-bold">Kas Penerimaan Transaksi:</td>
            <td class="text-right">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></td>
            <td class="text-bold">Kewajiban Pengeluaran Operasional:</td>
            <td class="text-right">Rp <?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?></td>
        </tr>
        <tr>
            <td class="text-bold">Rata-rata Nilai Transaksi:</td>
            <td class="text-right">Rp <?php echo e(number_format($rataRataPemasukan, 0, ',', '.')); ?></td>
            <td class="text-bold">Ekuitas Laba Bersih Operasional:</td>
            <td class="text-right">Rp <?php echo e(number_format($labaBersih, 0, ',', '.')); ?></td>
        </tr>
        <tr class="sub-total">
            <td class="text-bold">TOTAL AKTIVA KAS</td>
            <td class="text-right text-bold">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></td>
            <td class="text-bold">TOTAL PASIVA &amp; EKUITAS</td>
            <td class="text-right text-bold">Rp <?php echo e(number_format($totalPengeluaran + $labaBersih, 0, ',', '.')); ?></td>
        </tr>
        <tr><td colspan="4"></td></tr>

        <!-- POSISI UTANG &amp; PIUTANG -->
        <tr>
            <td colspan="4" class="section-header"> III. POSISI UTANG &amp; PIUTANG</td>
        </tr>
        <tr class="table-head">
            <th colspan="2">PIUTANG (BELUM DITERIMA)</th>
            <th colspan="2">UTANG (BELUM DIBAYAR)</th>
        </tr>
        <tr>
            <td class="text-bold">Total Transaksi Belum Lunas (Piutang):</td>
            <td class="text-right">Rp <?php echo e(number_format($totalPiutang ?? 0, 0, ',', '.')); ?></td>
            <td class="text-bold">Total Kewajiban (Utang):</td>
            <td class="text-right">Rp <?php echo e(number_format($totalUtang ?? 0, 0, ',', '.')); ?></td>
        </tr>
        <tr>
            <td class="text-bold">Jumlah Transaksi Piutang:</td>
            <td class="text-right"><?php echo e($jumlahPiutang ?? 0); ?> Transaksi</td>
            <td class="text-bold">Jumlah Transaksi Utang:</td>
            <td class="text-right"><?php echo e($jumlahUtang ?? 0); ?> Transaksi</td>
        </tr>
        <tr><td colspan="4"></td></tr>

        <!-- RINCIAN PENGELUARAN PER KATEGORI -->
        <tr>
            <td colspan="4" class="section-header"> IV. RINCIAN BIAYA &amp; PENGELUARAN PER KATEGORI</td>
        </tr>
        <tr class="table-head">
            <th>No</th>
            <th colspan="2">Kategori Pengeluaran</th>
            <th>Total Nominal (Rp)</th>
        </tr>
        <?php $__empty_1 = true; $__currentLoopData = $distribusiPengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td colspan="2"><?php echo e($kat['kategori']); ?></td>
                <td class="text-right"><?php echo e(number_format($kat['total'], 0, ',', '.')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4" class="text-center" style="color: #94a3b8;">Tidak ada data pengeluaran pada periode ini.</td>
            </tr>
        <?php endif; ?>
        <tr class="sub-total">
            <td colspan="3" class="text-bold text-center">TOTAL BEBAN PENGELUARAN</td>
            <td class="text-right text-bold"><?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?></td>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"></td></tr>

        <!-- LEMBAR PENGESAHAN -->
        <tr>
            <td colspan="2"></td>
            <td colspan="2" class="text-center">Ciamis, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2" class="text-center">Mengetahui / Menyetujui,</td>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"></td></tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2" class="text-center text-bold" style="text-decoration: underline;">Pimpinan / Manager Bening Laundry</td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/exports/neraca_excel.blade.php ENDPATH**/ ?>