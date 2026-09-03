<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Pengajuan Belanja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        .header {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 12pt;
        }
        .header-line {
            border-bottom: 2px solid #000;
            margin-bottom: 2px;
        }
        .header-line-thin {
            border-bottom: 1px solid #000;
            margin-bottom: 20px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            font-size: 12pt;
            text-transform: uppercase;
        }
        .content {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border: none;
        }
        .signature-table td {
            border: none;
            padding: 0;
            text-align: center;
            vertical-align: bottom;
            height: 100px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        BENING LAUNDRY<br>
        SMKN 1 CIAMIS<br>
        <span style="font-weight: normal; font-size: 10pt;">
            Jl. Jenderal Sudirman Nomor : 269<br>
            Ciamis – 46215
        </span>
    </div>
    
    <div class="header-line"></div>
    <div class="header-line-thin"></div>

    <div class="title">
        TEACHING FACTORY (TEFA) PERHOTELAN<br>
        FORMULIR PENGAJUAN BELANJA<br>
        <?php echo e(strtoupper($pengajuan->tanggal_pengajuan->translatedFormat('F Y'))); ?>

    </div>

    <div class="content">
        <p>Kepada Yth.<br>
        Bendahara Pengeluaran BLUD<br>
        SMKN 1 Ciamis<br>
        di Tempat</p>

        <p>Dengan hormat,<br>
        Bersama ini kami mengajukan kebutuhan belanja untuk kegiatan Teaching Factory (TEFA) sebagai berikut:</p>

        <?php if(count($items) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Qty</th>
                    <th width="35%">Nama Barang/Bahan</th>
                    <th width="20%">Harga Satuan (Rp)</th>
                    <th width="20%">Jumlah (Rp)</th>
                    <th width="10%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td class="text-center"><?php echo e($item['qty']); ?></td>
                    <td><?php echo e($item['nama']); ?></td>
                    <td class="text-right"><?php echo e($item['harga']); ?></td>
                    <td class="text-right"><?php echo e($item['total']); ?></td>
                    <td></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="total-row">
                    <td colspan="4" class="text-right">Total Pengajuan : </td>
                    <td class="text-right">Rp. <?php echo e(number_format($pengajuan->estimasi_biaya, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
        <div style="border: 1px solid #ccc; padding: 15px; margin: 20px 0; font-family: monospace; white-space: pre-wrap;"><?php echo e($pengajuan->alasan); ?></div>
        <div style="font-weight: bold; margin-bottom: 20px;">
            Total Pengajuan : Rp. <?php echo e(number_format($pengajuan->estimasi_biaya, 0, ',', '.')); ?>

        </div>
        <?php endif; ?>

        <p>Demikian pengajuan ini kami sampaikan. Atas perhatian dan persetujuannya kami ucapkan terima kasih.</p>

        <table class="signature-table">
            <tr>
                <td width="33%">
                    Ketua TEFA
                    <br><br><br><br><br>
                    <span class="signature-name">Dini Yudi Kasimamora, S.Tr.Par</span><br>
                    NIP. 19930311 2020122 022
                </td>
                <td width="33%">
                    Bendahara Pengeluaran BLUD
                    <br><br><br><br><br>
                    <span class="signature-name">Cucu Syamsudin, S.Kom.</span><br>
                    NIP. 19790128 201408 1 001
                </td>
                <td width="33%">
                    Mengetahui,<br>
                    Kepala Sekolah
                    <br><br><br><br>
                    <span class="signature-name">H. Cepy Wahyudin, A.Md., S.Kom., M.Kom.</span><br>
                    NIP. 19840825 201001 1 010
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
<?php /**PATH E:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pengajuan_belanja/pdf.blade.php ENDPATH**/ ?>