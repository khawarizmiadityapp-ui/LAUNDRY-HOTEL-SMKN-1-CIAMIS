{{--resources/views/admin/laporan_keuangan/index.blade.php--}}
@extends('layouts.admin')

@section('title', 'Laporan Keuangan - Bening Laundry')

@section('content')
<div class="space-y-6">
    <!-- Header + Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-500 mt-1">Analisis komprehensif arus kas dan performa bisnis laundry.</p>
        </div>
        <div class="flex flex-col gap-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                        <a href="{{ route('admin.laporan_keuangan.index', ['filter' => 'bulanan']) }}"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition {{ ($filter ?? 'bulanan') == 'bulanan' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-800' }}">Bulanan</a>
                        
                        <a href="{{ route('admin.laporan_keuangan.index', ['filter' => 'tahunan']) }}"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition {{ $filter == 'tahunan' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-800' }}">Tahunan</a>
                        
                        <button type="button" onclick="toggleCustomFilter(this)"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition {{ $filter == 'custom' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-800' }}">Custom</button>
                    </div>
                    <button type="button" onclick="openExportModal()" class="flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
                        <i class="fas fa-file-export"></i> Export Data
                    </button>
                </div>
            </div>
            
            <form method="GET" action="{{ route('admin.laporan_keuangan.index') }}" id="customFilterDiv" class="{{ $filter == 'custom' ? 'flex' : 'hidden' }} absolute right-0 top-full mt-2 z-50 flex-col gap-3 bg-white p-4 rounded-xl shadow-xl border border-gray-100 w-64">
                <input type="hidden" name="filter" value="custom">
                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition w-full mt-1">Terapkan Filter</button>
            </form>

            @if($errors->has('dari') || $errors->has('sampai'))
                <div class="text-red-500 text-xs">
                    @error('dari') <div>{{ $message }}</div> @enderror
                    @error('sampai') <div>{{ $message }}</div> @enderror
                </div>
            @endif
        </div>
    </div>

    <!-- Statistik Cards (5) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Pemasukan -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pemasukan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>

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

                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Piutang -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Piutang (Belum Bayar)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($piutang ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-xl">
                    <i class="fas fa-hand-holding-dollar text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Utang -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Utang (Kewajiban)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($utang ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-file-invoice-dollar text-purple-600 text-xl"></i>
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

    {{-- ═══════════ DAILY TARGET TRACKING ═══════════ --}}
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-md p-6 border border-blue-100">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                    Target Harian (7 Hari Terakhir)
                </h3>
                <p class="text-sm text-gray-600 mt-1">Tracking performa harian dengan sistem carry-forward deficit</p>
            </div>
            <div class="bg-white rounded-xl px-4 py-3 border border-blue-200 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pencapaian Minggu Ini</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $weeklyAchievementRate }}%</p>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($weeklyActualSum) }} / {{ number_format($weeklyTargetSum) }}</p>
            </div>
        </div>

        {{-- Today's Highlight --}}
        <div class="bg-white rounded-xl p-5 mb-4 border-2 border-blue-300 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Hari Ini</p>
                    <p class="text-xs text-gray-400">{{ $todayTarget->date->translatedFormat('l, d F Y') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $todayTarget->status_color === 'green' ? 'bg-emerald-100 text-emerald-700' : 
                       ($todayTarget->status_color === 'yellow' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                    {{ $todayTarget->is_achieved ? 'Target Tercapai ✓' : 'Belum Tercapai' }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Target Dasar</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($todayTarget->base_target, 0, ',', '.') }}</p>
                </div>
                @if($todayTarget->carry_forward > 0)
                <div>
                    <p class="text-xs text-rose-500 font-semibold">+ Deficit Kemarin</p>
                    <p class="text-lg font-bold text-rose-600">Rp {{ number_format($todayTarget->carry_forward, 0, ',', '.') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Target Final</p>
                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($todayTarget->adjusted_target, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Realisasi Bersih</p>
                    <p class="text-lg font-bold {{ $todayTarget->net_income >= $todayTarget->adjusted_target ? 'text-emerald-600' : 'text-amber-600' }}">
                        Rp {{ number_format($todayTarget->net_income, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between text-xs font-semibold">
                    <span class="text-gray-600">Progress</span>
                    <span class="text-blue-600">{{ $todayTarget->achievement_percentage }}%</span>
                </div>
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-500 {{ $todayTarget->is_achieved ? 'bg-emerald-500' : 'bg-blue-600' }}" 
                         style="width: {{ min(100, $todayTarget->achievement_percentage) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Last 7 Days Table --}}
        <div class="bg-white rounded-xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Target</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Realisasi</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Selisih</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($dailyTargets as $target)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-800">{{ $target->date->translatedFormat('D') }}</span>
                                    <span class="text-xs text-gray-500">{{ $target->date->translatedFormat('d M') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($target->adjusted_target, 0, ',', '.') }}</span>
                                    @if($target->carry_forward > 0)
                                    <span class="text-xs text-rose-500">(+{{ number_format($target->carry_forward, 0, ',', '.') }})</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold {{ $target->net_income >= $target->adjusted_target ? 'text-emerald-600' : 'text-amber-600' }}">
                                Rp {{ number_format($target->net_income, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-bold {{ $target->variance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $target->variance >= 0 ? '+' : '' }}Rp {{ number_format($target->variance, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $target->status_color === 'green' ? 'bg-emerald-100 text-emerald-700' : 
                                       ($target->status_color === 'yellow' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $target->achievement_percentage }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-gray-600">
                <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                <strong>Sistem Carry-Forward:</strong> Jika hari ini pemasukan bersih kurang dari target, defisit akan ditambahkan ke target hari berikutnya secara otomatis.
            </p>
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

@include('admin.laporan_keuangan.partials.export_modal')

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

    function toggleCustomFilter(btn) {
        const form = document.getElementById('customFilterDiv');
        const isHidden = form.classList.toggle('hidden');
        
        const container = btn.parentElement;
        const links = container.querySelectorAll('a');
        
        if (!isHidden) {
            // Activate Custom button visually
            btn.classList.remove('text-gray-600', 'hover:text-gray-800');
            btn.classList.add('bg-blue-600', 'text-white');
            
            // Visually deactivate others
            links.forEach(l => {
                if (l.classList.contains('bg-blue-600')) {
                    l.dataset.wasActive = 'true';
                    l.classList.remove('bg-blue-600', 'text-white');
                    l.classList.add('text-gray-600', 'hover:text-gray-800');
                }
            });
        } else {
            // Revert Custom button
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('text-gray-600', 'hover:text-gray-800');
            
            // Restore previous active button
            links.forEach(l => {
                if (l.dataset.wasActive === 'true') {
                    l.classList.add('bg-blue-600', 'text-white');
                    l.classList.remove('text-gray-600', 'hover:text-gray-800');
                    delete l.dataset.wasActive;
                }
            });
        }
    }
</script>
@endpush
