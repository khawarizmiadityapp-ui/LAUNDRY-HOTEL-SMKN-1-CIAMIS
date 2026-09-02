<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center; color: #1e3a8a;">
                UNIT PRODUKSI & JASA: BENING LAUNDRY HOTEL SMKN 1 CIAMIS
            </td>
        </tr>
        <tr>
            <td colspan="8" style="font-size: 12pt; font-weight: bold; text-align: center; color: #0f172a;">
                LAPORAN PEKERJAAN & KINERJA HARIAN PETUGAS PIKET
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; font-size: 10pt; color: #64748b;">
                Periode: <?php echo e($periodeJudul); ?> | Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?> WIB
            </td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Total Petugas Piket</th>
            <td colspan="2" style="font-weight: bold;"><?php echo e($stats['total_petugas']); ?> Petugas</td>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Total Tugas Selesai</th>
            <td colspan="2" style="font-weight: bold;"><?php echo e($stats['total_tasks']); ?> Tugas</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Total Bobot Cucian</th>
            <td colspan="2" style="font-weight: bold;"><?php echo e(number_format($stats['total_weight'], 1)); ?> Kg</td>
            <th colspan="2" style="background-color: #f1f5f9; font-weight: bold;">Output (Cuci/Setrika/Pack/Kasir)</th>
            <td colspan="2" style="font-weight: bold;"><?php echo e($stats['washing_count']); ?> / <?php echo e($stats['ironing_count']); ?> / <?php echo e($stats['packing_count']); ?> / <?php echo e($stats['kasir_count']); ?></td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="font-weight: bold; font-size: 11pt; background-color: #1e40af; color: #ffffff; text-align: left;">
                1. REKAPITULASI KINERJA PETUGAS
            </td>
        </tr>
        <tr style="background-color: #e2e8f0; font-weight: bold; text-align: center;">
            <th width="8">No</th>
            <th width="30">Nama Petugas / Siswa</th>
            <th width="20">Shift & Presensi</th>
            <th width="15">Washing</th>
            <th width="15">Ironing</th>
            <th width="15">Packing</th>
            <th width="15">Kasir</th>
            <th width="18">Total Output</th>
        </tr>
        <?php $__empty_1 = true; $__currentLoopData = $rekapPetugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                <td style="font-weight: bold;"><?php echo e($item['nama']); ?></td>
                <td style="text-align: center;"><?php echo e($item['shift']); ?> (<?php echo e(ucfirst($item['status_kehadiran'])); ?>)</td>
                <td style="text-align: center;"><?php echo e($item['washing_count']); ?></td>
                <td style="text-align: center;"><?php echo e($item['ironing_count']); ?></td>
                <td style="text-align: center;"><?php echo e($item['packing_count']); ?></td>
                <td style="text-align: center;"><?php echo e($item['kasir_count']); ?></td>
                <td style="text-align: center; font-weight: bold; background-color: #f8fafc;"><?php echo e($item['total_output']); ?> item</td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" style="text-align: center; color: #94a3b8;">Tidak ada data aktivitas petugas pada periode ini.</td>
            </tr>
        <?php endif; ?>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="font-weight: bold; font-size: 11pt; background-color: #047857; color: #ffffff; text-align: left;">
                2. RINCIAN LOG PEKERJAAN & TUGAS HARIAN
            </td>
        </tr>
        <tr style="background-color: #e2e8f0; font-weight: bold; text-align: center;">
            <th width="8">No</th>
            <th width="18">Waktu Selesai</th>
            <th width="25">Nama Petugas</th>
            <th width="16">Stasiun</th>
            <th width="20">Kode Transaksi</th>
            <th width="25">Nama Pelanggan</th>
            <th width="20">Berat / Layanan</th>
            <th width="16">Status</th>
        </tr>
        <?php $__empty_1 = true; $__currentLoopData = $taskLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                <td style="text-align: center;"><?php echo e($log['completed_at'] ? \Carbon\Carbon::parse($log['completed_at'])->translatedFormat('d/m/Y H:i') : '-'); ?></td>
                <td style="font-weight: bold;"><?php echo e($log['petugas_name'] ?? 'Petugas'); ?></td>
                <td style="text-align: center;"><?php echo e(strtoupper($log['stage'])); ?></td>
                <td style="text-align: center;"><?php echo e($log['transaksi_code']); ?></td>
                <td><?php echo e($log['customer_name']); ?></td>
                <td style="text-align: center;"><?php echo e($log['weight'] ? $log['weight'] . ' kg' : '-'); ?> (<?php echo e($log['layanan']); ?>)</td>
                <td style="text-align: center;"><?php echo e(ucfirst($log['status'])); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" style="text-align: center; color: #94a3b8;">Belum ada catatan log pekerjaan pada periode ini.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/exports/laporan_pekerjaan_petugas_excel.blade.php ENDPATH**/ ?>