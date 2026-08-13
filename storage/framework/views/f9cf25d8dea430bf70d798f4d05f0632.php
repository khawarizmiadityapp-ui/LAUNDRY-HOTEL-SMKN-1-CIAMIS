<?php $__env->startSection('title', 'Pengaturan - Bening Laundry'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Pengaturan Aplikasi</h1>
            <p class="text-gray-500 mt-1">Kelola konfigurasi sistem Bening Laundry secara sentral.</p>
        </div>
    </div>

    <!-- Settings Card Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Settings Form Card -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Konfigurasi Umum
            </h2>
            
            <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                <?php echo csrf_field(); ?>
                
                <!-- Target Pendapatan Bulanan -->
                <div>
                    <label for="target" class="block text-sm font-semibold text-gray-700 mb-1">Target Pemasukan Bulanan (Rp)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-semibold text-sm">
                            Rp
                        </div>
                        <input type="number" name="target" id="target" value="<?php echo e($limitPemasukanBulanan); ?>" required
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="Contoh: 50000000">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Digunakan untuk visualisasi progres pencapaian target pada laporan keuangan.</p>
                </div>

                <!-- Nomor WA Pihak Bisa Dihubungi Pembeli -->
                <div>
                    <label for="admin_wa" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Admin (Contact Person)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <input type="text" name="admin_wa" id="admin_wa" value="<?php echo e($adminWA); ?>" required
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="Contoh: 082116035029">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Bisa diawali 08, 628, atau +62 (contoh: 082116035029). Nomor ini digunakan oleh pelanggan pada halaman tracking dan landing page untuk menghubungi laundry.</p>
                </div>

                <!-- Nomor WA Pihak Layanan (Pemesanan) -->
                <div>
                    <label for="service_wa" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Service (Pemesanan)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <input type="text" name="service_wa" id="service_wa" value="<?php echo e($serviceWA); ?>" required
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="Contoh: 082116035029">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Bisa diawali 08, 628, atau +62 (contoh: 082116035029). Nomor ini digunakan khusus ketika pelanggan menekan tombol "Pesan Layanan".</p>
                </div>

                <!-- Foto Home (Hero Image) -->
                <div>
                    <label for="hero_image" class="block text-sm font-semibold text-gray-700 mb-1">Foto Home (Banner Utama)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="file" name="hero_image" id="hero_image" accept="image/*"
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Biarkan kosong jika tidak ingin mengubah foto. (Rekomendasi rasio kotak/square, maks 2MB)</p>
                    <?php if(isset($heroImage)): ?>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-slate-500">Foto saat ini:</span>
                            <img src="<?php echo e($heroImage); ?>" alt="Current Hero" class="h-10 w-10 object-cover rounded shadow-sm border border-slate-200">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Logo Laundry -->
                <div>
                    <label for="logo_image" class="block text-sm font-semibold text-gray-700 mb-1">Logo Laundry</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="file" name="logo_image" id="logo_image" accept="image/*"
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Biarkan kosong jika tidak mengubah logo. (Rekomendasi rasio 1:1, maks 1MB)</p>
                    <?php if(isset($logoImage)): ?>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-slate-500">Logo saat ini:</span>
                            <img src="<?php echo e($logoImage); ?>" alt="Current Logo" class="h-10 w-10 object-contain bg-white rounded shadow-sm border border-slate-200">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-brand-500/25 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="bg-gradient-to-br from-brand-600 to-indigo-900 rounded-2xl shadow-xl text-white p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Informasi Kontak</span>
                <h3 class="text-xl font-bold leading-snug">Kontak ini akan dihubungi oleh pembeli/pelanggan di situs depan.</h3>
                <p class="text-brand-100 text-sm leading-relaxed">
                    Sistem akan menyinkronkan nomor WhatsApp Admin ke link tombol "Hubungi Admin" di halaman Lacak Status Cucian, serta nomor WhatsApp Service ke tombol "Pesan Layanan". Pastikan nomor-nomor tersebut aktif agar pesan dari pelanggan dapat terkirim secara langsung.
                </p>
            </div>
            
            <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.003 5.324 5.328 0 11.859 0c3.161.001 6.136 1.23 8.375 3.466 2.238 2.237 3.467 5.214 3.466 8.378-.004 6.528-5.329 11.854-11.859 11.854-.001 0-.001 0 0 0-2.006-.001-3.98-.521-5.733-1.509L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.725 1.45 5.275 0 9.56-4.28 9.563-9.553.002-2.556-.993-4.959-2.799-6.766C16.331 2.478 13.932 1.48 11.378 1.48c-5.281 0-9.57 4.287-9.574 9.561-.001 1.63.435 3.22 1.262 4.636l-.995 3.635 3.719-.976-.143-.092z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-brand-200">WhatsApp Link Preview</p>
                        <a href="https://wa.me/<?php echo e($adminWA); ?>" target="_blank" class="text-sm font-bold text-white hover:underline flex items-center gap-1">
                            wa.me/<?php echo e($adminWA); ?>

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Pengeluaran Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Kategori Pengeluaran
            </h2>
            <button type="button" onclick="openAddKategoriModal()" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-xl transition flex items-center gap-2 shadow-md shadow-brand-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kategori
            </button>
        </div>

        <p class="text-sm text-slate-500 mb-4">Kelola kategori untuk pengeluaran operasional laundry. Kategori nonaktif tidak akan muncul di dropdown pengeluaran.</p>

        <!-- Tabel Kategori -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Digunakan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="kategoriTableBody">
                    <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium text-slate-800"><?php echo e($kategori->nama); ?></td>
                        <td class="px-4 py-3 text-sm text-slate-600"><?php echo e($kategori->deskripsi ?? '-'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php if($kategori->is_active): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Aktif
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                Nonaktif
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">
                            <?php echo e($kategori->pengeluarans_count ?? 0); ?> kali
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editKategori(<?php echo e($kategori->id); ?>, '<?php echo e($kategori->nama); ?>', '<?php echo e($kategori->deskripsi); ?>', <?php echo e($kategori->is_active ? 'true' : 'false'); ?>)" 
                                        class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <form action="<?php echo e(route('admin.kategori-pengeluaran.toggle-status', $kategori)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="p-1.5 <?php echo e($kategori->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50'); ?> rounded-lg transition" 
                                            title="<?php echo e($kategori->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                        <?php if($kategori->is_active): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        <?php else: ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php endif; ?>
                                    </button>
                                </form>

                                <?php if(!$kategori->isUsed()): ?>
                                <form action="<?php echo e(route('admin.kategori-pengeluaran.destroy', $kategori)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="p-1.5 text-slate-300" title="Tidak bisa dihapus karena masih digunakan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($kategoriList->isEmpty()): ?>
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-slate-500 font-medium">Belum ada kategori pengeluaran</p>
            <p class="text-slate-400 text-sm mt-1">Klik tombol "Tambah Kategori" untuk membuat kategori baru</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal Tambah/Edit Kategori -->
    <div id="kategoriModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-800">Tambah Kategori</h3>
                <button type="button" onclick="closeKategoriModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="kategoriForm" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                           placeholder="Contoh: Gaji Karyawan">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                              placeholder="Opsional: Jelaskan kategori ini"></textarea>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                               class="w-4 h-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500">
                        <span class="ml-2 text-sm font-medium text-slate-700">Aktifkan kategori ini</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                    <button type="button" onclick="closeKategoriModal()" class="px-4 py-2.5 bg-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-300 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-brand-500 text-white font-semibold rounded-xl hover:bg-brand-600 transition shadow-md shadow-brand-500/25">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddKategoriModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Kategori';
            document.getElementById('kategoriForm').action = '<?php echo e(route("admin.kategori-pengeluaran.store")); ?>';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('nama').value = '';
            document.getElementById('deskripsi').value = '';
            document.getElementById('is_active').checked = true;
            document.getElementById('kategoriModal').classList.remove('hidden');
        }

        function editKategori(id, nama, deskripsi, isActive) {
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('kategoriForm').action = '<?php echo e(url("admin/kategori-pengeluaran")); ?>/' + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('nama').value = nama;
            document.getElementById('deskripsi').value = deskripsi || '';
            document.getElementById('is_active').checked = isActive;
            document.getElementById('kategoriModal').classList.remove('hidden');
        }

        function closeKategoriModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('kategoriModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeKategoriModal();
            }
        });
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/settings.blade.php ENDPATH**/ ?>