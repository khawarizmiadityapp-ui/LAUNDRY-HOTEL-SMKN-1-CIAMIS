<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Laporan Keuangan</h1>
    <p class="text-slate-500">Rekapitulasi pemasukan laundry.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 text-white shadow-lg">
        <p class="text-blue-100 text-sm font-medium mb-1">Pemasukan Hari Ini</p>
        <h2 class="text-3xl font-bold">Rp 1.250.000</h2>
        <div class="mt-4 flex items-center gap-2 text-sm text-blue-200">
            <svg class="w-4 h-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            <span>+12% dari kemarin</span>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <p class="text-slate-500 text-sm font-medium mb-1">Pemasukan Bulan Ini</p>
        <h2 class="text-3xl font-bold text-slate-800">Rp 15.400.000</h2>
        <div class="mt-4 w-full bg-slate-100 rounded-full h-2">
            <div class="bg-blue-500 h-2 rounded-full" style="width: 70%"></div>
        </div>
        <p class="text-xs text-slate-400 mt-2">Target tercapai 70%</p>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 flex flex-col justify-center items-center gap-3">
        <p class="text-slate-500 text-sm font-medium">Export Data</p>
        <div class="flex gap-2 w-full">
            <button class="flex-1 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 text-sm font-medium inline-flex items-center justify-center gap-1"><svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> PDF</button>
            <button class="flex-1 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 text-sm font-medium inline-flex items-center justify-center gap-1"><svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Excel</button>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="font-bold text-slate-800">Riwayat Transaksi</h3>
    </div>
    <table class="w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-600 font-semibold">
            <tr>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Keterangan</th>
                <th class="px-6 py-3">Metode</th>
                <th class="px-6 py-3 text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <tr>
                <td class="px-6 py-4 text-slate-500">24 Okt 2023</td>
                <td class="px-6 py-4 font-medium text-slate-700">Pembayaran Pesanan #TRX-001</td>
                <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs">Tunai</span></td>
                <td class="px-6 py-4 text-right font-bold text-green-600">+ Rp 21.000</td>
            </tr>
            <tr>
                <td class="px-6 py-4 text-slate-500">23 Okt 2023</td>
                <td class="px-6 py-4 font-medium text-slate-700">Pembayaran Pesanan #TRX-099</td>
                <td class="px-6 py-4"><span class="px-2 py-1 bg-purple-50 text-purple-600 rounded text-xs">QRIS</span></td>
                <td class="px-6 py-4 text-right font-bold text-green-600">+ Rp 45.000</td>
            </tr>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\repost.blade.php ENDPATH**/ ?>