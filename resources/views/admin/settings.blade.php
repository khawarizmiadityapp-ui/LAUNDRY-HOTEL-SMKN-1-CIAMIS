{{-- resources/views/admin/settings.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan - Bening Laundry')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-up">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Pengaturan Aplikasi</h1>
            <p class="text-gray-500 mt-1">Kelola QRIS pembayaran, kontak WhatsApp, SEO &amp; Google, maintenance, kategori, dan akun pengguna secara sentral.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="#qris-config" data-settings-jump="qris-config" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                Ganti QRIS &amp; Transfer
            </a>
            @if(auth()->user()->isSuperAdmin())
            <a href="#maintenance-panel" data-settings-jump="maintenance-panel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766" /></svg>
                Maintenance
            </a>
            <a href="#system-cache-panel" data-settings-jump="system-cache-panel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                Cache &amp; Optimasi
            </a>
            @endif
            <a href="#general-config" data-settings-jump="general-config" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Kontak &amp; Branding
            </a>
            <a href="#seo-config" data-settings-jump="seo-config" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 shadow-xs">
                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <span>SEO &amp; Google</span>
                <span class="relative flex h-2 w-2" title="{{ !empty($seoConfig['google_verification']) ? 'Google Verification Aktif' : 'Belum Diverifikasi GSC' }}">
                    @if(!empty($seoConfig['google_verification']))
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    @else
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    @endif
                </span>
            </a>
            <a href="#user-management-panel" data-settings-jump="user-management-panel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                Manajemen Akun
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 shadow-xs">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div class="text-xs sm:text-sm space-y-1">
                <p class="font-bold">Gagal menyimpan perubahan. Periksa input berikut:</p>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Dedicated QRIS & Non-Cash Settings Grid (Super Admin & Admin) -->
    <div id="qris-config" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-md border border-indigo-100 p-6">
            <div class="flex items-center justify-between gap-2 pb-3 mb-4 border-b border-slate-100">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    </span>
                    Konfigurasi QRIS &amp; Pembayaran
                </h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Super Admin &amp; Admin
                </span>
            </div>

            <form action="{{ route('admin.settings.qris') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="qris_merchant_name" class="block text-xs font-semibold text-gray-700 mb-1">Nama Merchant QRIS</label>
                        <input type="text" name="qris_merchant_name" id="qris_merchant_name" value="{{ old('qris_merchant_name', $qrisConfig['merchant_name'] ?? 'BENING LAUNDRY - SMKN 1 CIAMIS') }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="BENING LAUNDRY - SMKN 1 CIAMIS">
                    </div>
                    <div>
                        <label for="qris_nmid" class="block text-xs font-semibold text-gray-700 mb-1">NMID / ID Merchant QRIS</label>
                        <input type="text" name="qris_nmid" id="qris_nmid" value="{{ old('qris_nmid', $qrisConfig['nmid'] ?? 'ID1026000109281') }}"
                               class="w-full px-3.5 py-2.5 text-sm font-mono border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="ID1026000109281">
                    </div>
                </div>

                <div>
                    <label for="qris_image" class="block text-xs font-semibold text-gray-700 mb-1">Upload Gambar Barcode QRIS Resmi Toko (JPG/PNG/WEBP, maks 5MB)</label>
                    <input type="file" name="qris_image" id="qris_image" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewQrisFile(this)"
                           class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-[11px] text-slate-400 mt-1">Upload gambar barcode QRIS resmi toko (dari bank/QRIS merchant), atau biarkan kosong untuk generate QR otomatis dari Payload/NMID.</p>
                    @if(!empty($qrisConfig['qris_image']))
                        <div class="mt-2.5 flex items-center gap-3 p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                            <img src="{{ $qrisConfig['qris_image'] }}" alt="QRIS Toko" class="h-12 w-12 object-contain bg-white rounded-lg border border-slate-200 p-1">
                            <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 font-semibold cursor-pointer">
                                <input type="checkbox" name="reset_qris_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                Hapus gambar kustom &amp; gunakan QRIS otomatis
                            </label>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="qris_payload" class="block text-xs font-semibold text-gray-700 mb-1">Payload String QRIS Resmi / EMVCo (Opsional)</label>
                    <textarea name="qris_payload" id="qris_payload" rows="2" maxlength="1000"
                              class="w-full px-3.5 py-2 text-xs font-mono border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800"
                              placeholder="Tempel string QRIS mentah jika tidak mengupload gambar barcode (opsional)...">{{ old('qris_payload', $qrisConfig['qris_payload'] ?? '') }}</textarea>
                </div>

                <div>
                    <label for="bank_transfer_info" class="block text-xs font-semibold text-gray-700 mb-1">Info Rekening Transfer Bank / E-Wallet</label>
                    <input type="text" name="bank_transfer_info" id="bank_transfer_info" value="{{ old('bank_transfer_info', $qrisConfig['bank_info'] ?? '') }}"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                           placeholder="Contoh: BCA 1234567890 a.n. Bening Laundry SMKN 1 Ciamis">
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-md shadow-indigo-600/25 transition inline-flex items-center gap-2 text-sm active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Konfigurasi QRIS
                    </button>
                </div>
            </form>
        </div>

        <!-- QRIS Live Preview Card -->
        <div class="bg-gradient-to-br from-brand-600 to-indigo-900 rounded-2xl shadow-xl text-white p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">QRIS Pembayaran Aktif</span>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ !empty($qrisConfig['qris_image']) ? 'bg-emerald-400/20 text-emerald-100 border border-emerald-300/30' : 'bg-white/15 text-blue-100' }}">
                        {{ !empty($qrisConfig['qris_image']) ? 'Gambar QRIS Kustom' : (!empty($qrisConfig['qris_payload']) ? 'Payload QRIS Kustom' : 'Generator QRIS Otomatis') }}
                    </span>
                </div>
                <h3 class="text-xl font-bold leading-snug">Barcode Pembayaran QRIS Terintegrasi</h3>
                <p class="text-brand-100 text-sm leading-relaxed">
                    Perubahan QRIS langsung disinkronkan ke seluruh sistem: <strong>Kasir (POS)</strong>, <strong>Pengambilan Cucian</strong>, <strong>Manajemen Pembayaran</strong>, dan <strong>Struk / Invoice Pelanggan</strong>.
                </p>

                <!-- Preview Kartu QRIS -->
                <div class="bg-white text-slate-800 rounded-2xl p-4 shadow-lg mt-4 flex flex-col sm:flex-row items-center gap-4">
                    <div class="p-2 bg-slate-50 border border-slate-200 rounded-xl shrink-0">
                        <img id="settingsQrisPreviewImg" src="{{ $qrisConfig['display_qr_url'] ?? '' }}" alt="QRIS Preview" class="w-28 h-28 object-contain">
                    </div>
                    <div class="text-center sm:text-left min-w-0 flex-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-600 text-[10px] font-extrabold uppercase tracking-wider border border-rose-200 mb-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg> QRIS CONNECTED
                        </div>
                        <p class="text-sm font-extrabold text-slate-900 truncate">{{ $qrisConfig['merchant_name'] ?? 'BENING LAUNDRY - SMKN 1 CIAMIS' }}</p>
                        <p class="text-xs font-mono text-slate-500 mt-0.5">NMID: {{ $qrisConfig['nmid'] ?? 'ID1026000109281' }}</p>
                        <p class="text-[11px] text-slate-500 mt-1.5 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <span class="truncate">{{ $qrisConfig['bank_info'] ?? '-' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-2 text-xs">
                <span class="text-brand-200">Cek langsung di modul transaksi:</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.pembayaran.index') }}" class="px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 text-white font-semibold transition">Menu Pembayaran</a>
                    <a href="{{ route('admin.pos.index') }}" class="px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 text-white font-semibold transition">Menu POS Kasir</a>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isSuperAdmin())
    @php
        $maint = $maintenanceConfig ?? get_maintenance_config();
    @endphp
    <!-- Maintenance Notice Control (Khusus Super Admin) -->
    <div id="maintenance-panel" class="bg-white rounded-2xl shadow-md border {{ $maint['enabled'] ? 'border-amber-300 ring-2 ring-amber-400/30' : 'border-purple-100' }} p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl {{ $maint['enabled'] ? 'bg-amber-100 text-amber-700' : 'bg-purple-50 text-purple-600' }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    </svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-800">Pemberitahuan Maintenance</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                            Khusus Super Admin
                        </span>
                        @if($maint['enabled'])
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Sedang Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Nyalakan pemberitahuan agar pelanggan, petugas, dan admin mengetahui saat sistem sedang ada perbaikan.</p>
                </div>
            </div>
            @if(!empty($maint['updated_at']))
                <div class="text-[11px] text-slate-400 text-left sm:text-right shrink-0">
                    Terakhir diubah: <span class="font-semibold text-slate-600">{{ $maint['updated_at'] }}</span>
                    @if(!empty($maint['updated_by']))
                        <br>oleh <span class="font-semibold text-slate-600">{{ $maint['updated_by'] }}</span>
                    @endif
                </div>
            @endif
        </div>

        <form action="{{ route('admin.settings.maintenance') }}" method="POST" class="space-y-4">
            @csrf

            <div x-data="{ maintEnabled: {{ $maint['enabled'] ? 'true' : 'false' }} }" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <p class="text-sm font-bold text-slate-800">Status Pemberitahuan Maintenance</p>
                    <p class="text-xs text-slate-500">Aktifkan untuk menampilkan pemberitahuan sedang ada perbaikan kepada seluruh pengguna.</p>
                </div>
                <label class="inline-flex items-center gap-3 cursor-pointer shrink-0 select-none">
                    <input type="hidden" name="enabled" value="0">
                    <input type="checkbox" name="enabled" value="1" id="maintenance_enabled" x-model="maintEnabled" class="sr-only peer" {{ $maint['enabled'] ? 'checked' : '' }}>
                    <div class="relative w-12 h-6 bg-slate-300 rounded-full peer peer-checked:bg-amber-500 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 shadow-inner"></div>
                    <span class="text-xs font-bold uppercase tracking-wider"
                          :class="maintEnabled ? 'text-amber-700' : 'text-slate-600'"
                          x-text="maintEnabled ? 'Nyala (ON)' : 'Mati (OFF)'">
                        {{ $maint['enabled'] ? 'Nyala (ON)' : 'Mati (OFF)' }}
                    </span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="maintenance_title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Judul Pemberitahuan</label>
                    <input type="text" name="title" id="maintenance_title" value="{{ $maint['title'] }}" maxlength="120"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 text-slate-800 font-medium"
                           placeholder="Pemberitahuan Perbaikan Sistem (Maintenance)">
                </div>

                <div>
                    <label for="maintenance_eta" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Estimasi Selesai (Opsional)</label>
                    <input type="text" name="eta" id="maintenance_eta" value="{{ $maint['eta'] }}" maxlength="100"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 text-slate-800 font-medium"
                           placeholder="Contoh: 17 Nov 2026, 10:00 WIB atau Sekitar 30 menit">
                </div>
            </div>

            <div>
                <label for="maintenance_message" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Pesan Pemberitahuan</label>
                <textarea name="message" id="maintenance_message" rows="2" maxlength="600"
                          class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 text-slate-800"
                          placeholder="Jelaskan kepada pengguna bahwa sistem sedang ada perbaikan...">{{ $maint['message'] }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Mode Tampilan</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="cursor-pointer flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white transition">
                        <input type="radio" name="mode" value="banner" {{ ($maint['mode'] ?? 'banner') === 'banner' ? 'checked' : '' }} class="mt-0.5 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Banner Pemberitahuan (Direkomendasikan)</span>
                            <span class="text-[11px] text-slate-500">Menampilkan banner perbaikan di semua halaman, aplikasi tetap bisa digunakan normal.</span>
                        </div>
                    </label>
                    <label class="cursor-pointer flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white transition">
                        <input type="radio" name="mode" value="full" {{ ($maint['mode'] ?? 'banner') === 'full' ? 'checked' : '' }} class="mt-0.5 text-rose-600 focus:ring-rose-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Maintenance Penuh (Kunci Akses)</span>
                            <span class="text-[11px] text-slate-500">Tampilkan layar maintenance ke semua pengunjung/petugas/admin. Hanya Super Admin yang bisa akses.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-600/20 transition inline-flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Pengaturan Maintenance
                </button>
            </div>
        </form>
    </div>

    <!-- System Cache & Optimization Panel (Khusus Super Admin) -->
    @php
        $cacheInfo = $cacheStatus ?? [
            'config_cached' => app()->configurationIsCached(),
            'routes_cached' => app()->routesAreCached(),
            'compiled_views_count' => count(glob(storage_path('framework/views/*.php')) ?: []),
        ];
    @endphp
    <div id="system-cache-panel" class="bg-white rounded-2xl shadow-md border border-emerald-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-800">Cache &amp; Optimasi Sistem (Artisan)</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                            Khusus Super Admin
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Jalankan pembersihan dan caching Laravel langsung dari dashboard tanpa perlu membuka terminal SSH.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold shrink-0">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border {{ $cacheInfo['config_cached'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $cacheInfo['config_cached'] ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    Config: {{ $cacheInfo['config_cached'] ? 'Cached' : 'Dinamis' }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border {{ $cacheInfo['routes_cached'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $cacheInfo['routes_cached'] ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    Route: {{ $cacheInfo['routes_cached'] ? 'Cached' : 'Dinamis' }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border bg-indigo-50 text-indigo-700 border-indigo-200">
                    Views: {{ $cacheInfo['compiled_views_count'] }} file
                </span>
            </div>
        </div>

        <!-- Jalankan Semua Sekaligus -->
        <div class="mb-4 p-4 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-extrabold text-emerald-950">Optimasi Penuh Sekali Klik (Setelah Deploy / Update)</p>
                <p class="text-xs text-emerald-800/80 mt-0.5">Menjalankan berurutan: <code class="font-mono bg-white/80 px-1.5 py-0.5 rounded border border-emerald-200">optimize:clear</code> &rarr; <code class="font-mono bg-white/80 px-1.5 py-0.5 rounded border border-emerald-200">config:cache</code> &rarr; <code class="font-mono bg-white/80 px-1.5 py-0.5 rounded border border-emerald-200">route:cache</code> &rarr; <code class="font-mono bg-white/80 px-1.5 py-0.5 rounded border border-emerald-200">view:cache</code>.</p>
            </div>
            <form action="{{ route('admin.settings.cache') }}" method="POST" class="shrink-0">
                @csrf
                <input type="hidden" name="action" value="optimize_all">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition inline-flex items-center justify-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    Jalankan Semua (4 Perintah)
                </button>
            </form>
        </div>

        <!-- Tombol Satuan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3.5">
            <div class="p-4 rounded-xl border border-rose-200/80 bg-rose-50/40 flex flex-col justify-between gap-3">
                <div>
                    <code class="text-[11px] font-mono font-bold text-rose-700 bg-white px-2 py-0.5 rounded-md border border-rose-200">php artisan optimize:clear</code>
                    <h3 class="text-sm font-bold text-slate-800 mt-2">Bersihkan Semua Cache</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Hapus seluruh cache config, route, compiled view, dan cache aplikasi.</p>
                </div>
                <form action="{{ route('admin.settings.cache') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="optimize_clear">
                    <button type="submit" class="w-full px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center justify-center gap-1.5 shadow-xs active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Optimize Clear
                    </button>
                </form>
            </div>

            <div class="p-4 rounded-xl border border-indigo-200/80 bg-indigo-50/40 flex flex-col justify-between gap-3">
                <div>
                    <code class="text-[11px] font-mono font-bold text-indigo-700 bg-white px-2 py-0.5 rounded-md border border-indigo-200">php artisan config:cache</code>
                    <h3 class="text-sm font-bold text-slate-800 mt-2">Cache Konfigurasi</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Gabungkan dan cache file <span class="font-mono">config/*</span> &amp; <span class="font-mono">.env</span> agar lebih cepat.</p>
                </div>
                <form action="{{ route('admin.settings.cache') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="config_cache">
                    <button type="submit" class="w-full px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center justify-center gap-1.5 shadow-xs active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Config Cache
                    </button>
                </form>
            </div>

            <div class="p-4 rounded-xl border border-sky-200/80 bg-sky-50/40 flex flex-col justify-between gap-3">
                <div>
                    <code class="text-[11px] font-mono font-bold text-sky-700 bg-white px-2 py-0.5 rounded-md border border-sky-200">php artisan route:cache</code>
                    <h3 class="text-sm font-bold text-slate-800 mt-2">Cache Routing</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Compile seluruh rute URL agar resolusi route jauh lebih ringan.</p>
                </div>
                <form action="{{ route('admin.settings.cache') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="route_cache">
                    <button type="submit" class="w-full px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center justify-center gap-1.5 shadow-xs active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        Route Cache
                    </button>
                </form>
            </div>

            <div class="p-4 rounded-xl border border-amber-200/80 bg-amber-50/40 flex flex-col justify-between gap-3">
                <div>
                    <code class="text-[11px] font-mono font-bold text-amber-800 bg-white px-2 py-0.5 rounded-md border border-amber-200">php artisan view:cache</code>
                    <h3 class="text-sm font-bold text-slate-800 mt-2">Cache Template Blade</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Pre-compile seluruh file Blade di <span class="font-mono">resources/views</span>.</p>
                </div>
                <form action="{{ route('admin.settings.cache') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="view_cache">
                    <button type="submit" class="w-full px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center justify-center gap-1.5 shadow-xs active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>
                        View Cache
                    </button>
                </form>
            </div>
        </div>

        @if(session('cache_output'))
            <div class="mt-4 rounded-xl bg-slate-900 border border-slate-800 p-4 text-left">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Output Terminal Artisan</span>
                    <span class="text-[10px] font-mono text-emerald-400">OK</span>
                </div>
                <pre class="text-xs font-mono text-emerald-300 whitespace-pre-wrap break-words leading-relaxed">{{ session('cache_output') }}</pre>
            </div>
        @endif
    </div>
    @endif

    <!-- Settings Card Grid (Kontak WA & Branding) -->
    <div id="general-config" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Settings Form Card -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Konfigurasi Umum (Kontak &amp; Tampilan)
            </h2>
            
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <!-- Nomor WA Pihak Bisa Dihubungi Pembeli -->
                <div>
                    <label for="admin_wa" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Admin (Contact Person)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <input type="text" name="admin_wa" id="admin_wa" value="{{ $adminWA }}" required
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
                        <input type="text" name="service_wa" id="service_wa" value="{{ $serviceWA }}" required
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
                    @if(isset($heroImage))
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-slate-500">Foto saat ini:</span>
                            <img src="{{ $heroImage }}" alt="Current Hero" class="h-10 w-10 object-cover rounded shadow-sm border border-slate-200">
                        </div>
                    @endif
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
                    @if(isset($logoImage))
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-slate-500">Logo saat ini:</span>
                            <img src="{{ $logoImage }}" alt="Current Logo" class="h-10 w-10 object-contain bg-white rounded shadow-sm border border-slate-200">
                        </div>
                    @endif
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

        <!-- WhatsApp Info Card -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl text-white p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <span class="bg-white/15 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Informasi Kontak Terintegrasi</span>
                <h3 class="text-xl font-bold leading-snug">Sinkronisasi WhatsApp Layanan &amp; Branding</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Nomor WhatsApp Admin &amp; Service otomatis tersambung ke tombol chat pelanggan pada halaman depan, halaman tracking pesanan, layar maintenance, dan invoice/nota digital.
                </p>
            </div>
            
            <div class="mt-6 pt-5 border-t border-white/10 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.003 5.324 5.328 0 11.859 0c3.161.001 6.136 1.23 8.375 3.466 2.238 2.237 3.467 5.214 3.466 8.378-.004 6.528-5.329 11.854-11.859 11.854-.001 0-.001 0 0 0-2.006-.001-3.98-.521-5.733-1.509L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.725 1.45 5.275 0 9.56-4.28 9.563-9.553.002-2.556-.993-4.959-2.799-6.766C16.331 2.478 13.932 1.48 11.378 1.48c-5.281 0-9.57 4.287-9.574 9.561-.001 1.63.435 3.22 1.262 4.636l-.995 3.635 3.719-.976-.143-.092z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">WhatsApp Admin Preview</p>
                        <a href="https://wa.me/{{ $adminWA }}" target="_blank" class="text-sm font-bold text-white hover:underline flex items-center gap-1">
                            wa.me/{{ $adminWA }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400">WhatsApp Service</p>
                    <a href="https://wa.me/{{ $serviceWA }}" target="_blank" class="text-sm font-bold text-emerald-400 hover:underline">wa.me/{{ $serviceWA }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO & Google Search Console Configuration -->
    @php
        $seoTitleValue = old('seo_title', $seoConfig['title'] ?? '');
        $seoDescValue = old('seo_description', $seoConfig['description'] ?? '');
        $seoKeywordsValue = old('seo_keywords', $seoConfig['keywords'] ?? '');
        $seoVerificationValue = old('seo_google_verification', $seoConfig['google_verification'] ?? '');
    @endphp
    <div id="seo-config"
         x-data="{
             title: @js($seoTitleValue),
             description: @js($seoDescValue),
             verification: @js($seoVerificationValue),
             extractedVerification() {
                 const raw = (this.verification || '').trim();
                 if (!raw) return '';
                 const match = raw.match(/content\s*=\s*['\x22]([^'\x22]+)['\x22]/i);
                 return match ? match[1].trim() : raw;
             }
         }"
         class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-up scroll-mt-6 rounded-2xl transition-all duration-500">
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Pengaturan SEO &amp; Google Search Console
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Atur tampilan website di hasil pencarian Google, kata kunci lokal Ciamis, dan kode verifikasi kepemilikan situs.</p>
                    </div>
                </div>
                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                    Super Admin &amp; Admin
                </span>
            </div>

            <form action="{{ route('admin.settings.seo') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="seo_title" class="block text-sm font-semibold text-gray-700">Judul SEO (Meta Title)</label>
                        <span class="text-[11px] transition-colors"
                              :class="title.length >= 35 && title.length <= 65 ? 'text-emerald-600 font-semibold' : (title.length > 65 ? 'text-amber-600 font-semibold' : 'text-slate-400 font-medium')">
                            <span x-text="title.length">{{ mb_strlen($seoTitleValue) }}</span>/120 karakter (ideal 50-65)
                        </span>
                    </div>
                    <input type="text"
                           name="seo_title"
                           id="seo_title"
                           maxlength="120"
                           required
                           x-model="title"
                           value="{{ $seoTitleValue }}"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-slate-800 text-sm font-medium transition-all"
                           placeholder="Contoh: Bening Laundry SMKN 1 Ciamis — Jasa Laundry Profesional di Ciamis">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="seo_description" class="block text-sm font-semibold text-gray-700">Deskripsi SEO (Meta Description)</label>
                        <span class="text-[11px] transition-colors"
                              :class="description.length >= 90 && description.length <= 165 ? 'text-emerald-600 font-semibold' : (description.length > 165 ? 'text-amber-600 font-semibold' : 'text-slate-400 font-medium')">
                            <span x-text="description.length">{{ mb_strlen($seoDescValue) }}</span>/300 karakter (ideal 120-160)
                        </span>
                    </div>
                    <textarea name="seo_description"
                              id="seo_description"
                              rows="3"
                              maxlength="300"
                              required
                              x-model="description"
                              class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-slate-800 text-sm leading-relaxed transition-all"
                              placeholder="Jelaskan keunggulan layanan laundry di Ciamis agar menarik diklik di Google...">{{ $seoDescValue }}</textarea>
                </div>

                <div>
                    <label for="seo_keywords" class="block text-sm font-semibold text-gray-700 mb-1">Kata Kunci / Keywords (Opsional)</label>
                    <input type="text"
                           name="seo_keywords"
                           id="seo_keywords"
                           maxlength="300"
                           value="{{ $seoKeywordsValue }}"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-slate-800 text-sm transition-all"
                           placeholder="laundry ciamis, jasa laundry ciamis, bening laundry smkn 1 ciamis">
                    <p class="text-xs text-slate-400 mt-1">Pisahkan dengan tanda koma. Utamakan kata kunci lokal seperti nama layanan dan kota Ciamis.</p>
                </div>

                <div>
                    <label for="seo_google_verification" class="block text-sm font-semibold text-gray-700 mb-1">Kode Verifikasi Google Search Console</label>
                    <input type="text"
                           name="seo_google_verification"
                           id="seo_google_verification"
                           maxlength="255"
                           x-model="verification"
                           value="{{ $seoVerificationValue }}"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-slate-800 text-sm font-mono transition-all"
                           placeholder="Tempel token verifikasi atau tag <meta name=&quot;google-site-verification&quot; ...>">
                    <div x-show="extractedVerification() !== ''"
                         x-transition.opacity.duration.200ms
                         class="mt-1.5 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px] text-emerald-700">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Token aktif: <code class="font-bold" x-text="extractedVerification()"></code></span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Isi dari menu Google Search Console &rarr; HTML Tag verification. Boleh menempelkan tokennya saja atau satu baris tag meta lengkap.</p>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 hover:-translate-y-0.5 active:scale-[0.98] text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-teal-600/20 transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Konfigurasi SEO
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Google Search Preview & Technical SEO Diagnostics -->
        <div class="bg-gradient-to-br from-slate-900 via-brand-950 to-slate-900 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white flex flex-col justify-between gap-5">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-bold tracking-wide uppercase text-emerald-300">Preview Hasil Pencarian Google</span>
                    <span x-show="extractedVerification() !== ''"
                          x-transition.opacity.duration.250ms
                          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Google Verification Terpasang
                    </span>
                    <span x-show="extractedVerification() === ''"
                          x-transition.opacity.duration.250ms
                          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-200 border border-amber-400/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                        Belum Diverifikasi GSC
                    </span>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-5 text-slate-800 shadow-lg border border-slate-200/80 space-y-2 transition-transform duration-300 hover:-translate-y-0.5">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ $seoConfig['favicon'] ?? url('/images/logobening.jpeg') }}" alt="Favicon Preview" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                        <div class="min-w-0 leading-tight">
                            <p class="text-xs font-semibold text-slate-800 truncate">Bening Laundry SMKN 1 Ciamis</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ url('/') }}</p>
                        </div>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-blue-700 leading-snug line-clamp-2" x-text="title || 'Bening Laundry SMKN 1 Ciamis'">
                        {{ $seoTitleValue }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-3" x-text="description || 'Layanan laundry kiloan & satuan cepat, bersih, wangi, dan rapi di Ciamis.'">
                        {{ $seoDescValue }}
                    </p>
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <div class="bg-white/5 hover:bg-white/10 transition-colors border border-white/10 rounded-xl p-3">
                        <p class="font-bold text-emerald-300 mb-0.5">Schema JSON-LD Aktif</p>
                        <p class="text-slate-300 leading-relaxed">Halaman depan otomatis mengirim data terstruktur <code class="text-white">DryCleaningOrLaundry</code> beserta daftar layanan aktif ke Google.</p>
                    </div>
                    <div class="bg-white/5 hover:bg-white/10 transition-colors border border-white/10 rounded-xl p-3">
                        <p class="font-bold text-sky-300 mb-0.5">Privasi Halaman Internal</p>
                        <p class="text-slate-300 leading-relaxed">Halaman admin, petugas, login, dan struk tracking ditandai <code class="text-white">noindex, nofollow</code> agar data pelanggan aman.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/15 pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs text-slate-300">
                    <p class="font-semibold text-white">Endpoint Sitemap &amp; Crawler</p>
                    <p>Submit URL <code class="text-emerald-300">sitemap.xml</code> ke Google Search Console setelah domain HTTPS aktif.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('seo.sitemap') }}" target="_blank" class="group px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 hover:-translate-y-0.5 text-xs font-bold text-white transition-all duration-200 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-300 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                        </svg>
                        sitemap.xml
                    </a>
                    <a href="{{ route('seo.robots') }}" target="_blank" class="group px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 hover:-translate-y-0.5 text-xs font-bold text-white transition-all duration-200 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-sky-300 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                        </svg>
                        robots.txt
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Pengeluaran Section -->
    <div id="kategori-panel" class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
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
        <div class="overflow-x-auto min-h-[250px]">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Digunakan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="kategoriTableBody">
                    @foreach($kategoriList as $kategori)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $kategori->nama }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $kategori->deskripsi ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($kategori->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">
                            {{ $kategori->pengeluarans_count ?? 0 }} kali
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="relative inline-block text-left">
                                <button type="button" 
                                        onclick="toggleDropdown('dropdown-kategori-{{ $kategori->id }}')" 
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition focus:outline-none"
                                        title="Menu Aksi">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                                    </svg>
                                </button>
                                
                                <div id="dropdown-kategori-{{ $kategori->id }}" 
                                     class="hidden absolute right-0 top-full mt-1 w-44 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5 text-left">
                                    {{-- Edit --}}
                                    <button type="button" 
                                            onclick="editKategori({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}', '{{ addslashes($kategori->deskripsi ?? '') }}', {{ $kategori->is_active ? 'true' : 'false' }}); document.getElementById('dropdown-kategori-{{ $kategori->id }}').classList.add('hidden')" 
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Kategori
                                    </button>

                                    {{-- Toggle Status --}}
                                    <form action="{{ route('admin.kategori-pengeluaran.toggle-status', $kategori) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold {{ $kategori->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors">
                                            @if($kategori->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            Nonaktifkan
                                            @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Aktifkan
                                            @endif
                                        </button>
                                    </form>

                                    <div class="h-px bg-slate-100 my-1"></div>

                                    {{-- Hapus --}}
                                    @if(!$kategori->isUsed())
                                    <form action="{{ route('admin.kategori-pengeluaran.destroy', $kategori) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                    @else
                                    <div class="flex items-center gap-2 px-4 py-2 text-[11px] text-slate-400 cursor-not-allowed" title="Tidak bisa dihapus karena masih digunakan">
                                        <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Sedang Digunakan
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($kategoriList->isEmpty())
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-slate-500 font-medium">Belum ada kategori pengeluaran</p>
            <p class="text-slate-400 text-sm mt-1">Klik tombol "Tambah Kategori" untuk membuat kategori baru</p>
        </div>
        @endif
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Manajemen Akun & Ganti Password Section -->
    <div id="user-management-panel" class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                    Manajemen Akun & Ganti Password
                    @if(auth()->user()->isSuperAdmin())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                            Super Admin
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">
                            Administrator
                        </span>
                    @endif
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelola seluruh akun pengguna sistem dan penggantian kata sandi yang dilindungi verifikasi 2FA Google Authenticator.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openCreateUserModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-purple-600 hover:bg-purple-700 text-white transition shadow-sm active:scale-95">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    <span>Tambah Pengguna</span>
                </button>
                <button type="button" onclick="openQrModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition shadow-2xs">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    <span>QR 2FA {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}</span>
                </button>
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
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
                    @forelse($userList as $userItem)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs {{ $userItem->isSuperAdmin() ? 'bg-gradient-to-br from-purple-600 to-indigo-600 text-white shadow-sm' : ($userItem->role === 'admin' ? 'bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-sm' : 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm') }}">
                                    {{ strtoupper(substr($userItem->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800 text-sm">{{ $userItem->name }}</span>
                                        @if($userItem->id === auth()->id())
                                             <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-md">Akun Anda</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-slate-400">ID: #{{ $userItem->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600 font-mono">
                            {{ $userItem->email }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($userItem->isSuperAdmin())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                    <svg class="w-3 h-3 text-purple-600" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                                    Super Admin
                                </span>
                            @elseif($userItem->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Petugas / Staff
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">
                            @if($userItem->division)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 uppercase tracking-wide">
                                    {{ config('sidebar.division_labels.' . $userItem->division) ?? ucfirst($userItem->division) }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(auth()->user()->isSuperAdmin() || !$userItem->isSuperAdmin())
                                <button type="button" onclick="openChangePasswordModal({{ $userItem->id }}, '{{ addslashes($userItem->name) }}', '{{ addslashes($userItem->email) }}', '{{ addslashes($userItem->role_display_name) }}', {{ $userItem->isSuperAdmin() ? 'true' : 'false' }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 font-semibold text-xs rounded-xl transition border border-brand-200 hover:border-brand-300 shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                    </svg>
                                    Ganti Password
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed select-none" title="Admin tidak memiliki izin mengubah password Super Admin">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    <span>Terkunci</span>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400 text-sm">
                            Tidak ada data pengguna ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

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
                @csrf
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
                @csrf
                
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
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg> Lihat QR
                        </button>
                    </div>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-500">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
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
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
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
                        <img src="{{ $adminQrCodeUrl }}" alt="QR 2FA Admin" class="w-40 h-40 object-contain rounded-lg">
                    </div>
                    <span class="text-xs text-blue-700 font-semibold flex items-center gap-1.5 mt-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Scan dengan Google Authenticator di HP
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Kunci Manual (Setup Key)
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-xs font-bold text-blue-700 tracking-wider flex-1 truncate select-all">
                            {{ $adminFormattedSecret }}
                        </div>
                        <button type="button" onclick="copyAdminSecret('{{ $adminRawSecret }}', this)" 
                                class="inline-flex items-center px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition shrink-0 active:scale-95">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg> Salin
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
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
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

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
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
                        @if(auth()->user()->isSuperAdmin())
                        <option value="super_admin">⭐ Super Admin (Akses Penuh Seluruh Sistem)</option>
                        @endif
                        <option value="admin">🛡️ Admin (Administrator Portal)</option>
                        <option value="staff" selected>👤 Petugas / Staff (Operasional Lapangan)</option>
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
                        <option value="all_roles">All Roles / Multi-Job (Semua Stasiun Petugas)</option>
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
            document.getElementById('kategoriForm').action = '{{ route("admin.kategori-pengeluaran.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('nama').value = '';
            document.getElementById('deskripsi').value = '';
            document.getElementById('is_active').checked = true;
            document.getElementById('kategoriModal').classList.remove('hidden');
        }

        function editKategori(id, nama, deskripsi, isActive) {
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('kategoriForm').action = '{{ url("admin/kategori-pengeluaran") }}/' + id;
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
        function openChangePasswordModal(userId, userName, userEmail, userRole, isSuperAdmin = false) {
            @if(!auth()->user()->isSuperAdmin())
            if (isSuperAdmin) {
                alert('Akses ditolak. Admin tidak memiliki izin untuk mengubah kata sandi Super Admin.');
                return;
            }
            @endif
            document.getElementById('targetUserName').textContent = userName;
            document.getElementById('targetUserEmail').textContent = userEmail;
            document.getElementById('targetUserRole').textContent = userRole;
            document.getElementById('changePasswordForm').action = "{{ url('admin/users') }}/" + userId + "/password";
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

        function previewQrisFile(input) {
            const previewImg = document.getElementById('settingsQrisPreviewImg');
            if (!previewImg || !input.files || !input.files[0]) {
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }

        function copyAdminSecret(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<svg class="w-3.5 h-3.5 inline mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Tersalin!';
                btn.classList.add('bg-emerald-50', 'text-emerald-700');
                setTimeout(() => {
                    btn.innerHTML = orig;
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700');
                }, 2000);
            }).catch(err => {
                alert('Kunci: ' + text);
            });
        }

        function focusSettingsSection(sectionId) {
            const target = document.getElementById(sectionId);
            if (!target) return;

            const scrollContainer = document.getElementById('admin-main-scroll') || target.closest('main');
            if (scrollContainer) {
                const containerRect = scrollContainer.getBoundingClientRect();
                const targetRect = target.getBoundingClientRect();
                const offsetTop = scrollContainer.scrollTop + (targetRect.top - containerRect.top) - 16;
                scrollContainer.scrollTo({ top: Math.max(0, offsetTop), behavior: 'smooth' });
            } else {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            target.classList.add('ring-4', 'ring-teal-400/50', 'ring-offset-4', 'ring-offset-slate-50');
            setTimeout(() => {
                target.classList.remove('ring-4', 'ring-teal-400/50', 'ring-offset-4', 'ring-offset-slate-50');
            }, 1400);
        }

        document.querySelectorAll('[data-settings-jump]').forEach((link) => {
            link.addEventListener('click', (event) => {
                const sectionId = link.getAttribute('data-settings-jump');
                if (!sectionId || !document.getElementById(sectionId)) return;
                event.preventDefault();
                history.replaceState(null, '', '#' + sectionId);
                focusSettingsSection(sectionId);
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash && window.location.hash.length > 1) {
                focusSettingsSection(window.location.hash.slice(1));
            }
        });
    </script>
</div>
@endsection


