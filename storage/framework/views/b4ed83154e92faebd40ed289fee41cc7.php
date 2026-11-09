<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rincian Target Harian - <?php echo e($periodeJudul); ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.3;
            color: #1e293b;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
            color: #0f172a;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 11pt;
            margin: 2px 0 0 0;
            color: #0284c7;
            font-weight: bold;
        }
        .header p {
            font-size: 8.5pt;
            margin: 3px 0 0 0;
            color: #64748b;
        }
        .kpi-container {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .kpi-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 6px 10px;
            border-radius: 4px;
            text-align: center;
        }
        .kpi-title {
            font-size: 7.5pt;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
        }
        .kpi-value {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 2px;
            color: #0f172a;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        table.data-table th, 
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            vertical-align: middle;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 7.5pt;
        }
        table.data-table tr.libur {
            background-color: #f8fafc;
            color: #94a3b8;
        }
        table.data-table tr.today {
            background-color: #eff6ff;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }
        .text-emerald { color: #059669; }
        .text-rose { color: #e11d48; }
        .text-blue { color: #2563eb; }
        
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .badge-success { background-color: #d1fae5; color: #047857; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-danger { background-color: #ffe4e6; color: #e11d48; }
        .badge-muted { background-color: #f1f5f9; color: #64748b; }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .signature-space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- Header / Kop -->
    <div class="header">
        <h1>SMK NEGERI 1 CIAMIS &bull; TEFA PERHOTELAN</h1>
        <h2>LAPORAN RINCIAN TARGET HARIAN & REALISASI KEUANGAN</h2>
        <p>Unit Usaha: Bening Laundry &bull; Periode: <strong><?php echo e($periodeJudul); ?></strong> &bull; Dicetak: <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB</p>
    </div>

    <!-- Ringkasan KPI -->
    <table class="kpi-container">
        <tr>
            <td style="width: 16.6%; padding: 2px;">
                <div class="kpi-box">
                    <div class="kpi-title">Total Target Dasar</div>
                    <div class="kpi-value font-mono">Rp <?php echo e(number_format($totalBaseTarget, 0, ',', '.')); ?></div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 2px;">
                <div class="kpi-box">
                    <div class="kpi-title">Pemasukan</div>
                    <div class="kpi-value font-mono text-emerald">Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 2px;">
                <div class="kpi-box">
                    <div class="kpi-title">Pengeluaran</div>
                    <div class="kpi-value font-mono text-rose">Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 2px;">
                <div class="kpi-box">
                    <div class="kpi-title">Realisasi Bersih</div>
                    <div class="kpi-value font-mono text-blue">Rp <?php echo e(number_format($totalNetIncome, 0, ',', '.')); ?></div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 2px;">
                <div class="kpi-box">
                    <div class="kpi-title">Selisih Target</div>
                    <div class="kpi-value font-mono <?php echo e($totalVariance >= 0 ? 'text-emerald' : 'text-rose'); ?>">
                        <?php echo e($totalVariance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($totalVariance, 0, ',', '.')); ?>

                    </div>
                </div>
            </td>
            <td style="width: 17%; padding: 2px;">
                <div class="kpi-box">
                    <div class="kpi-title">Hari Kerja / Tercapai</div>
                    <div class="kpi-value"><?php echo e($achievedDays); ?> / <?php echo e($activeWorkdays); ?> Hari</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian Harian -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 13%;">Tanggal & Hari</th>
                <th style="width: 11%;">Target Dasar</th>
                <th style="width: 11%;">Defisit Kemarin</th>
                <th style="width: 11%;">Target Final</th>
                <th style="width: 11%;">Pemasukan</th>
                <th style="width: 11%;">Pengeluaran</th>
                <th style="width: 11%;">Realisasi Bersih</th>
                <th style="width: 11%;">Selisih</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $dailyTargets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isWorkday = $dt->is_workday;
            ?>
            <tr class="<?php echo e(!$isWorkday ? 'libur' : ($dt->date->isToday() ? 'today' : '')); ?>">
                <td class="text-left font-bold whitespace-nowrap">
                    <?php echo e($dt->date->translatedFormat('d M Y (D)')); ?>

                    <?php if(!$isWorkday): ?>
                        <span style="font-size: 6.5pt; color: #64748b;">(Libur)</span>
                    <?php endif; ?>
                </td>

                <td class="text-right font-mono">
                    <?php echo e($isWorkday ? 'Rp ' . number_format($dt->base_target, 0, ',', '.') : '-'); ?>

                </td>

                <td class="text-right font-mono <?php echo e($dt->carry_forward > 0 ? 'text-rose font-bold' : ''); ?>">
                    <?php echo e(($isWorkday && $dt->carry_forward > 0) ? 'Rp ' . number_format($dt->carry_forward, 0, ',', '.') : '-'); ?>

                </td>

                <td class="text-right font-mono font-bold text-blue">
                    <?php echo e($isWorkday ? 'Rp ' . number_format($dt->adjusted_target, 0, ',', '.') : '-'); ?>

                </td>

                <td class="text-right font-mono text-emerald">
                    Rp <?php echo e(number_format($dt->actual_income, 0, ',', '.')); ?>

                </td>

                <td class="text-right font-mono text-rose">
                    Rp <?php echo e(number_format($dt->actual_expense, 0, ',', '.')); ?>

                </td>

                <td class="text-right font-mono font-bold <?php echo e($dt->net_income >= $dt->adjusted_target && $isWorkday ? 'text-emerald' : 'text-blue'); ?>">
                    Rp <?php echo e(number_format($dt->net_income, 0, ',', '.')); ?>

                </td>

                <td class="text-right font-mono font-bold <?php echo e($dt->variance >= 0 ? 'text-emerald' : 'text-rose'); ?>">
                    <?php if($isWorkday): ?>
                        <?php echo e($dt->variance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($dt->variance, 0, ',', '.')); ?>

                    <?php else: ?>
                        <?php echo e($dt->net_income > 0 ? '+Rp ' . number_format($dt->net_income, 0, ',', '.') : '-'); ?>

                    <?php endif; ?>
                </td>

                <td class="text-center">
                    <?php if(!$isWorkday): ?>
                        <span class="badge badge-muted">Non-Operasional</span>
                    <?php elseif($dt->is_achieved): ?>
                        <span class="badge badge-success">Tercapai &#10003;</span>
                    <?php elseif($dt->status_color === 'yellow'): ?>
                        <span class="badge badge-warning">Mendekati</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Belum</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-right font-mono">Rp <?php echo e(number_format($totalBaseTarget, 0, ',', '.')); ?></td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-right font-mono text-emerald">Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></td>
                <td class="text-right font-mono text-rose">Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></td>
                <td class="text-right font-mono text-blue">Rp <?php echo e(number_format($totalNetIncome, 0, ',', '.')); ?></td>
                <td class="text-right font-mono <?php echo e($totalVariance >= 0 ? 'text-emerald' : 'text-rose'); ?>">
                    <?php echo e($totalVariance >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($totalVariance, 0, ',', '.')); ?>

                </td>
                <td class="text-center">
                    <?php echo e($totalBaseTarget > 0 ? round(($totalIncome / $totalBaseTarget) * 100, 1) . '%' : '-'); ?>

                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%; padding-left: 30px;">
                Mengetahui / Menyetujui,<br>
                <strong>Manajer / Ketua TEFA Perhotelan</strong>
                <div class="signature-space"></div>
                <strong>( ___________________________ )</strong><br>
                NIP. ....................................................
            </td>
            <td style="width: 50%; padding-left: 100px;">
                Ciamis, <?php echo e(now()->translatedFormat('d F Y')); ?><br>
                <strong>Bagian Administrasi Keuangan & Kasir</strong>
                <div class="signature-space"></div>
                <strong>( <?php echo e(auth()->user()->name ?? 'Petugas Administrasi'); ?> )</strong><br>
                NIP/ID. .................................................
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pdf\target_harian.blade.php ENDPATH**/ ?>