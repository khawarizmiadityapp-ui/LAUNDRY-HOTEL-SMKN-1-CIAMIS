<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="mb-7 animate-fade-up">
    <h1 class="font-display text-2xl font-700 text-slate-900">Dashboard Overview</h1>
    <p class="text-sm text-slate-500 mt-1">Good morning, Admin. Here is what's happening today.</p>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-7 stagger">

    <?php echo $__env->make('components.stat-card', [
        'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'label'  => 'Total Transaksi',
        'value'  => number_format($stats['total_orders']),
        'sub'    => 'Semua pesanan masuk',
        'badge'  => $stats['orders_today'] . ' hari ini',
        'up'     => true,
        'color'  => 'blue',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('components.stat-card', [
        'icon'   => 'M12 6v12m-3-2.818.879.659c1.171.33 2.51-.645 2.51-1.857v-1a2 2 0 011.01-1.756l.291-.16c1.043-.614 1.043-2.07 0-2.684L13.51 9.24a2 2 0 01-1.01-1.756V6.5a1.5 1.5 0 013 0v.5',
        'label'  => 'Total Pendapatan',
        'value'  => 'Rp ' . number_format($stats['total_income'], 0, ',', '.'),
        'sub'    => 'Pemasukan lunas',
        'badge'  => 'Aktif',
        'up'     => true,
        'color'  => 'green',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('components.stat-card', [
        'icon'   => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
        'label'  => 'Total Pengeluaran',
        'value'  => 'Rp ' . number_format($stats['total_expense'], 0, ',', '.'),
        'sub'    => 'Biaya operasional',
        'badge'  => 'Bulan ini',
        'up'     => false,
        'color'  => 'red',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('components.stat-card', [
        'icon'   => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99',
        'label'  => 'Sedang Diproses',
        'value'  => $stats['processing'] . ' Orders',
        'sub'    => 'Menunggu tahap selesai',
        'color'  => 'purple',
        'progress' => ($stats['total_orders'] > 0) ? ($stats['processing'] / $stats['total_orders'] * 100) : 0,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>


<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-7">

    
    <div class="bg-white rounded-2xl shadow-card p-5 animate-fade-up" style="animation-delay:.1s">
        <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
            <div>
                <h2 class="font-display text-base font-700 text-slate-900">Grafik Pemasukan & Pengeluaran</h2>
                <p id="revenueChartSubtitle" class="text-xs text-slate-400 mt-0.5">Last 7 days performance</p>
            </div>
            <div class="flex items-center bg-slate-100 rounded-xl p-1 gap-0.5" id="chart-tabs">
                <?php $__currentLoopData = ['Daily','Weekly','Monthly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button onclick="switchTab('<?php echo e(strtolower($tab)); ?>', this)"
                            data-tab="<?php echo e(strtolower($tab)); ?>"
                            class="tab-btn text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-all
                                   <?php echo e($tab === 'Weekly' ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'); ?>">
                        <?php echo e($tab); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="flex items-center gap-5 mt-4">
            <span class="flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-block w-3 h-3 rounded-sm bg-brand-500"></span> Pemasukan
            </span>
            <span class="flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-block w-3 h-3 rounded-sm bg-rose-400"></span> Pengeluaran
            </span>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-card p-5 animate-fade-up" style="animation-delay:.15s">
        <div class="mb-5">
            <h2 class="font-display text-base font-700 text-slate-900">Grafik Transaksi</h2>
            <p id="transactionChartSubtitle" class="text-xs text-slate-400 mt-0.5">Jumlah transaksi 7 hari terakhir</p>
        </div>
        <div class="relative h-72">
            <canvas id="transactionChart"></canvas>
        </div>
        <div class="flex items-center gap-5 mt-4">
            <span class="flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-block w-3 h-3 rounded-sm bg-emerald-500"></span> Transaksi
            </span>
        </div>
    </div>

</div>


<div class="bg-white rounded-2xl shadow-card animate-fade-up" style="animation-delay:.2s">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-display text-base font-700 text-slate-900">Transaksi Terbaru</h2>
            <p class="text-xs text-slate-400 mt-0.5">10 pesanan laundry terakhir</p>
        </div>
        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition">
            Lihat Semua
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </div>

    <!-- Table wrapper -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Layanan</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Berat (kg)</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Harga</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50/60 transition-colors duration-100">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <?php $colors = ['from-brand-500 to-brand-700','from-violet-500 to-violet-700','from-rose-400 to-rose-600','from-teal-500 to-teal-700','from-amber-400 to-amber-600','from-emerald-500 to-emerald-700']; ?>
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br <?php echo e($colors[(int)$index % 6]); ?> flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                    <?php echo e(strtoupper(substr($trx->customer_name, 0, 1))); ?>

                                </div>
                                <div class="leading-none">
                                    <p class="font-medium text-slate-800"><?php echo e($trx->customer_name); ?></p>
                                    <p class="text-xs text-slate-400 mt-0.5">ID: <?php echo e($trx->transaksi_code); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">
                            <?php echo e(ucfirst($trx->service_type)); ?>

                        </td>
                        <td class="px-4 py-3.5 text-center text-slate-600"><?php echo e(number_format($trx->weight, 1)); ?></td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                <?php echo e($trx->status == 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-brand-50 text-brand-600'); ?>">
                                <?php echo e(ucfirst($trx->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-800">Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?></td>
                        <td class="px-6 py-3.5 text-right text-xs text-slate-400 whitespace-nowrap"><?php echo e($trx->created_at->format('M d, H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Load More -->
    <div class="border-t border-slate-100 px-6 py-4 flex justify-center">
        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition px-4 py-2 rounded-xl hover:bg-brand-50">
            Lihat Semua Transaksi
        </a>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-card p-6 animate-fade-up" style="animation-delay:.25s">
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h2 class="font-display text-lg font-700 text-slate-900">Layanan Paling Banyak Dipesan</h2>
            <p class="text-xs text-slate-400 mt-0.5">Top 10 layanan berdasarkan jumlah pesanan</p>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-brand-50 to-violet-50 rounded-lg border border-brand-100">
            <span class="inline-block w-2.5 h-2.5 rounded-sm bg-gradient-to-r from-brand-500 to-violet-500"></span>
            <span class="text-xs font-semibold text-brand-600">Jumlah Pesanan</span>
        </div>
    </div>
    <div class="relative bg-slate-50/50 rounded-xl p-5 border border-slate-100" style="min-height: 450px;">
        <canvas id="serviceChart"></canvas>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Chart.js Data (Dynamic) ──────────────────────────────────────────
const chartDataValues = <?php echo json_encode($chartData, 15, 512) ?>;
const serviceStatsData = <?php echo json_encode($serviceStats, 15, 512) ?>;

// ── Chart Init ─────────────────────────────────────────────
const ctx = document.getElementById('revenueChart').getContext('2d');
const transactionCtx = document.getElementById('transactionChart').getContext('2d');
const serviceCtx = document.getElementById('serviceChart').getContext('2d');

const tooltipOptions = {
    backgroundColor: '#1e293b',
    titleColor: '#94a3b8',
    bodyColor: '#f1f5f9',
    padding: 10,
    cornerRadius: 10,
    displayColors: true,
    boxWidth: 8,
    boxHeight: 8,
    boxPadding: 4,
};

let currentChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartDataValues.weekly.labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: chartDataValues.weekly.income,
                backgroundColor: 'rgba(53,104,244,.85)',
                hoverBackgroundColor: 'rgba(53,104,244,1)',
                borderRadius: { topLeft: 6, topRight: 6 },
                borderSkipped: false,
                barPercentage: .6,
                categoryPercentage: .7,
            },
            {
                label: 'Pengeluaran',
                data: chartDataValues.weekly.expense,
                backgroundColor: 'rgba(251,113,133,.75)',
                hoverBackgroundColor: 'rgba(244,63,94,1)',
                borderRadius: { topLeft: 6, topRight: 6 },
                borderSkipped: false,
                barPercentage: .6,
                categoryPercentage: .7,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: tooltipOptions
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { color: '#94a3b8', font: { size: 11 } }
            },
            y: {
                grid: { color: '#f1f5f9', drawBorder: false },
                border: { display: false, dash: [4,4] },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11 },
                    padding: 8,
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

let transactionChart = new Chart(transactionCtx, {
    type: 'line',
    data: {
        labels: chartDataValues.weekly.labels,
        datasets: [
            {
                label: 'Transaksi',
                data: chartDataValues.weekly.transactions,
                borderColor: 'rgba(16,185,129,1)',
                backgroundColor: 'rgba(16,185,129,.14)',
                fill: true,
                tension: .35,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#10b981',
                borderWidth: 2.5,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: tooltipOptions
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { color: '#94a3b8', font: { size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9', drawBorder: false },
                border: { display: false, dash: [4,4] },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11 },
                    padding: 8,
                    precision: 0,
                }
            }
        }
    }
});

// ── Service Chart (Horizontal Bar) ─────────────────────────────────────────────
const serviceLabels = serviceStatsData.map(s => s.service_name);
const serviceData = serviceStatsData.map(s => s.order_count);

// Create gradient colors for modern look
const serviceGradients = [
    { start: 'rgba(53, 104, 244, 0.85)', end: 'rgba(99, 102, 241, 0.85)' },
    { start: 'rgba(139, 92, 246, 0.85)', end: 'rgba(168, 85, 247, 0.85)' },
    { start: 'rgba(236, 72, 153, 0.85)', end: 'rgba(244, 63, 94, 0.85)' },
    { start: 'rgba(251, 146, 60, 0.85)', end: 'rgba(251, 191, 36, 0.85)' },
    { start: 'rgba(34, 197, 94, 0.85)', end: 'rgba(16, 185, 129, 0.85)' },
    { start: 'rgba(14, 165, 233, 0.85)', end: 'rgba(6, 182, 212, 0.85)' },
    { start: 'rgba(168, 85, 247, 0.85)', end: 'rgba(192, 132, 252, 0.85)' },
    { start: 'rgba(244, 63, 94, 0.85)', end: 'rgba(251, 113, 133, 0.85)' },
    { start: 'rgba(234, 179, 8, 0.85)', end: 'rgba(250, 204, 21, 0.85)' },
    { start: 'rgba(16, 185, 129, 0.85)', end: 'rgba(52, 211, 153, 0.85)' },
];

let serviceChart = new Chart(serviceCtx, {
    type: 'bar',
    data: {
        labels: serviceLabels,
        datasets: [{
            label: 'Jumlah Pesanan',
            data: serviceData,
            backgroundColor: serviceGradients.map((g, i) => {
                const ctx = serviceCtx;
                const gradient = ctx.createLinearGradient(0, 0, 500, 0);
                gradient.addColorStop(0, g.start);
                gradient.addColorStop(1, g.end);
                return gradient;
            }),
            hoverBackgroundColor: serviceGradients.map(g => g.end.replace('0.85', '0.95')),
            borderRadius: { topRight: 10, bottomRight: 10 },
            borderSkipped: false,
            barPercentage: 0.65,
            categoryPercentage: 0.85,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 0,
                right: 60,
                top: 10,
                bottom: 10
            }
        },
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: {
                ...tooltipOptions,
                callbacks: {
                    title: function(context) {
                        return context[0].label;
                    },
                    label: function(context) {
                        const service = serviceStatsData[context.dataIndex];
                        return [
                            `Pesanan: ${service.order_count}x`,
                            `Total Qty: ${parseFloat(service.total_qty).toFixed(1)}`,
                            `Revenue: Rp ${parseInt(service.total_revenue).toLocaleString('id-ID')}`
                        ];
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { 
                    color: '#e2e8f0',
                    drawBorder: false,
                    lineWidth: 1,
                },
                border: { display: false },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11, weight: '500' },
                    padding: 10,
                    precision: 0,
                }
            },
            y: {
                grid: { display: false },
                border: { display: false },
                ticks: { 
                    color: '#1e293b', 
                    font: { size: 13, weight: '600' },
                    padding: 15,
                    callback: function(value, index) {
                        const label = this.getLabelForValue(value);
                        // Truncate long labels
                        return label.length > 28 ? label.substring(0, 28) + '...' : label;
                    }
                }
            }
        }
    },
    plugins: [{
        afterDatasetsDraw: function(chart) {
            const ctx = chart.ctx;
            chart.data.datasets.forEach(function(dataset, i) {
                const meta = chart.getDatasetMeta(i);
                if (!meta.hidden) {
                    meta.data.forEach(function(element, index) {
                        // Draw the count at the end of each bar
                        ctx.fillStyle = '#475569';
                        ctx.font = 'bold 13px sans-serif';
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'middle';
                        
                        const dataString = dataset.data[index] + 'x';
                        const position = element.tooltipPosition();
                        ctx.fillText(dataString, position.x + 10, position.y);
                    });
                }
            });
        }
    }]
});

// ── Tab Switcher Logic ─────────────────────────────────────────────
window.switchTab = function(period, btnElement) {
    // Update active UI tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-brand-600', 'shadow-sm');
        btn.classList.add('text-slate-500', 'hover:text-slate-700');
    });
    btnElement.classList.remove('text-slate-500', 'hover:text-slate-700');
    btnElement.classList.add('bg-white', 'text-brand-600', 'shadow-sm');
    
    // Update chart subtitle
    const revenueSubtitle = document.getElementById('revenueChartSubtitle');
    const transactionSubtitle = document.getElementById('transactionChartSubtitle');
    if (period === 'daily') {
        revenueSubtitle.textContent = 'Today\'s performance by hour';
        transactionSubtitle.textContent = 'Jumlah transaksi hari ini per 2 jam';
    } else if (period === 'weekly') {
        revenueSubtitle.textContent = 'Last 7 days performance';
        transactionSubtitle.textContent = 'Jumlah transaksi 7 hari terakhir';
    } else if (period === 'monthly') {
        revenueSubtitle.textContent = 'Last 6 months performance';
        transactionSubtitle.textContent = 'Jumlah transaksi 6 bulan terakhir';
    }

    // Update chart data
    const newData = chartDataValues[period];
    if (newData) {
        currentChart.data.labels = newData.labels;
        currentChart.data.datasets[0].data = newData.income;
        currentChart.data.datasets[1].data = newData.expense;
        currentChart.update();

        transactionChart.data.labels = newData.labels;
        transactionChart.data.datasets[0].data = newData.transactions;
        transactionChart.update();
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>