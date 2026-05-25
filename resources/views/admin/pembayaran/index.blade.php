{{-- resources/views/admin/pembayaran/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Manajemen Pembayaran - Bening Laundry')
@section('content')

<div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Pembayaran</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola seluruh transaksi masuk, status pelunasan pelanggan, dan riwayat metode pembayaran dalam satu alur yang jernih.</p>
            </div>
            <a href="{{ route('admin.pembayaran.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md flex items-center gap-2 transition">
                <i class="fas fa-plus"></i> Entri Bayar Baru
            </a>
        </div>

        <!-- STATISTIK CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-green-500 text-sm bg-green-100 px-2 py-1 rounded-full">+12%</span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">↑ 12% dari kemarin</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Transaksi Belum Lunas</p>
                        <p class="text-2xl font-bold mt-1">{{ $transaksiBelumLunas }} Transaksi</p>
                    </div>
                    <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.pembayaran.create') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-arrow-right mr-1"></i> Proses Pembayaran
                    </a>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Metode Populer</p>
                        <p class="text-2xl font-bold mt-1">{{ $metodePopulerNama ?? 'QRIS' }}</p>
                    </div>
                    <i class="fas fa-qrcode text-green-500 text-2xl"></i>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $persentaseMetodePopuler }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ $persentaseMetodePopuler }}% dari total transaksi</p>
                </div>
            </div>
        </div>

        <!-- FILTER TABS -->
        <div class="flex space-x-2 border-b mb-6">
            <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => null])) }}" 
               class="px-5 py-2 text-sm font-medium rounded-t-lg transition {{ !request('status') ? 'bg-white text-blue-700 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                Semua
            </a>
            <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => 'Lunas'])) }}" 
               class="px-5 py-2 text-sm font-medium rounded-t-lg transition {{ request('status') == 'Lunas' ? 'bg-white text-blue-700 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                Lunas
            </a>
            <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => 'Belum Lunas'])) }}" 
               class="px-5 py-2 text-sm font-medium rounded-t-lg transition {{ request('status') == 'Belum Lunas' ? 'bg-white text-blue-700 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                Belum Lunas
            </a>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">{{ $trx->transaksi_code }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $trx->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($trx->service_type) }} - {{ $trx->weight }} kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trx->payment_status == 'lunas')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-check-circle mr-1 text-xs"></i> Lunas
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-hourglass-half mr-1"></i> Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown('dropdown-pembayaran-{{ $trx->id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                                                   hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                                        </svg>
                                    </button>
                                    
                                    <div id="dropdown-pembayaran-{{ $trx->id }}" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                                        @if($trx->payment_status != 'lunas')
                                        <a href="{{ route('admin.pembayaran.create') }}"
                                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Bayar
                                        </a>
                                        @endif
                                        <a href="{{ route('pos.nota', $trx->id) }}" target="_blank"
                                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Cek Nota
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada transaksi ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $transactions->appends(request()->query())->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</main>
@endsection
