{{--resources/views/admin/laporan_keuangan/index.blade.php--}}
@extends('layouts.admin')

@section('title', 'Laporan Keuangan - Bening Laundry')

@section('content')
<div class="space-y-6">
    <!-- Header + Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-500 mt-1">Analisis komprehensif arus kas dan performa bisnis laundry.</p>
        </div>
        <form method="GET" action="{{ route('admin.laporan_keuangan.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                <button type="submit" name="filter" value="bulanan"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm transition {{ $filter == 'bulanan' ? 'bg-blue-500 text-white' : '' }}
                ">Bulanan</button>
                <button type="submit" name="filter" value="tahunan"
                class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-gray-800 shadow-sm transition {{ $filter == 'tahunan' ? 'bg-blue-600 text-white' : '' }}"
                >Tahunan</button>
                <button type="submit" name="filter" value="custom"
                class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-gray-800 shadow-sm transition {{ $filter == 'custom' ? 'bg-blue-600 text-white' : '' }}"
                >Custom</button>
            </div>
            <a href="{{ route('export.transaksi.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('export.transaksi.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
             class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
        </form>
    </div>

    <!-- Statistik Cards (3) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Pemasukan -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pemasukan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
                    @php $isMasukNaik = $persentaseMasuk >= 0; @endphp
                    <span class="inline-flex items-center text-sm {{ $isMasukNaik ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full mt-2">
                        <i class="fas {{ $isMasukNaik ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1 text-xs"></i>
                        {{ number_format(abs($persentaseMasuk), 1, ',', '.') }}% vs {{ $filter == 'bulanan' ? 'bulan lalu' : 'periode sebelumnya' }}
                    </span>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Pengeluaran -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pengeluaran</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
                    @php $isKeluarNaik = $persentaseKeluar >= 0; @endphp
                    <span class="inline-flex items-center text-sm {{ $isKeluarNaik ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50' }} px-2 py-0.5 rounded-full mt-2">
                        <i class="fas {{ $isKeluarNaik ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1 text-xs"></i> 
                        {{ number_format(abs($persentaseKeluar), 1, ',', '.') }}% {{ $isKeluarNaik ? 'peningkatan' : 'efisiensi' }} biaya
                    </span>
                </div>
                <div class="bg-red-100 p-3 rounded-xl">
                    <i class="fas fa-receipt text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Laba Bersih (Highlight) -->
        <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl shadow-lg p-6 relative overflow-hidden text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Laba Bersih</p>
                    <p class="text-3xl font-bold mt-1">Rp {{ number_format($laba, 0, ',', '.') }}</p>
                    <span class="inline-flex items-center text-sm bg-white/20 backdrop-blur-sm px-2 py-0.5 rounded-full mt-2"><i class="fas fa-chart-line mr-1"></i> profit {{ number_format($persenLaba, 2) }}%</span>
                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Target Pemasukan Bulanan -->
    <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-semibold text-gray-800">Target Pemasukan Bulan Ini</h3>
                    <button onclick="document.getElementById('targetModal').classList.remove('hidden')" class="text-blue-500 hover:text-blue-700 text-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-500">Target: Rp {{ number_format($limitPemasukanBulanan, 0, ',', '.') }} • Realisasi: Rp {{ number_format($realisasiBulanIni, 0, ',', '.') }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $persenTargetBulanIni >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                {{ number_format($persenTargetBulanIni, 2) }}%
            </span>
        </div>
        <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full {{ $persenTargetBulanIni >= 100 ? 'bg-emerald-500' : 'bg-blue-600' }}" style="width: {{ min(100, $persenTargetBulanIni) }}%"></div>
        </div>
    </div>

    {{-- ── Edit Target Modal (Hidden by default) ── --}}
    <div id="targetModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('targetModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.update_target') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.171.33 2.51-.645 2.51-1.857v-1a2 2 0 011.01-1.756l.291-.16c1.043-.614 1.043-2.07 0-2.684L13.51 9.24a2 2 0 01-1.01-1.756V6.5a1.5 1.5 0 013 0v.5" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Target Bulanan</h3>
                                <div class="mt-4">
                                    <label for="target" class="block text-sm font-medium text-gray-700">Jumlah Target (Rp)</label>
                                    <input type="number" name="target" id="target" value="{{ $limitPemasukanBulanan }}" class="mt-1 w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" onclick="document.getElementById('targetModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Grafik Tren & Distribusi Pengeluaran (2 kolom + 1 kolom kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Line Chart (2/3 lebar di lg) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-5">
            <div class="flex justify-between items-center flex-wrap mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Tren Pendapatan & Pengeluaran</h2>
                <div class="relative">
                    <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>6 Bulan Terakhir</option>
                        <option>12 Bulan Terakhir</option>
                        <option>Triwulan Ini</option>
                    </select>
                </div>
            </div>
            <canvas id="trendChart"></canvas>
        </div>

        <!-- Distribusi Pengeluaran + Insight -->
        <div class="space-y-6">
            <!-- Card Distribusi -->
            <div class="bg-white rounded-2xl shadow-md p-5">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2"><i class="fas fa-chart-simple text-blue-500"></i> Distribusi Pengeluaran</h3>
                <div class="mt-4 space-y-4">
                    @forelse($distribusiPengeluaran as $item)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">{{ $item['kategori'] }}</span>
                            <span class="text-gray-600">{{ $item['persen'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $item['persen'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada pengeluaran.</p>
                    @endforelse
                </div>
            </div>

            <!-- Card Insight -->
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-100 rounded-2xl shadow-sm p-5">
                <div class="flex gap-3">
                    <div class="text-amber-600 text-xl"><i class="fas fa-lightbulb"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Insight</h4>
                        <p class="text-sm text-gray-600 mt-1">Pengeluaran operasional meningkat 5% dari bulan lalu karena penambahan shift malam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <table class="min-w-full bg-white border mt-6">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Bulan</th>
                    <th class="px-4 py-2 border">Tahun</th>
                    <th class="px-4 py-2 border">Pemasukan</th>
                    <th class="px-4 py-2 border">Pengeluaran</th>
                    <th class="px-4 py-2 border">Laba</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporanBulanan as $data)
                <tr>
                    <td class="border px-4 py-2">{{ $data['bulan'] }}</td>
                    <td class="border px-4 py-2">{{ $data['tahun'] }}</td>
                    <td class="border px-4 py-2 text-green-600">
                        Rp {{ number_format($data['pemasukan'],0,',','.') }}
                    </td>
                    <td class="border px-4 py-2 text-red-600">
                        Rp {{ number_format($data['pengeluaran'],0,',','.') }}
                    </td>
                    <td class="border px-4 py-2 font-bold">
                        Rp {{ number_format($data['laba'],0,',','.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    <!-- Detail Transaksi Terbaru -->
    <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="flex justify-between items-center flex-wrap mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Detail Transaksi Terbaru</h2>
            <a href="{{ route('admin.transactions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">Lihat Semua Ledger <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentExpenses->merge($recentTransactions)->sortByDesc('created_at')->take(10) as $item)
                    @php
                        $isExpense = $item instanceof \App\Models\Pengeluaran;
                        $date = $isExpense ? $item->tanggal : $item->created_at;
                        $description = $isExpense ? $item->nama : 'Transaksi #' . $item->transaksi_code . ' - ' . $item->customer_name;
                        $category = $isExpense ? $item->kategori : 'LAYANAN';
                        $type = $isExpense ? 'PENGELUARAN' : 'PEMASUKAN';
                        $nominal = $isExpense ? $item->nominal : $item->total_price;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $description }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $category }}</td>
                        <td class="px-5 py-3">
                            <span class="{{ $isExpense ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $type }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right text-sm font-medium {{ $isExpense ? 'text-red-600' : 'text-green-600' }}">
                            {{ $isExpense ? '-' : '+' }} Rp {{ number_format($nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                            Belum ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('trendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: @json($dataMasuk),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($dataKeluar),
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.02)',
                        borderWidth: 3,
                        pointBackgroundColor: '#f97316',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true, boxWidth: 10 }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: { color: '#e2e8f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
