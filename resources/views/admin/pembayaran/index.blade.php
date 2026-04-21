{{-- resources/views/admin/pembayaran/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Manajemen Pembayaran - Bening Laundry')
@section('content')

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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $trx)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">{{ $trx['id_transaksi'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $trx['pelanggan']['nama'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $trx['pelanggan']['layanan'] }} - {{ $trx['pelanggan']['berat'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $trx['tanggal_bayar'] ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 text-sm">
                                        <i class="fas {{ $trx['metode'] == 'QRIS' ? 'fa-qrcode' : ($trx['metode'] == 'Tunai' ? 'fa-money-bill' : 'fa-university') }} text-gray-500"></i>
                                        {{ $trx['metode'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">Rp {{ number_format($trx['jumlah'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($trx['status'] == 'Lunas')
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
                                    @if($trx['status'] == 'Belum Lunas')
                                        <a href="{{ route('admin.pembayaran.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                            <i class="fas fa-credit-card"></i> Bayar Sekarang
                                        </a>
                                    @else
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada transaksi ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination & Info -->
                <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $transactions->firstItem() ?? 0 }} sampai {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi
                    </div>
                    <div>
                        {{ $transactions->appends(request()->query())->onEachSide(1)->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>

            <!-- SECTION BAWAH: 2 CARD -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Ingatkan Pembayaran -->
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-800"><i class="fab fa-whatsapp text-green-500 mr-2"></i> Ingatkan Pembayaran</h3>
                        <p class="text-sm text-gray-500 mt-1">Kirim notifikasi otomatis ke WhatsApp pelanggan yang belum melunasi tagihan mereka.</p>
                    </div>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow text-sm flex items-center gap-2 transition">
                        <i class="fab fa-whatsapp"></i> Kirim Blast WA
                    </button>
                </div>
                <!-- Card Laporan Bulanan -->
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-800"><i class="fas fa-chart-line text-blue-500 mr-2"></i> Laporan Pembayaran Bulanan</h3>
                        <p class="text-sm text-gray-500 mt-1">Unduh rekapitulasi seluruh transaksi bulan Oktober dalam format PDF atau Excel.</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="border border-red-500 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg text-sm flex items-center gap-1 transition">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <button class="border border-green-500 text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg text-sm flex items-center gap-1 transition">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Optional pagination style overrides for Tailwind -->
<style>
    /* Pagination styling */
    .pagination {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        background-color: #f3f4f6;
        color: #374151;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .pagination a:hover {
        background-color: #e0e7ff;
        color: #1e40af;
    }
    .pagination .active span {
        background-color: #2563eb;
        color: white;
    }
    .pagination .disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection