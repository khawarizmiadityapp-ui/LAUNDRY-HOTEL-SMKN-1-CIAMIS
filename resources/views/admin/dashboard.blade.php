{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- ── Page Header ─────────────────────────────── --}}
<div class="mb-7 animate-fade-up">
    <h1 class="font-display text-2xl font-700 text-slate-900">Dashboard Overview</h1>
    <p class="text-sm text-slate-500 mt-1">Good morning, Admin. Here is what's happening today.</p>
</div>

{{-- ── Stat Cards ───────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-7 stagger">

    @include('components.stat-card', [
        'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'label'  => 'Total Transaksi',
        'value'  => '1,284',
        'sub'    => 'Last 30 days performance',
        'badge'  => '+12.5%',
        'up'     => true,
        'color'  => 'blue',
    ])

    @include('components.stat-card', [
        'icon'   => 'M12 6v12m-3-2.818.879.659c1.171.33 2.51-.645 2.51-1.857v-1a2 2 0 011.01-1.756l.291-.16c1.043-.614 1.043-2.07 0-2.684L13.51 9.24a2 2 0 01-1.01-1.756V6.5a1.5 1.5 0 013 0v.5',
        'label'  => 'Total Pendapatan',
        'value'  => 'Rp 42.5M',
        'sub'    => 'Revenue after service fees',
        'badge'  => '+8.2%',
        'up'     => true,
        'color'  => 'green',
    ])

    @include('components.stat-card', [
        'icon'   => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
        'label'  => 'Total Pengeluaran',
        'value'  => 'Rp 12.8M',
        'sub'    => 'Operational & materials cost',
        'badge'  => '-2.4%',
        'up'     => false,
        'color'  => 'red',
    ])

    @include('components.stat-card', [
        'icon'   => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99',
        'label'  => 'Sedang Diproses',
        'value'  => '42 Orders',
        'sub'    => '65% of total capacity',
        'color'  => 'purple',
        'progress' => 65,
    ])

</div>

{{-- ── Chart + Promo ────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">

    {{-- Chart Card --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-card p-5 animate-fade-up" style="animation-delay:.1s">
        <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
            <div>
                <h2 class="font-display text-base font-700 text-slate-900">Grafik Pemasukan & Pengeluaran</h2>
                <p class="text-xs text-slate-400 mt-0.5">Weekly performance analysis</p>
            </div>
            <div class="flex items-center bg-slate-100 rounded-xl p-1 gap-0.5" id="chart-tabs">
                @foreach(['Daily','Weekly','Monthly'] as $tab)
                    <button onclick="switchTab('{{ strtolower($tab) }}', this)"
                            data-tab="{{ strtolower($tab) }}"
                            class="tab-btn text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-all
                                   {{ $tab === 'Weekly' ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $tab }}
                    </button>
                @endforeach
            </div>
        </div>
        <div class="relative h-52 sm:h-64">
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

    {{-- Promo Card --}}
    <div class="relative overflow-hidden rounded-2xl shadow-card animate-fade-up p-6 flex flex-col justify-between min-h-[240px]"
         style="background: linear-gradient(135deg, #1f48e9 0%, #141d54 100%); animation-delay:.15s">
        {{-- Decorative circles --}}
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-10 -left-6 w-52 h-52 rounded-full bg-white/5"></div>
        <div class="absolute top-14 right-0 w-24 h-24 rounded-full bg-white/5"></div>

        <div class="relative">
            <span class="inline-flex items-center gap-1.5 bg-white/15 text-white/90 text-[10px] font-semibold tracking-wider uppercase px-2.5 py-1 rounded-full mb-4">
                <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                Pro Feature
            </span>
            <h3 class="font-display text-xl font-700 text-white leading-snug">
                Expedite Your<br>Laundry Operations
            </h3>
            <p class="text-sm text-white/65 mt-3 leading-relaxed">
                Upgrade to the premium fleet management system to handle 200+ orders daily with AI scheduling.
            </p>
        </div>

        <div class="relative mt-6">
            <button class="w-full py-2.5 px-4 rounded-xl bg-white text-brand-700 text-sm font-bold
                           hover:bg-brand-50 active:scale-95 transition-all duration-150 shadow-sm">
                Explore Pro Features →
            </button>
        </div>
    </div>

</div>

{{-- ── Recent Transactions ──────────────────────── --}}
<div class="bg-white rounded-2xl shadow-card animate-fade-up" style="animation-delay:.2s">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-display text-base font-700 text-slate-900">Recent Transactions</h2>
            <p class="text-xs text-slate-400 mt-0.5">Latest 10 laundry orders processed today</p>
        </div>
        <a href="#" class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition">
            View All
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
                @php
                    $transactions = [
                        ['initials'=>'AS','name'=>'Andi Saputra',    'id'=>'TRX-2941','layanan'=>'Cuci Kering Lipat',  'berat'=>'5.0 kg',  'status'=>'in_progress','harga'=>'Rp 45,000', 'tanggal'=>'Oct 24, 09:12 AM'],
                        ['initials'=>'ML','name'=>'Maria Larasati',  'id'=>'TRX-2940','layanan'=>'Cuci Setrika Express','berat'=>'2.5 kg',  'status'=>'completed',  'harga'=>'Rp 38,500', 'tanggal'=>'Oct 24, 08:45 AM'],
                        ['initials'=>'BP','name'=>'Budi Pratama',    'id'=>'TRX-2939','layanan'=>'Bedcover Large',      'berat'=>'1.0 Unit', 'status'=>'queue',       'harga'=>'Rp 65,000', 'tanggal'=>'Oct 24, 07:30 AM'],
                        ['initials'=>'DW','name'=>'Dewi Wijaya',     'id'=>'TRX-2938','layanan'=>'Cuci Kering Lipat',  'berat'=>'8.2 kg',  'status'=>'in_progress','harga'=>'Rp 73,800', 'tanggal'=>'Oct 23, 04:20 PM'],
                        ['initials'=>'RA','name'=>'Rizky Aditya',    'id'=>'TRX-2937','layanan'=>'Setrika Only',        'berat'=>'3.0 kg',  'status'=>'completed',  'harga'=>'Rp 21,000', 'tanggal'=>'Oct 23, 02:10 PM'],
                        ['initials'=>'SN','name'=>'Siti Nuraini',    'id'=>'TRX-2936','layanan'=>'Cuci Kering Kilat',  'berat'=>'4.5 kg',  'status'=>'queue',       'harga'=>'Rp 54,000', 'tanggal'=>'Oct 23, 11:55 AM'],
                    ];

                    $statusConfig = [
                        'in_progress' => ['label'=>'In Progress', 'class'=>'bg-brand-50 text-brand-600'],
                        'completed'   => ['label'=>'Completed',   'class'=>'bg-emerald-50 text-emerald-600'],
                        'queue'       => ['label'=>'Queue',        'class'=>'bg-amber-50 text-amber-600'],
                    ];

                    $avatarColors = ['AS'=>'from-brand-500 to-brand-700','ML'=>'from-violet-500 to-violet-700','BP'=>'from-rose-400 to-rose-600','DW'=>'from-teal-500 to-teal-700','RA'=>'from-amber-400 to-amber-600','SN'=>'from-emerald-500 to-emerald-700'];
                @endphp

                @foreach($transactions as $trx)
                    @php
                        $st  = $statusConfig[$trx['status']];
                        $avc = $avatarColors[$trx['initials']] ?? 'from-slate-400 to-slate-600';
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors duration-100">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $avc }} flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                    {{ $trx['initials'] }}
                                </div>
                                <div class="leading-none">
                                    <p class="font-medium text-slate-800">{{ $trx['name'] }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">ID: {{ $trx['id'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">{{ $trx['layanan'] }}</td>
                        <td class="px-4 py-3.5 text-center text-slate-600">{{ $trx['berat'] }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $st['class'] }}">
                                @if($trx['status'] === 'in_progress')
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                                @elseif($trx['status'] === 'completed')
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                @endif
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-800">{{ $trx['harga'] }}</td>
                        <td class="px-6 py-3.5 text-right text-xs text-slate-400 whitespace-nowrap">{{ $trx['tanggal'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Load More -->
    <div class="border-t border-slate-100 px-6 py-4 flex justify-center">
        <button class="flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition px-4 py-2 rounded-xl hover:bg-brand-50">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
            Load More Transactions
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Chart.js Data ──────────────────────────────────────────
const chartData = {
    daily: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        income:  [2.1, 3.4, 2.8, 4.2, 3.9, 5.6, 4.3],
        expense: [0.8, 1.2, 0.9, 1.5, 1.1, 2.0, 1.4],
    },
    weekly: {
        labels: ['Week 1','Week 2','Week 3','Week 4','Week 5','Week 6','Week 7'],
        income:  [8.5, 11.2, 9.8, 14.3, 12.1, 16.4, 13.7],
        expense: [3.1, 4.2,  3.6,  5.1,  4.4,  5.9,  4.8],
    },
    monthly: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct'],
        income:  [32, 38, 35, 42, 39, 47, 44, 51, 48, 56],
        expense: [11, 14, 13, 16, 15, 18, 16, 19, 18, 21],
    }
};

// ── Chart Init ─────────────────────────────────────────────
const ctx = document.getElementById('revenueChart').getContext('2d');

const gradBlue = ctx.createLinearGradient(0,0,0,220);
gradBlue.addColorStop(0,  'rgba(53,104,244,.22)');
gradBlue.addColorStop(1,  'rgba(53,104,244,.02)');

const gradRed = ctx.createLinearGradient(0,0,0,220);
gradRed.addColorStop(0,  'rgba(251,113,133,.20)');
gradRed.addColorStop(1,  'rgba(251,113,133,.02)');

let currentTab = 'weekly';
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { intersect: false, mode: 'index' },
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1e293b',
            titleColor: '#94a3b8',
            bodyColor: '#f1f5f9',
            padding: 10,
            cornerRadius: 10,
            displayColors: true,
            boxWidth: 8, boxHeight: 8, boxPadding: 4,
        }
    },
    scales: {
        x: {
            grid: { display: false },
            border: { display: false },
            ticks: { color: '#94a3b8', font: { size: 11, family: 'DM Sans' } }
        },
        y: {
            grid: { color: '#f1f5f9', drawBorder: false },
            border: { display: false, dash: [4,4] },
            ticks: { color: '#94a3b8', font: { size: 11, family: 'DM Sans' }, padding: 8 }
        }
    }
};

const chart = new Chart(ctx, {
    type: 'bar',
    data: buildData('weekly'),
    options: chartOptions
});

function buildData(tab) {
    const d = chartData[tab];
    return {
        labels: d.labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: d.income,
                backgroundColor: 'rgba(53,104,244,.85)',
                hoverBackgroundColor: 'rgba(53,104,244,1)',
                borderRadius: { topLeft: 6, topRight: 6 },
                borderSkipped: false,
                barPercentage: .6,
                categoryPercentage: .7,
            },
            {
                label: 'Pengeluaran',
                data: d.expense,
                backgroundColor: 'rgba(251,113,133,.75)',
                hoverBackgroundColor: 'rgba(244,63,94,1)',
                borderRadius: { topLeft: 6, topRight: 6 },
                borderSkipped: false,
                barPercentage: .6,
                categoryPercentage: .7,
            }
        ]
    };
}

// ── Tab Switch ─────────────────────────────────────────────
function switchTab(tab, btn) {
    currentTab = tab;
    chart.data = buildData(tab);
    chart.update('active');

    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-white','text-brand-600','shadow-sm');
        b.classList.add('text-slate-500');
    });
    btn.classList.add('bg-white','text-brand-600','shadow-sm');
    btn.classList.remove('text-slate-500');
}
</script>
@endpush