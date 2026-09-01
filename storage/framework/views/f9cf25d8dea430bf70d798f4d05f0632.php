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

    <?php if(auth()->user()->isSuperAdmin()): ?>
    <!-- Manajemen Akun & Ganti Password Section (Khusus Super Admin) -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                    Manajemen Akun & Ganti Password
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                        Khusus Super Admin
                    </span>
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelola seluruh akun pengguna sistem dan penggantian kata sandi yang dilindungi verifikasi 2FA Google Authenticator.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openCreateUserModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-purple-600 hover:bg-purple-700 text-white transition shadow-sm active:scale-95">
                    <i class="fa-solid fa-user-plus text-white"></i>
                    <span>Tambah Pengguna</span>
                </button>
                <button type="button" onclick="openQrModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition shadow-2xs">
                    <i class="fa-solid fa-qrcode text-purple-600"></i>
                    <span>QR 2FA Super Admin</span>
                </button>
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                    <i class="fa-solid fa-shield-halved text-amber-600"></i>
                    <span>2FA Aktif</span>
                </span>
            </div>
        </div>

        <!-- Tabel Daftar Akun -->
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Pengguna</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Divisi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $userList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs <?php echo e($userItem->isSuperAdmin() ? 'bg-gradient-to-br from-purple-600 to-indigo-600 text-white shadow-sm' : ($userItem->role === 'admin' ? 'bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-sm' : 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm')); ?>">
                                    <?php echo e(strtoupper(substr($userItem->name, 0, 2))); ?>

                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800 text-sm"><?php echo e($userItem->name); ?></span>
                                        <?php if($userItem->id === auth()->id()): ?>
                                            <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-md">Akun Anda</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-xs text-slate-400">ID: #<?php echo e($userItem->id); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600 font-mono">
                            <?php echo e($userItem->email); ?>

                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if($userItem->isSuperAdmin()): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                    <i class="fa-solid fa-crown text-[10px]"></i>
                                    Super Admin
                                </span>
                            <?php elseif($userItem->role === 'admin'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                    Admin
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Petugas / Staff
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">
                            <?php if($userItem->division): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 uppercase tracking-wide">
                                    <?php echo e(config('sidebar.division_labels.' . $userItem->division) ?? ucfirst($userItem->division)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-slate-400 text-xs italic">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" onclick="openChangePasswordModal(<?php echo e($userItem->id); ?>, '<?php echo e(addslashes($userItem->name)); ?>', '<?php echo e(addslashes($userItem->email)); ?>', '<?php echo e(addslashes($userItem->role_display_name)); ?>')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 font-semibold text-xs rounded-xl transition border border-brand-200 hover:border-brand-300 shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                                Ganti Password
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400 text-sm">
                            Tidak ada data pengguna ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

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

    <!-- Modal Ganti Password (Dilindungi OTP Google Authenticator) -->
    <div id="changePasswordModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 relative animate-fade-in border border-slate-100">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 leading-tight">Ganti Password Akun</h3>
                        <p class="text-xs text-slate-400">Verifikasi 2FA Google Authenticator</p>
                    </div>
                </div>
                <button type="button" onclick="closeChangePasswordModal()" class="text-slate-400 hover:text-slate-600 transition p-1 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Target User Info Box -->
            <div class="mb-4 p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <p id="targetUserName" class="font-bold text-sm text-slate-800">-</p>
                    <p id="targetUserEmail" class="text-xs text-slate-500 font-mono">-</p>
                </div>
                <span id="targetUserRole" class="text-[11px] font-semibold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700">-</span>
            </div>

            <form id="changePasswordForm" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label for="new_password" class="block text-sm font-semibold text-slate-700 mb-1">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" id="new_password" required minlength="6"
                               class="w-full pl-10 pr-10 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium text-sm"
                               placeholder="Minimal 6 karakter">
                        <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">
                        Konfirmasi Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input type="password" name="password_confirmation" id="new_password_confirmation" required minlength="6"
                               class="w-full pl-10 pr-10 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium text-sm"
                               placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>

                <!-- OTP 2FA Input -->
                <div class="p-3.5 bg-blue-50/70 rounded-2xl border border-blue-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="otp" class="block text-xs font-bold uppercase tracking-wider text-blue-900">
                            Kode OTP Authenticator <span class="text-rose-500">*</span>
                        </label>
                        <button type="button" onclick="openQrModal()" class="text-xs text-blue-700 hover:text-blue-800 font-bold flex items-center gap-1 hover:underline">
                            <i class="fa-solid fa-qrcode"></i> Lihat QR
                        </button>
                    </div>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-500">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                        <input type="text" name="otp" id="otp" required maxlength="6" inputmode="numeric" pattern="[0-9]*"
                               class="w-full pl-9 pr-4 py-2.5 bg-white border border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 font-mono font-bold tracking-widest text-base placeholder:tracking-normal placeholder:font-normal placeholder:text-xs"
                               placeholder="6 digit kode OTP">
                    </div>
                    <p class="text-[11px] text-blue-700/80 leading-snug">
                        Buka aplikasi Google Authenticator Admin di HP untuk mengambil 6 digit kode keamanan.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition text-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-xl transition shadow-md shadow-brand-500/25 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lihat QR Google Authenticator Admin -->
    <div id="adminQrModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 relative animate-fade-in border border-slate-100">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 leading-tight">Google Authenticator 2FA</h3>
                        <p class="text-xs text-slate-400">Kunci Keamanan Akun Admin</p>
                    </div>
                </div>
                <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-600 transition p-1 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-100 flex flex-col items-center">
                    <div class="p-2 bg-white rounded-2xl border border-slate-200 shadow-xs mb-2">
                        <img src="<?php echo e($adminQrCodeUrl); ?>" alt="QR 2FA Admin" class="w-40 h-40 object-contain rounded-lg">
                    </div>
                    <span class="text-xs text-blue-700 font-semibold flex items-center gap-1.5 mt-1">
                        <i class="fa-solid fa-camera"></i> Scan dengan Google Authenticator di HP
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Kunci Manual (Setup Key)
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-xs font-bold text-blue-700 tracking-wider flex-1 truncate select-all">
                            <?php echo e($adminFormattedSecret); ?>

                        </div>
                        <button type="button" onclick="copyAdminSecret('<?php echo e($adminRawSecret); ?>', this)" 
                                class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition shrink-0 active:scale-95">
                            <i class="fa-regular fa-copy mr-1"></i> Salin
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Masukkan kunci di atas pada Google Authenticator jika tidak bisa memindai QR code.
                    </p>
                </div>

                <div class="pt-2 border-t border-slate-100 flex justify-end">
                    <button type="button" onclick="closeQrModal()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengguna Baru -->
    <div id="createUserModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-slate-100 animate-fade-in">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Tambah Akun Pengguna</h3>
                        <p class="text-xs text-slate-400">Buat akun Super Admin, Admin, atau Petugas baru</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateUserModal()" class="text-slate-400 hover:text-slate-600 transition p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="<?php echo e(route('admin.users.store')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="create_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="create_name" required placeholder="Contoh: Budi Super Admin"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 transition">
                </div>

                <div>
                    <label for="create_email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="create_email" required placeholder="Contoh: user@laundry.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 transition">
                </div>

                <div>
                    <label for="create_password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Kata Sandi Awal</label>
                    <input type="password" name="password" id="create_password" required minlength="6" placeholder="Minimal 6 karakter"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 transition">
                </div>

                <div>
                    <label for="create_role" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Peran / Role</label>
                    <select name="role" id="create_role" onchange="handleCreateUserRoleChange(this.value)" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 transition">
                        <option value="super_admin">⭐ Super Admin (Akses Penuh Seluruh Sistem)</option>
                        <option value="admin">🛡️ Admin (Administrator Portal)</option>
                        <option value="staff">👤 Petugas / Staff (Operasional Lapangan)</option>
                    </select>
                </div>

                <div id="divisionField" class="hidden">
                    <label for="create_division" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Divisi Petugas</label>
                    <select name="division" id="create_division"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 transition">
                        <option value="washing">Washing (Pencucian)</option>
                        <option value="ironing">Ironing (Setrika)</option>
                        <option value="packing">Packing (Pengemasan)</option>
                        <option value="customer_service">Customer Service (Kasir / POS)</option>
                        <option value="inventory">Inventory (Gudang)</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeCreateUserModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-sm transition shadow-sm active:scale-95">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateUserModal() {
            document.getElementById('create_name').value = '';
            document.getElementById('create_email').value = '';
            document.getElementById('create_password').value = '';
            document.getElementById('create_role').value = 'super_admin';
            handleCreateUserRoleChange('super_admin');
            document.getElementById('createUserModal').classList.remove('hidden');
        }

        function closeCreateUserModal() {
            document.getElementById('createUserModal').classList.add('hidden');
        }

        function handleCreateUserRoleChange(role) {
            const divisionField = document.getElementById('divisionField');
            if (role === 'staff') {
                divisionField.classList.remove('hidden');
            } else {
                divisionField.classList.add('hidden');
            }
        }

        document.getElementById('createUserModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateUserModal();
            }
        });

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

        // Change Password Functions
        function openChangePasswordModal(userId, userName, userEmail, userRole) {
            document.getElementById('targetUserName').textContent = userName;
            document.getElementById('targetUserEmail').textContent = userEmail;
            document.getElementById('targetUserRole').textContent = userRole;
            document.getElementById('changePasswordForm').action = "<?php echo e(url('admin/users')); ?>/" + userId + "/password";
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';
            document.getElementById('otp').value = '';
            document.getElementById('changePasswordModal').classList.remove('hidden');
        }

        function closeChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.add('hidden');
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const openIcon = button.querySelector('.eye-open');
            const closedIcon = button.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                openIcon.classList.add('hidden');
                closedIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                openIcon.classList.remove('hidden');
                closedIcon.classList.add('hidden');
            }
        }

        // Close change password modal when clicking backdrop
        document.getElementById('changePasswordModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeChangePasswordModal();
            }
        });

        // QR Modal Functions
        function openQrModal() {
            document.getElementById('adminQrModal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('adminQrModal').classList.add('hidden');
        }

        document.getElementById('adminQrModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeQrModal();
            }
        });

        function copyAdminSecret(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600 mr-1"></i> Tersalin!';
                btn.classList.add('bg-emerald-50', 'text-emerald-700');
                setTimeout(() => {
                    btn.innerHTML = orig;
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700');
                }, 2000);
            }).catch(err => {
                alert('Kunci: ' + text);
            });
        }
    </script>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/settings.blade.php ENDPATH**/ ?>