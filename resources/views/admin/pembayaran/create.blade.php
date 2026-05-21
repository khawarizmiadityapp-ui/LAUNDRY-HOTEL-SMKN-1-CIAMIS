{{-- resources/views/admin/pembayaran/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Entri Pembayaran Baru - Bening Laundry')
@section('content')

<div class="container mx-auto px-4 py-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Entri Pembayaran Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Catat pembayaran dari pelanggan untuk transaksi laundry</p>
        </div>
        <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl shadow-md flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- FORM PEMBAYARAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FORM UTAMA (2/3) -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.pembayaran.store') }}" method="POST" class="bg-white rounded-xl shadow-md p-6">
                @csrf

                <!-- SECTION 1: PILIH TRANSAKSI -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-blue-600"></i> Pilih Transaksi
                    </h3>
                    
                    <!-- Search Transaksi -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Transaksi</label>
                        <div class="relative">
                            <input type="text" 
                                   id="searchTransaksi" 
                                   placeholder="Cari berdasarkan ID Transaksi atau Nama Pelanggan..."
                                   class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Transaksi Belum Lunas -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 cursor-pointer transition">
                            <input type="radio" 
                                   name="transaksi_id" 
                                   value="TRX-{{ 8800 + $i }}" 
                                   class="w-5 h-5 text-blue-600 focus:ring-blue-500"
                                   required>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">#BNG-{{ 8800 + $i }}</p>
                                        <p class="text-sm text-gray-600">Pelanggan {{ $i }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Reguler - {{ rand(2, 10) }} kg</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600">Rp {{ number_format(rand(25000, 60000), 0, ',', '.') }}</p>
                                        <span class="inline-block mt-1 px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Belum Lunas</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endfor
                    </div>
                </div>

                <hr class="my-6">

                <!-- SECTION 2: DETAIL PEMBAYARAN -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-money-bill-wave text-green-600"></i> Detail Pembayaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jumlah Bayar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Bayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="jumlah_bayar" 
                                       placeholder="0" 
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required
                                       min="0">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan jumlah yang dibayarkan pelanggan</p>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="metode_pembayaran" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Pilih Metode</option>
                                <option value="Tunai">💵 Tunai</option>
                                <option value="QRIS">📱 QRIS</option>
                                <option value="Transfer BCA">🏦 Transfer BCA</option>
                                <option value="Transfer Mandiri">🏦 Transfer Mandiri</option>
                                <option value="Transfer BRI">🏦 Transfer BRI</option>
                                <option value="E-Wallet">💳 E-Wallet (GoPay/OVO/Dana)</option>
                            </select>
                        </div>

                        <!-- Tanggal Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="tanggal_bayar" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- Status Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="status_pembayaran" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="Lunas">✅ Lunas</option>
                                <option value="Belum Lunas">⏳ Belum Lunas</option>
                                <option value="Cicilan">💰 Cicilan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" 
                                  rows="3" 
                                  placeholder="Tambahkan catatan pembayaran jika diperlukan..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>

                <hr class="my-6">

                <!-- SECTION 3: BUKTI PEMBAYARAN (OPTIONAL) -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-camera text-purple-600"></i> Bukti Pembayaran (Opsional)
                    </h3>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                        <input type="file" 
                               name="bukti_pembayaran" 
                               id="buktiPembayaran" 
                               accept="image/*"
                               class="hidden">
                        <label for="buktiPembayaran" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">Klik untuk upload bukti pembayaran</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                        </label>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.pembayaran.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md flex items-center gap-2 transition">
                        <i class="fas fa-save"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>

        <!-- SIDEBAR INFO (1/3) -->
        <div class="lg:col-span-1">
            <!-- Card Panduan -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                <h4 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Panduan Entri Pembayaran
                </h4>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle mt-0.5"></i>
                        <span>Pilih transaksi yang belum lunas dari daftar</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle mt-0.5"></i>
                        <span>Masukkan jumlah yang dibayarkan pelanggan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle mt-0.5"></i>
                        <span>Pilih metode pembayaran yang digunakan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle mt-0.5"></i>
                        <span>Upload bukti pembayaran jika ada (opsional)</span>
                    </li>
                </ul>
            </div>

            <!-- Card Statistik Hari Ini -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-green-600"></i> Statistik Hari Ini
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-gray-800">12 Transaksi</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Pendapatan</span>
                        <span class="font-bold text-green-600">Rp 1.250.000</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Belum Lunas</span>
                        <span class="font-bold text-red-600">5 Transaksi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Search -->
<script>
document.getElementById('searchTransaksi').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const transaksiItems = document.querySelectorAll('label[class*="flex items-center p-4"]');
    
    transaksiItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});

// Preview bukti pembayaran
document.getElementById('buktiPembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const label = document.querySelector('label[for="buktiPembayaran"]');
            label.innerHTML = `
                <img src="${e.target.result}" class="max-h-48 mx-auto rounded-lg mb-2">
                <p class="text-sm text-green-600">✓ ${file.name}</p>
                <p class="text-xs text-gray-400 mt-1">Klik untuk ganti gambar</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection
