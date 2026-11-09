<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapan Pembayaran Harian - <?php echo e($tanggalFormatted); ?></title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; line-height: 1.4; color: #1a202c; }
        .header { text-align: center; border-bottom: 2.5px double #1a202c; padding-bottom: 8px; margin-bottom: 15px; }
        .header h3 { margin: 0; font-size: 11px; font-weight: normal; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 14px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .header h1 { margin: 2px 0; font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 9px; color: #4a5568; }
        .title-box { text-align: center; margin-bottom: 15px; }
        .title-box h2 { margin: 0; font-size: 13px; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p { margin: 3px 0 0 0; font-size: 10px; color: #4a5568; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9.5px; }
        th, td { border: 1px solid #718096; padding: 5px 6px; vertical-align: middle; }
        th { background-color: #edf2f7; font-weight: bold; text-align: center; font-size: 9px; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier', monospace; }
        .font-bold { font-weight: bold; }

        .section-title { font-size: 11px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; color: #1e3a8a; }

        .summary-grid { width: 100%; border-collapse: collapse; border: none; margin-bottom: 15px; }
        .summary-grid td { border: none; padding: 4px; }
        .stat-card { border: 1px solid #cbd5e0; padding: 8px; background: #f8fafc; border-radius: 4px; }
        .stat-card span { font-size: 8.5px; color: #64748b; text-transform: uppercase; display: block; }
        .stat-card strong { font-size: 12px; color: #0f172a; }

        .signature-container { margin-top: 25px; width: 100%; }
        .signature-table { width: 100%; border-collapse: collapse; border: none; }
        .signature-table td { border: none; text-align: center; font-size: 9.5px; }
        .signature-space { height: 50px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    
    <div class="header">
        <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h2>SMK NEGERI 1 CIAMIS</h2>
        <h1>UNIT PRODUKSI & JASA: BENING LAUNDRY HOTEL</h1>
        <p>Jl. Jenderal Sudirman No. 269 Ciamis 46211 | Telp. (0265) 771204 | Email: beninglaundry@smkn1ciamis.sch.id</p>
    </div>

    
    <div class="title-box">
        <h2>REKAPAN PEMBAYARAN & PELAYANAN HARIAN</h2>
        <p><strong>Hari / Tanggal:</strong> <?php echo e($tanggalFormatted); ?></p>
    </div>

    
    <table class="summary-grid">
        <tr>
            <td style="width: 20%;">
                <div class="stat-card">
                    <span>Total Omzet Lunas</span>
                    <strong>Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></strong>
                </div>
            </td>
            <td style="width: 20%;">
                <div class="stat-card">
                    <span>Pembayaran Tunai</span>
                    <strong>Rp <?php echo e(number_format($totalTunai, 0, ',', '.')); ?></strong>
                </div>
            </td>
            <td style="width: 20%;">
                <div class="stat-card">
                    <span>Pembayaran QRIS</span>
                    <strong>Rp <?php echo e(number_format($totalQris ?? 0, 0, ',', '.')); ?></strong>
                </div>
            </td>
            <td style="width: 20%;">
                <div class="stat-card">
                    <span>Transfer Bank</span>
                    <strong>Rp <?php echo e(number_format($totalTransfer ?? 0, 0, ',', '.')); ?></strong>
                </div>
            </td>
            <td style="width: 20%;">
                <div class="stat-card">
                    <span>Belum Lunas (Piutang)</span>
                    <strong>Rp <?php echo e(number_format($rekapMetode['belum_bayar_total'] ?? 0, 0, ',', '.')); ?></strong>
                </div>
            </td>
        </tr>
    </table>

    
    <?php if(isset($rekapMetode)): ?>
    <div class="section-title">1. Rekapan Metode Pembayaran & Status Pelunasan</div>
    <table>
        <thead>
            <tr>
                <th style="width: 35%;">Metode / Status Pembayaran</th>
                <th style="width: 20%;">Jumlah Transaksi</th>
                <th style="width: 20%;">Persentase Omzet Lunas</th>
                <th style="width: 25%;">Total Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Pembayaran Tunai (Cash)</td>
                <td class="text-center"><?php echo e($rekapMetode['tunai_count']); ?> Transaksi</td>
                <td class="text-center"><?php echo e($rekapMetode['tunai_persen']); ?>%</td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($rekapMetode['tunai_total'], 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="font-bold">Pembayaran QRIS (Scan Barcode)</td>
                <td class="text-center"><?php echo e($rekapMetode['qris_count']); ?> Transaksi</td>
                <td class="text-center"><?php echo e($rekapMetode['qris_persen']); ?>%</td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($rekapMetode['qris_total'], 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="font-bold">Transfer Bank (BCA / Mandiri / BRI)</td>
                <td class="text-center"><?php echo e($rekapMetode['transfer_count']); ?> Transaksi</td>
                <td class="text-center"><?php echo e($rekapMetode['transfer_persen']); ?>%</td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($rekapMetode['transfer_total'], 0, ',', '.')); ?></td>
            </tr>
            <tr style="background-color: #edf2f7; font-weight: bold;">
                <td>TOTAL PENERIMAAN LUNAS</td>
                <td class="text-center"><?php echo e($rekapMetode['lunas_count']); ?> Transaksi</td>
                <td class="text-center">100%</td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($rekapMetode['lunas_total'], 0, ',', '.')); ?></td>
            </tr>
            <?php if(($rekapMetode['belum_bayar_count'] ?? 0) > 0): ?>
            <tr style="background-color: #fff5f5;">
                <td class="font-bold" style="color: #9b2c2c;">Tagihan Belum Lunas (Piutang)</td>
                <td class="text-center" style="color: #9b2c2c;"><?php echo e($rekapMetode['belum_bayar_count']); ?> Transaksi</td>
                <td class="text-center" style="color: #9b2c2c;">Piutang</td>
                <td class="text-right font-mono font-bold" style="color: #9b2c2c;">Rp <?php echo e(number_format($rekapMetode['belum_bayar_total'], 0, ',', '.')); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>

    
    <div class="section-title">2. Rincian Pelayanan Laundry Hari Ini</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Layanan Laundry</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Total Qty / Berat</th>
                <th style="width: 15%;">Jumlah Order</th>
                <th style="width: 15%;">Total Penerimaan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php $__empty_1 = true; $__currentLoopData = $serviceBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($no++); ?></td>
                <td class="font-bold"><?php echo e($srv['nama']); ?></td>
                <td class="text-center uppercase" style="font-size: 8.5px;"><?php echo e($srv['kategori']); ?></td>
                <td class="text-center font-mono"><?php echo e($srv['qty']); ?> <?php echo e($srv['kategori'] == 'kiloan' ? 'kg' : 'pcs'); ?></td>
                <td class="text-center"><?php echo e($srv['count']); ?>x</td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($srv['total'], 0, ',', '.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #718096;">Tidak ada transaksi layanan pada hari ini.</td>
            </tr>
            <?php endif; ?>
            <tr style="background-color: #edf2f7; font-weight: bold;">
                <td colspan="5" class="text-center">TOTAL PENERIMAAN LAYANAN</td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></td>
            </tr>
        </tbody>
    </table>

    
    <div class="section-title" style="margin-top: 15px;">3. Daftar Transaksi & Pelunasan Pelanggan</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No. Transaksi</th>
                <th style="width: 25%;">Pelanggan</th>
                <th style="width: 25%;">Rincian Layanan</th>
                <th style="width: 15%;">Metode</th>
                <th style="width: 15%;">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <?php $noTrx = 1; ?>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($noTrx++); ?></td>
                <td class="text-center font-mono" style="font-size: 8.5px;"><?php echo e($trx->transaksi_code); ?></td>
                <td>
                    <strong><?php echo e($trx->customer_name); ?></strong>
                    <div style="font-size: 8px; color: #64748b;"><?php echo e($trx->customer_phone ?: '-'); ?></div>
                </td>
                <td>
                    <?php if($trx->details && $trx->details->count() > 0): ?>
                        <?php echo e($trx->details->map(fn($d) => ($d->layanan->nama ?? 'Layanan') . ' (' . $d->qty . 'x)')->join(', ')); ?>

                    <?php else: ?>
                        <?php echo e(ucfirst($trx->service_type)); ?> (<?php echo e($trx->weight); ?> kg)
                    <?php endif; ?>
                </td>
                <td class="text-center uppercase" style="font-size: 8.5px;">
                    <?php echo e(str_replace('_', ' ', $trx->payment_method ?: 'Tunai')); ?>

                </td>
                <td class="text-right font-mono font-bold">Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #718096;">Belum ada transaksi pada tanggal ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 45%;">
                    Mengetahui,<br>
                    <strong>Kepala Program Keahlian / Unit Usaha</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">Dra. Hj. Nunung Rohanah, M.Pd.</div>
                    <div>NIP. 19680512 199303 2 004</div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    Ciamis, <?php echo e($tanggalFormatted); ?><br>
                    <strong>Kasir / Petugas Penerima Pembayaran</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name"><?php echo e(auth()->user()->name ?? 'Kasir Laundry'); ?></div>
                    <div>Unit Laundry Hotel SMKN 1 Ciamis</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pdf\rekap_pembayaran_harian.blade.php ENDPATH**/ ?>