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
    <table class="w-full text-sm text-slate-600">
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
                <td class="text-right px-6 relative">
                    <button onclick="toggleDropdown(this)">⋮</button>

                    <div class="dropdown hidden absolute right-0 bg-white border rounded shadow w-32">
                        <button onclick="openEditModal({{ $trx }})"
                            class="block w-full text-left px-3 py-2 hover:bg-gray-100">
                            Edit
                        </button>

                        <form action="{{ route('admin.transactions.destroy',$trx->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="w-full text-left px-3 py-2 text-red-500 hover:bg-gray-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center py-6 text-gray-400">
                    Belum ada data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL CREATE -->
<div id="modalInput" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]">
    <div class="bg-white p-6 rounded-xl w-96">

        <h2 class="font-bold mb-4">Tambah Transaksi</h2>

        <form action="{{ route('admin.transactions.store') }}" method="POST">
            @csrf

            <input name="customer_name" placeholder="Nama"
                class="w-full border p-2 mb-3 rounded">

            <input name="customer_phone" placeholder="No HP"
                class="w-full border p-2 mb-3 rounded">

            <select name="service_type" class="w-full border p-2 mb-3 rounded">
                <option value="regular">Regular</option>
                <option value="express">Express</option>
            </select>

            <input name="weight" type="number" step="0.1" placeholder="Berat"
                class="w-full border p-2 mb-3 rounded">

            <select name="payment_method" class="w-full border p-2 mb-3 rounded text-sm text-slate-700">
                <option value="tunai">Tunai</option>
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer</option>
            </select>
 
            <textarea name="notes" placeholder="Catatan" rows="2"
                class="w-full border p-2 mb-3 rounded text-sm text-slate-700"></textarea>

            <!-- BUTTON -->
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="toggleModal('modalInput')" 
                    class="px-3 py-2 text-gray-600">
                    Batal
                </button>

                <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>


<!-- MODAL EDIT -->
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] overflow-y-auto">
    <div class="bg-white p-6 rounded-xl w-full max-w-md my-8 shadow-xl">
        <h2 class="font-bold text-lg mb-4 text-slate-800">Edit Transaksi</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-3">
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Nama Pelanggan</label>
                    <input id="edit_customer" name="customer_name" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700 focus:ring focus:ring-blue-100">
                </div>
                
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">No HP</label>
                    <input id="edit_phone" name="customer_phone" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700 focus:ring focus:ring-blue-100">
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Berat (kg)</label>
                    <input id="edit_weight" name="weight" type="number" step="0.1" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700 focus:ring focus:ring-blue-100">
                    <p class="text-[10px] text-slate-400 mt-1">Catatan: Jika transaksi berasal dari POS, perubahan berat ini tidak akan mengubah total harga.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Status Pesanan</label>
                        <select id="edit_status" name="status" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700">
                            <option value="diterima">Diterima</option>
                            <option value="disortir">Disortir</option>
                            <option value="dicuci">Dicuci</option>
                            <option value="dikeringkan">Dikeringkan</option>
                            <option value="disetrika">Disetrika</option>
                            <option value="dipacking">Dipacking</option>
                            <option value="selesai">Selesai</option>
                            <option value="diambil">Diambil</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Pembayaran</label>
                        <select id="edit_payment_status" name="payment_status" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700">
                            <option value="belum_bayar">Belum Lunas</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Metode Pembayaran</label>
                    <select id="edit_payment_method" name="payment_method" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700">
                        <option value="tunai">Tunai</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer</option>
                        <option value="cash">Cash (Lama)</option>
                        <option value="dana">Dana (Lama)</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Catatan</label>
                    <textarea id="edit_notes" name="notes" rows="2" class="w-full border border-slate-200 p-2 rounded text-sm text-slate-700"></textarea>
                </div>

                <div id="edit_services_container" class="hidden bg-slate-50 p-3 rounded border border-slate-100">
                    <label class="text-xs font-semibold text-slate-500 uppercase">Layanan yang Dipesan</label>
                    <ul id="edit_services_list" class="text-sm text-slate-700 list-disc pl-4 mt-1">
                    </ul>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="toggleModal('modalEdit')" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded font-medium transition">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium transition shadow-md">Update Transaksi</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT -->
<script>
function toggleModal(id){
    document.getElementById(id).classList.toggle('hidden')
}

function toggleDropdown(btn){
    let menu = btn.nextElementSibling
    menu.classList.toggle('hidden')
}

function openEditModal(data){
    toggleModal('modalEdit');

    document.getElementById('edit_customer').value = data.customer_name || '';
    document.getElementById('edit_phone').value = data.customer_phone || '';
    document.getElementById('edit_weight').value = data.weight || '0';
    document.getElementById('edit_status').value = data.status || 'diterima';
    document.getElementById('edit_payment_status').value = data.payment_status || 'belum_bayar';
    document.getElementById('edit_payment_method').value = data.payment_method || 'tunai';
    document.getElementById('edit_notes').value = data.notes || '';

    let servicesContainer = document.getElementById('edit_services_container');
    let servicesList = document.getElementById('edit_services_list');
    servicesList.innerHTML = '';

    if (data.details && data.details.length > 0) {
        servicesContainer.classList.remove('hidden');
        data.details.forEach(detail => {
            let li = document.createElement('li');
            let namaLayanan = detail.layanan ? detail.layanan.nama : 'Layanan';
            li.textContent = `${namaLayanan} (${detail.qty}x)`;
            servicesList.appendChild(li);
        });
    } else {
        servicesContainer.classList.add('hidden');
    }

    document.getElementById('editForm').action = `/admin/transaksi/${data.id}`;
}
</script>

@endsection
