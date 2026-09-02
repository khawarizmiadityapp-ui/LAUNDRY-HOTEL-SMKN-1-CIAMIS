<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Habis Pakai (BHP) - Laundry Hotel SMKN 1 Ciamis</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1a202c;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px double #1a202c;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h3 { margin: 0; font-size: 11px; font-weight: normal; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 14px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .header h1 { margin: 2px 0; font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 9px; color: #4a5568; }
        .title-box { text-align: center; margin-bottom: 15px; }
        .title-box h2 { margin: 0; font-size: 13px; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p { margin: 3px 0 0 0; font-size: 10px; color: #4a5568; }
        
        table.bhp-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 9.5px;
        }
        table.bhp-table th, table.bhp-table td {
            border: 1px solid #718096;
            padding: 6px 7px;
            vertical-align: middle;
        }
        table.bhp-table th {
            background-color: #edf2f7;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier', monospace; }
        .font-bold { font-weight: bold; }

        .badge-aman { color: #15803d; font-weight: bold; }
        .badge-menipis { color: #b45309; font-weight: bold; }
        .badge-kritis { color: #b91c1c; font-weight: bold; }

        .summary-box {
            margin-top: 15px;
            border: 1px solid #cbd5e0;
            padding: 8px 12px;
            background-color: #f8fafc;
            border-radius: 4px;
            width: 45%;
        }

        .signature-container {
            margin-top: 25px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .signature-table td {
            border: none;
            text-align: center;
            font-size: 9.5px;
        }
        .signature-space { height: 55px; }
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
        <h2>LAPORAN BARANG HABIS PAKAI (BHP)</h2>
        <p><strong>Posisi Data Per:</strong> <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB</p>
    </div>

    
    <table class="bhp-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Barang Habis Pakai</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 12%;">Satuan Kemasan</th>
                <th style="width: 13%;">Kapasitas/Unit</th>
                <th style="width: 15%;">Sisa Stok Fisik</th>
                <th style="width: 15%;">Status Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $minStock = $item->minimum_stock ?? 5;
                $statusClass = 'badge-aman';
                $statusLabel = 'Aman';
                
                if ($item->stock_units <= $minStock) {
                    $statusClass = 'badge-kritis';
                    $statusLabel = 'Kritis / Menipis';
                } elseif ($item->stock_units <= ($minStock * 2)) {
                    $statusClass = 'badge-menipis';
                    $statusLabel = 'Perlu Belanja';
                }
            ?>
            <tr>
                <td class="text-center"><?php echo e($no++); ?></td>
                <td class="font-bold"><?php echo e($item->name); ?></td>
                <td class="text-center uppercase" style="font-size: 8.5px;"><?php echo e($item->category); ?></td>
                <td class="text-center capitalize"><?php echo e($item->unit_type); ?></td>
                <td class="text-center font-mono">
                    <?php echo e($item->capacity_per_unit); ?> <?php echo e($item->unit_of_measurement); ?>

                </td>
                <td class="text-center font-mono font-bold">
                    <?php echo e($item->stock_units); ?> <?php echo e($item->unit_type); ?>

                    <?php if($item->unit_of_measurement !== 'pcs' && $item->stock_subunits > 0): ?>
                        <div style="font-size: 8px; color: #64748b;">+<?php echo e($item->stock_subunits); ?> <?php echo e($item->unit_of_measurement); ?> aktif</div>
                    <?php endif; ?>
                </td>
                <td class="text-center <?php echo e($statusClass); ?>">
                    <?php echo e($statusLabel); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px; color: #718096;">
                    Belum ada data barang habis pakai.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <div class="summary-box">
        <strong style="text-decoration: underline;">RINGKASAN STATUS BHP:</strong>
        <table style="width: 100%; margin-top: 4px; font-size: 9px;">
            <tr>
                <td>Total Jenis Barang Habis Pakai</td>
                <td>: <strong><?php echo e($totalJenis); ?> Jenis</strong></td>
            </tr>
            <tr>
                <td>Total Kemasan / Unit Tersedia</td>
                <td>: <strong><?php echo e($totalUnits); ?> Unit/Pcs</strong></td>
            </tr>
            <tr>
                <td>Item Berstatus Kritis/Habis</td>
                <td>: <strong style="color: #b91c1c;"><?php echo e($itemKritis); ?> Item (Harus Segera Dibelanjakan)</strong></td>
            </tr>
        </table>
    </div>

    
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 45%;">
                    Mengetahui,<br>
                    <strong>Kepala Program / Penanggung Jawab Unit</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name">Dra. Hj. Nunung Rohanah, M.Pd.</div>
                    <div>NIP. 19680512 199303 2 004</div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    Ciamis, <?php echo e(now()->translatedFormat('d F Y')); ?><br>
                    <strong>Pengelola Logistik & Inventory BHP</strong>
                    <div class="signature-space"></div>
                    <div class="signature-name"><?php echo e(auth()->user()->name ?? 'Petugas Logistik'); ?></div>
                    <div>Unit Laundry Hotel SMKN 1 Ciamis</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pdf/bhp.blade.php ENDPATH**/ ?>