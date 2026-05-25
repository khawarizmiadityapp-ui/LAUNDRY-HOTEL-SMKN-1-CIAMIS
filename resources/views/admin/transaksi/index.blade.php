@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Transaksi</h1>
        <p class="text-slate-500">Kelola pesanan masuk dan status pengerjaan.</p>
    </div>

    <button onclick="toggleModal('modalInput')" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 shadow-lg">
        <i class="fa-solid fa-plus"></i> Pesanan Baru
    </button>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-slate-600 min-w-[950px]">
        <thead class="bg-slate-50 text-slate-700">
            <tr>
                <th class="px-6 py-3 text-left">Pelanggan</th>
                <th class="px-6 py-3 text-left">Layanan</th>
                <th class="px-6 py-3 text-left">Berat</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Pembayaran</th>
                <th class="px-6 py-3 text-left">Metode</th>
                <th class="px-6 py-3 text-left">Total</th>
                <th class="px-6 py-3 text-right">Aksi</th>
            </tr>
        </thead>
    
        <tbody class="divide-y">
            @forelse($transactions as $trx)
            <tr class="hover:bg-slate-50 transition">
                
                <!-- Pelanggan -->
                <td class="px-6 py-4">
                    <div class="font-semibold text-slate-800">
                        {{ $trx->customer_name }}
                    </div>
                    <div class="text-xs text-slate-400">
                        {{ $trx->created_at->format('d M Y') }}
                    </div>
                </td>
    
                <!-- Layanan -->
                <td class="px-6 py-4 capitalize">
                    @if(isset($trx->details) && $trx->details->count() > 0)
                        <ul class="text-xs list-disc pl-3 text-slate-600">
                        @foreach($trx->details as $detail)
                            <li>{{ $detail->layanan->nama ?? 'Layanan' }} ({{ $detail->qty }}x)</li>
                        @endforeach
                        </ul>
                    @else
                        {{ $trx->service_type }}
                    @endif
                </td>
    
                <!-- Berat -->
                <td class="px-6 py-4">
                    {{ $trx->weight }} kg
                </td>
    
                <!-- Status -->
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-600">
                        {{ $trx->status }}
                    </span>
                </td>
    
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded 
                        {{ $trx->payment_status == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </td>
     
                <!-- Metode -->
                <td class="px-6 py-4 uppercase font-bold text-xs">
                    {{ $trx->payment_method ?? 'Cash' }}
                </td>
    
                <!-- Total -->
                <td class="px-6 py-4 font-semibold text-slate-800">
                    Rp {{ number_format($trx->total_price,0,',','.') }}
                </td>
    
                <!-- Aksi -->
                <td class="px-6 py-4 text-right whitespace-nowrap">
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown('dropdown-{{ $trx->id }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                                       hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                            </svg>
                        </button>
                        
                        <div id="dropdown-{{ $trx->id }}" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                            <a href="{{ route('pos.nota', $trx->id) }}" target="_blank"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Cek Nota
                            </a>

                            <div class="h-px bg-slate-50 my-1"></div>

                            <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
    
            @empty
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-400">
                    Belum ada data
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="bg-white rounded-xl shadow-sm border mt-4 p-4">
        {{ $transactions->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

<!-- MODAL CREATE -->
<div id="modalInput" class="hidden fixed inset-0 bg-black/50 z-[9999] overflow-y-auto p-4">
    <div class="flex items-start justify-center min-h-screen">
        <div class="bg-white p-6 rounded-xl w-full max-w-md my-8 shadow-xl">
            <h2 class="font-bold text-lg mb-4 text-slate-800">Tambah Transaksi</h2>

            <form action="{{ route('admin.transactions.store') }}" method="POST">
                @csrf

                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Nama Pelanggan</label>
                        <input name="customer_name" placeholder="Nama" required
                            class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700 focus:ring focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">No HP</label>
                        <input name="customer_phone" placeholder="No HP" required
                            class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700 focus:ring focus:ring-blue-100">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Tipe Layanan</label>
                            <select name="service_type" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700">
                                <option value="regular">Regular</option>
                                <option value="express">Express</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Berat (kg)</label>
                            <input name="weight" type="number" step="0.1" placeholder="0.0" required
                                class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700 focus:ring focus:ring-blue-100">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700">
                            <option value="tunai">Tunai</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Catatan</label>
                        <textarea name="notes" placeholder="Catatan" rows="2"
                            class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700"></textarea>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="toggleModal('modalInput')" 
                        class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded font-medium transition">
                        Batal
                    </button>

                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium transition shadow-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
function toggleModal(id){
    document.getElementById(id).classList.toggle('hidden')
}
</script>

@endsection
