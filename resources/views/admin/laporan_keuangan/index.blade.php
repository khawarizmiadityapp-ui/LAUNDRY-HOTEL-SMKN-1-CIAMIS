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

    <!-- Target Pemasukan Bulanan & Tahunan (Modernized Layout) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden transition-all duration-300 hover:shadow-md">
        <!-- Top Accent Gradient Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500"></div>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shadow-xs shrink-0">
                    <i class="fas fa-bullseye text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-base font-bold text-gray-800 tracking-tight">Target Pemasukan Admin</h3>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Target Aktif
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Monitoring target finansial bulanan & tahunan secara realtime</p>
                </div>
            </div>

            <div class="flex items-center gap-3 self-start md:self-auto">
                <button onclick="document.getElementById('targetModal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 rounded-xl transition-all duration-200 shadow-2xs group">
                    <i class="fas fa-edit text-blue-500 group-hover:scale-110 transition-transform"></i>
                    <span>Edit Target</span>
                </button>

                <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border text-xs font-bold shadow-2xs {{ $persenTargetBulanIni >= 100 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                    <i class="fas {{ $persenTargetBulanIni >= 100 ? 'fa-circle-check text-emerald-600' : 'fa-chart-line text-amber-600' }}"></i>
                    <span>{{ number_format($persenTargetBulanIni, 2) }}% Target</span>
                </div>
            </div>
        </div>

        <!-- Target Cards Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-6">
            <!-- Target Tahunan -->
            <div class="bg-gray-50/70 border border-gray-100 rounded-xl p-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-white border border-gray-200/60 text-gray-600 flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fas fa-calendar-alt text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-gray-400">Target Tahunan</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5 truncate">Rp {{ number_format($annualTarget, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Target Bulanan -->
            <div class="bg-indigo-50/40 border border-indigo-100/60 rounded-xl p-3.5 flex items-center gap-3 hover:bg-indigo-50/60 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-white border border-indigo-100 text-indigo-600 flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fas fa-calendar-check text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-indigo-500">Target Bulanan</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5 truncate">Rp {{ number_format($limitPemasukanBulanan, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Target Dasar Harian -->
            <div class="bg-blue-50/40 border border-blue-100/60 rounded-xl p-3.5 flex items-center gap-3 hover:bg-blue-50/60 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-white border border-blue-100 text-blue-600 flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fas fa-clock text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-blue-500">
                        Target Harian ({{ \App\Models\DailyTarget::getTargetDaysInMonth() }} Hari)
                    </p>
                    <p class="text-sm font-bold text-blue-700 mt-0.5 truncate">Rp {{ number_format(\App\Models\DailyTarget::calculateBaseTarget(), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Bar & Realization Info -->
        <div class="bg-gray-50/60 border border-gray-100 rounded-xl p-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs font-semibold gap-1.5 mb-2.5">
                <div class="flex items-center gap-1.5">
                    <span class="text-gray-500">Realisasi Bulan Ini:</span>
                    <span class="text-gray-900 font-bold text-sm">Rp {{ number_format($realisasiBulanIni, 0, ',', '.') }}</span>
                </div>
                <div class="text-gray-500 text-xs">
                    Target: <span class="font-semibold text-gray-700">Rp {{ number_format($limitPemasukanBulanan, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Sleek Bar -->
            <div class="w-full h-3.5 bg-gray-200/70 rounded-full overflow-hidden p-0.5 relative shadow-inner">
                <div class="h-full rounded-full transition-all duration-700 bg-gradient-to-r {{ $persenTargetBulanIni >= 100 ? 'from-emerald-500 to-teal-400' : 'from-blue-600 via-indigo-600 to-blue-500' }} shadow-xs" 
                     style="width: {{ min(100, max(2, $persenTargetBulanIni)) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ═══════════ DAILY TARGET & FINANCIAL REPORT ═══════════ --}}
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-md p-6 border border-blue-100">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                    Laporan Keuangan & Target Harian (Bulan {{ now()->translatedFormat('F Y') }})
                </h3>
                <p class="text-sm text-gray-600 mt-1">Sistem pencatatan harian otomatis. Jika pemasukan minus/defisit, kekurangan target otomatis ditambahkan ke target hari berikutnya.</p>
            </div>
            <div class="bg-white rounded-xl px-4 py-3 border border-blue-200 shadow-sm flex items-center gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pencapaian Bulan Ini</p>
                    <p class="text-2xl font-bold text-blue-600 mt-0.5">{{ $weeklyAchievementRate }}%</p>
                </div>
                <div class="text-right border-l border-gray-200 pl-4">
                    <p class="text-xs text-gray-500 font-semibold">Realisasi / Target</p>
                    <p class="text-xs font-bold text-gray-700 mt-1">Rp {{ number_format($weeklyActualSum, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">dari Rp {{ number_format($weeklyTargetSum, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Today's Highlight --}}
        <div class="bg-white rounded-xl p-5 mb-5 border-2 border-blue-300 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Target Hari Ini</p>
                    <p class="text-xs text-gray-400">{{ $todayTarget->date->translatedFormat('l, d F Y') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $todayTarget->status_color === 'green' ? 'bg-emerald-100 text-emerald-700' : 
                       ($todayTarget->status_color === 'yellow' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                    {{ $todayTarget->is_achieved ? 'Target Tercapai ✓' : 'Belum Tercapai' }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Target Dasar</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($todayTarget->base_target, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">+ Defisit Kemarin</p>
                    <p class="text-lg font-bold {{ $todayTarget->carry_forward > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                        Rp {{ number_format($todayTarget->carry_forward, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Target Final Hari Ini</p>
                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($todayTarget->adjusted_target, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Realisasi Bersih</p>
                    <p class="text-lg font-bold {{ $todayTarget->net_income >= $todayTarget->adjusted_target ? 'text-emerald-600' : 'text-amber-600' }}">
                        Rp {{ number_format($todayTarget->net_income, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Selisih (Defisit/Surplus)</p>
                    <p class="text-lg font-bold {{ $todayTarget->variance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $todayTarget->variance >= 0 ? '+' : '' }}Rp {{ number_format($todayTarget->variance, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between text-xs font-semibold">
                    <span class="text-gray-600">Progress Hari Ini</span>
                    <span class="text-blue-600">{{ $todayTarget->achievement_percentage }}%</span>
                </div>
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-500 {{ $todayTarget->is_achieved ? 'bg-emerald-500' : 'bg-blue-600' }}" 
                         style="width: {{ min(100, $todayTarget->achievement_percentage) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Laporan Per Hari Table --}}
        <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
            <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fas fa-list text-blue-600"></i> Rincian Per Hari (Bulan {{ now()->translatedFormat('F Y') }})
                </h4>
                <span class="text-xs text-gray-500 font-medium">Total: {{ $dailyTargets->count() }} Hari</span>
            </div>
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Target Dasar</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Defisit Kemarin</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Target Final</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pemasukan</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pengeluaran</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Realisasi Bersih</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Selisih</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dailyTargets as $dt)
                        <tr class="hover:bg-gray-50 {{ $dt->date->isToday() ? 'bg-blue-50/60 font-semibold' : '' }}">
                            <td class="px-4 py-3 text-xs text-gray-800 font-medium">
                                {{ $dt->date->translatedFormat('d M Y (D)') }}
                                @if($dt->date->isToday())
                                    <span class="ml-1 text-3xs bg-blue-600 text-white px-1.5 py-0.5 rounded-full uppercase">Hari Ini</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 text-right">Rp {{ number_format($dt->base_target, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs text-right {{ $dt->carry_forward > 0 ? 'text-rose-600 font-semibold' : 'text-gray-400' }}">
                                Rp {{ number_format($dt->carry_forward, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-xs text-blue-600 font-semibold text-right">Rp {{ number_format($dt->adjusted_target, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs text-emerald-600 font-medium text-right">Rp {{ number_format($dt->actual_income, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs text-rose-600 font-medium text-right">Rp {{ number_format($dt->actual_expense, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-right {{ $dt->net_income >= $dt->adjusted_target ? 'text-emerald-600' : 'text-amber-600' }}">
                                Rp {{ number_format($dt->net_income, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-xs font-bold text-right {{ $dt->variance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $dt->variance >= 0 ? '+' : '' }}Rp {{ number_format($dt->variance, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-3xs font-bold uppercase tracking-wider
                                    {{ $dt->status_color === 'green' ? 'bg-emerald-100 text-emerald-700' : 
                                       ($dt->status_color === 'yellow' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $dt->is_achieved ? 'Tercapai' : 'Belum' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-gray-600 leading-relaxed">
                <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                <strong>Sistem Carry-Forward Defisit:</strong> Target harian dihitung dari Target Bulanan ÷ Jumlah Hari Target. Jika realisasi bersih harian mengalami defisit (minus dari target final), defisit tersebut secara otomatis ditambahkan ke target hari berikutnya agar target keseluruhan admin tetap berlanjut secara konsisten.
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
                                <i class="fas fa-bullseye text-blue-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit Target Admin</h3>
                                <p class="text-xs text-gray-500 mt-1">Mengatur target pendapatan admin per tahun atau per bulan & hari target.</p>
                                
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Target</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                                                <input type="radio" name="target_type" value="tahunan" onchange="toggleTargetLabel('tahunan')" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-semibold text-gray-700">Target Tahunan</span>
                                            </label>
                                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                                                <input type="radio" name="target_type" value="bulanan" checked onchange="toggleTargetLabel('bulanan')" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-semibold text-gray-700">Target Bulanan</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="targetInput" id="targetInputLabel" class="block text-sm font-medium text-gray-700">Nominal Target Bulanan (Rp)</label>
                                        <input type="number" name="target" id="targetInput" value="{{ $limitPemasukanBulanan }}" class="mt-1 w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold" required>
                                        <p class="text-xs text-gray-400 mt-1" id="targetHelpText">Target harian dasar akan dihitung otomatis: Target ÷ Jumlah Hari.</p>
                                    </div>

                                    <div class="border-t border-gray-100 pt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembagi Hari Dalam Bulan</label>
                                        <div class="grid grid-cols-2 gap-3 mb-2">
                                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                                                <input type="radio" name="days_mode" value="auto" {{ env('TARGET_DAYS_PER_MONTH') && env('TARGET_DAYS_PER_MONTH') !== 'auto' ? '' : 'checked' }} onchange="toggleDaysMode('auto')" class="text-blue-600 focus:ring-blue-500">
                                                <div>
                                                    <span class="block text-xs font-semibold text-gray-700">Otomatis Kalender</span>
                                                    <span class="block text-[10px] text-gray-500">Sesuai bulan (28/30/31 hari)</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                                                <input type="radio" name="days_mode" value="custom" {{ env('TARGET_DAYS_PER_MONTH') && env('TARGET_DAYS_PER_MONTH') !== 'auto' ? 'checked' : '' }} onchange="toggleDaysMode('custom')" class="text-blue-600 focus:ring-blue-500">
                                                <div>
                                                    <span class="block text-xs font-semibold text-gray-700">Custom Hari Kerja</span>
                                                    <span class="block text-[10px] text-gray-500">Tentukan jumlah hari</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div id="customDaysWrapper" class="{{ env('TARGET_DAYS_PER_MONTH') && env('TARGET_DAYS_PER_MONTH') !== 'auto' ? '' : 'hidden' }}">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Hari Target Per Bulan</label>
                                            <input type="number" name="custom_days" id="customDaysInput" min="1" max="31" value="{{ is_numeric(env('TARGET_DAYS_PER_MONTH')) ? env('TARGET_DAYS_PER_MONTH') : 26 }}" placeholder="Contoh: 26" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                            <p class="text-[11px] text-gray-400 mt-1">Misalnya set 26 hari kerja per bulan. Target harian = Target Bulanan ÷ jumlah hari kerja.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm shadow-md">Simpan Target</button>
                        <button type="button" onclick="document.getElementById('targetModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleTargetLabel(type) {
            const label = document.getElementById('targetInputLabel');
            const input = document.getElementById('targetInput');
            const help = document.getElementById('targetHelpText');
            if (type === 'tahunan') {
                label.innerText = 'Nominal Target Tahunan (Rp)';
                input.value = {{ $annualTarget }};
                help.innerText = 'Target bulanan akan dihitung (Target Tahunan ÷ 12) & target harian otomatis disesuaikan.';
            } else {
                label.innerText = 'Nominal Target Bulanan (Rp)';
                input.value = {{ $limitPemasukanBulanan }};
                help.innerText = 'Target harian dasar akan dihitung otomatis: Target ÷ Jumlah Hari.';
            }
        }

        function toggleDaysMode(mode) {
            const wrapper = document.getElementById('customDaysWrapper');
            if (mode === 'custom') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }
        }
    </script>

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
