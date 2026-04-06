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
                {{ $trx->service_type }}
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

            <!-- Pembayaran -->
            <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs rounded 
                    {{ $trx->payment_status == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                </span>
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

            <textarea name="notes" placeholder="Catatan"
                class="w-full border p-2 mb-3 rounded"></textarea>

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
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]">
    <div class="bg-white p-6 rounded-xl w-96">
        <h2 class="font-bold mb-4">Edit Transaksi</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <input id="edit_customer" name="customer_name" class="w-full border p-2 mb-3 rounded">
            <input id="edit_weight" name="weight" class="w-full border p-2 mb-3 rounded">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalEdit')">Batal</button>
                <button class="bg-blue-600 text-white px-3 py-2 rounded">Update</button>
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
    toggleModal('modalEdit')

    document.getElementById('edit_customer').value = data.customer_name
    document.getElementById('edit_weight').value = data.weight

    document.getElementById('editForm').action = `/transactions/${data.id}`
}
</script>

@endsection
