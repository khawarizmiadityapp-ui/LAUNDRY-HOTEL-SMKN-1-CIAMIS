{{-- resources/views/admin/settings.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan - Bening Laundry')

@section('content')
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
            
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Target Pendapatan Bulanan -->
                <div>
                    <label for="target" class="block text-sm font-semibold text-gray-700 mb-1">Target Pemasukan Bulanan (Rp)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-semibold text-sm">
                            Rp
                        </div>
                        <input type="number" name="target" id="target" value="{{ $limitPemasukanBulanan }}" required
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="Contoh: 50000000">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Digunakan untuk visualisasi progres pencapaian target pada laporan keuangan.</p>
                </div>

                <!-- Nomor WA Pihak Bisa Dihubungi Pembeli -->
                <div>
                    <label for="admin_wa" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Admin (Contact Person)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-semibold text-sm">
                            +
                        </div>
                        <input type="text" name="admin_wa" id="admin_wa" value="{{ $adminWA }}" required
                               class="w-full pl-8 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                               placeholder="Contoh: 6282116035029">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Format diawali kode negara tanpa tanda tambah (+), contoh: 6282116035029. Nomor ini digunakan oleh pelanggan pada halaman tracking dan landing page untuk menghubungi laundry.</p>
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
                    Sistem akan menyinkronkan nomor WhatsApp ini ke link tombol "Hubungi Admin" di halaman Lacak Status Cucian dan "Pesan Layanan" di Landing Page utama. Pastikan nomor tersebut aktif WhatsApp-nya agar pesan dapat terkirim secara langsung.
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
                        <a href="https://wa.me/{{ $adminWA }}" target="_blank" class="text-sm font-bold text-white hover:underline flex items-center gap-1">
                            wa.me/{{ $adminWA }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
