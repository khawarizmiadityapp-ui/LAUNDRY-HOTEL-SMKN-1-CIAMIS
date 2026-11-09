<?php $__env->startSection('title', 'Entri Pembayaran Baru - Bening Laundry'); ?>
<?php $__env->startSection('content'); ?>

<div class="container mx-auto px-4 py-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Entri Pembayaran Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Catat pembayaran dari pelanggan untuk transaksi laundry</p>
        </div>
        <a href="<?php echo e(route('admin.pembayaran.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl shadow-md flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- FORM PEMBAYARAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="pembayaranCalculator()">
        <!-- FORM UTAMA (2/3) -->
        <div class="lg:col-span-2">
            <form action="<?php echo e(route('admin.pembayaran.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-md p-6">
                <?php echo csrf_field(); ?>

                <?php if($errors->any()): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- SECTION 1: PILIH TRANSAKSI -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span>Pilih Transaksi</span>
                    </h3>
                    
                    <!-- Search Transaksi -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Transaksi</label>
                        <div class="relative">
                            <input type="text" 
                                   id="searchTransaksi" 
                                   placeholder="Cari berdasarkan ID Transaksi atau Nama Pelanggan..."
                                   class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg class="w-4 h-4 absolute left-3 top-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Transaksi Belum Lunas -->
                    <div class="space-y-3 max-h-96 overflow-y-auto" id="transaksiList">
                        <?php $__empty_1 = true; $__currentLoopData = $transaksiBelumLunas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 cursor-pointer transition transaksi-item">
                            <input type="radio" 
                                   name="transaksi_id" 
                                   value="<?php echo e($trx->transaksi_code); ?>" 
                                   x-model="selectedTransaksi"
                                   @change="totalTagihan = <?php echo e($trx->total_price); ?>"
                                   class="w-5 h-5 text-blue-600 focus:ring-blue-500"
                                   required>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800"><?php echo e($trx->transaksi_code); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($trx->customer_name); ?></p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e(ucfirst($trx->service_type)); ?> - <?php echo e($trx->weight); ?> kg</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600">Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?></p>
                                        <span class="inline-block mt-1 px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Belum Lunas</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-center text-gray-500 bg-gray-50 rounded-lg flex flex-col items-center">
                            <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.375v-6.75a2.25 2.25 0 012.25-2.25h15a2.25 2.25 0 012.25 2.25v6.75m-19.5 0A2.25 2.25 0 004.5 19.5h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 011.07 1.916l-1.07-2.159z" />
                            </svg>
                            <p>Tidak ada transaksi yang belum lunas</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <hr class="my-6">

                <!-- SECTION 2: DETAIL PEMBAYARAN -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                        <span>Detail Pembayaran</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jumlah Bayar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nominal Uang Pelanggan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="jumlah_bayar" 
                                       x-model.number="jumlahBayar"
                                       placeholder="0" 
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required
                                       min="0">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan uang yang diberikan pelanggan</p>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="metode_pembayaran" 
                                    x-model="metodePembayaran"
                                    @change="onMethodChange()"
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

                        <!-- QRIS Barcode Box -->
                        <div class="md:col-span-2" x-show="metodePembayaran === 'QRIS' || metodePembayaran === 'E-Wallet'" x-cloak>
                            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-4 flex flex-col sm:flex-row items-center gap-4">
                                <div class="bg-white p-2.5 rounded-xl border border-purple-200 shadow-sm shrink-0">
                                    <?php if(!empty($qrisConfig['image_url'])): ?>
                                        <img src="<?php echo e($qrisConfig['image_url']); ?>" alt="QRIS Barcode" class="w-36 h-36 object-contain">
                                    <?php else: ?>
                                        <img src="<?php echo e($qrisConfig['fallback_qr_url']); ?>" alt="QRIS Barcode" class="w-36 h-36 object-contain">
                                    <?php endif; ?>
                                </div>
                                <div class="text-center sm:text-left">
                                    <span class="inline-block px-2.5 py-0.5 bg-purple-600 text-white text-[10px] font-bold rounded-full uppercase tracking-wider mb-1">QRIS Terkoneksi</span>
                                    <h4 class="font-bold text-gray-800 text-base"><?php echo e($qrisConfig['merchant_name']); ?></h4>
                                    <p class="text-xs text-gray-500 font-mono mb-2">NMID: <?php echo e($qrisConfig['nmid']); ?></p>
                                    <p class="text-xs text-purple-800 bg-purple-100/80 px-3 py-1.5 rounded-lg border border-purple-200">
                                        Scan QRIS melalui GoPay, OVO, DANA, ShopeePay, LinkAja, atau Mobile Banking. Nominal otomatis disamakan dengan tagihan: <strong>Rp <span x-text="formatRupiah(totalTagihan)"></span></strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Rekening Transfer -->
                        <div class="md:col-span-2" x-show="metodePembayaran.startsWith('Transfer')" x-cloak>
                            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-3.5 text-xs text-indigo-900">
                                <p class="font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5H4.5V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                    </svg>
                                    <span>Rekening Tujuan Transfer:</span>
                                </p>
                                <p class="font-mono font-semibold"><?php echo e($qrisConfig['bank_transfer_info']); ?></p>
                            </div>
                        </div>

                        <!-- Tanggal Pembayaran -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="tanggal_bayar" 
                                   value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Status pembayaran akan otomatis ditentukan berdasarkan nominal yang dibayarkan</p>
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
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <span>Bukti Pembayaran (Opsional)</span>
                    </h3>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                        <input type="file" 
                               name="bukti_pembayaran" 
                               id="buktiPembayaran" 
                               accept="image/*"
                               class="hidden">
                        <label for="buktiPembayaran" class="cursor-pointer flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload bukti pembayaran</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                        </label>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="flex gap-3 justify-end">
                    <a href="<?php echo e(route('admin.pembayaran.index')); ?>" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md flex items-center gap-2 transition font-semibold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Simpan Pembayaran</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- KALKULATOR PEMBAYARAN (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25z" />
                    </svg>
                    <span>Ringkasan Pembayaran</span>
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Tagihan</p>
                        <p class="text-2xl font-bold text-gray-800">Rp <span x-text="formatRupiah(totalTagihan)"></span></p>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm text-gray-500">Nominal Dibayar</p>
                        <p class="text-xl font-bold text-blue-600">Rp <span x-text="formatRupiah(jumlahBayar)"></span></p>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                        <p class="text-lg font-bold mb-1" :class="jumlahBayar >= totalTagihan ? 'text-green-600' : 'text-orange-600'">
                            <span x-show="jumlahBayar >= totalTagihan">✅ Lunas</span>
                            <span x-show="jumlahBayar < totalTagihan && jumlahBayar > 0">💰 Cicilan</span>
                            <span x-show="jumlahBayar == 0">⏳ Belum Dibayar</span>
                        </p>
                    </div>

                    <div class="border-t border-gray-100 pt-4" x-show="jumlahBayar >= totalTagihan">
                        <p class="text-sm text-gray-500">Kembalian</p>
                        <p class="text-3xl font-bold text-green-600">
                            Rp <span x-text="formatRupiah(kembalian > 0 ? kembalian : 0)"></span>
                        </p>
                    </div>

                    <div x-show="jumlahBayar < totalTagihan && jumlahBayar > 0" x-cloak class="p-3 bg-orange-50 text-orange-700 text-sm rounded-lg border border-orange-200 mt-4 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Pembayaran cicilan. Sisa: Rp <span x-text="formatRupiah(Math.abs(kembalian))"></span></span>
                    </div>
                    
                    <div x-show="kembalian < 0 && jumlahBayar == 0" x-cloak class="p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100 mt-4 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <span>Belum ada pembayaran</span>
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
    const transaksiItems = document.querySelectorAll('.transaksi-item');
    
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

document.addEventListener('alpine:init', () => {
    Alpine.data('pembayaranCalculator', () => ({
        selectedTransaksi: '',
        totalTagihan: 0,
        jumlahBayar: '',
        metodePembayaran: '',

        onMethodChange() {
            if ((this.metodePembayaran === 'QRIS' || this.metodePembayaran === 'E-Wallet' || this.metodePembayaran.startsWith('Transfer')) && this.totalTagihan > 0) {
                this.jumlahBayar = this.totalTagihan;
            }
        },
        
        get kembalian() {
            if (!this.jumlahBayar || this.totalTagihan === 0) return 0;
            return this.jumlahBayar - this.totalTagihan;
        },
        
        formatRupiah(value) {
            if (!value) return '0';
            return new Intl.NumberFormat('id-ID').format(value);
        }
    }))
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\pembayaran\create.blade.php ENDPATH**/ ?>