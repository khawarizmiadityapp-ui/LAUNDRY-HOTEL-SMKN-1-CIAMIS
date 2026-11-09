{{-- resources/views/admin/pengajuan_belanja/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Formulir Pengajuan Belanja Baru')
@section('page-title', 'Formulir Pengajuan Belanja')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.pengeluaran.index') }}" class="hover:text-blue-600">Pengeluaran</a>
            <svg class="w-2.5 h-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            <a href="{{ route('admin.pengajuan_belanja.index') }}" class="hover:text-blue-600">Pengajuan Belanja</a>
            <svg class="w-2.5 h-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-800 font-semibold">Formulir Baru</span>
        </div>
        <div class="flex items-center gap-3">
            <label for="import_pdf" class="cursor-pointer px-3.5 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold rounded-xl shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Import dari PDF
            </label>
            <input type="file" id="import_pdf" accept=".pdf" class="hidden">
            
            <a href="{{ route('admin.pengajuan_belanja.index') }}"
               class="px-3.5 py-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg> Kembali
            </a>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold">Formulir Pengajuan Belanja / Pengadaan</h1>
                <p class="text-xs text-blue-100 mt-0.5">Isi detail kebutuhan barang/operasional laundry untuk persetujuan manajemen.</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </div>
        </div>

        {{-- Form Body --}}
        <form action="{{ route('admin.pengajuan_belanja.store') }}" method="POST" enctype="multipart/form-data" 
              class="p-6 md:p-8 space-y-6" x-data="pengajuanForm()" @submit="prepareSubmit($event)">
            @csrf
            <input type="hidden" name="alasan" x-model="alasanHidden">

            {{-- Row: Kode & Tanggal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kode Pengajuan (Otomatis)</label>
                    <input type="text" value="{{ $kodePengajuan }}" readonly
                           class="w-full text-xs font-mono font-bold text-blue-700 bg-blue-50/50 border border-blue-200 rounded-xl p-3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                    @error('tanggal_pengajuan') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Nama Kebutuhan Belanja --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Barang / Keperluan Belanja <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_pengajuan" value="{{ old('nama_pengajuan') }}" required
                       placeholder="Contoh: Pembelian Deterjen Cair Matik 5 Jerigen & Plastik Packing Ukuran L"
                       class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                @error('nama_pengajuan') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Row: Kategori, Estimasi Biaya, Urgensi --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Pengeluaran <span class="text-rose-500">*</span></label>
                    <select name="kategori_id" required
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estimasi Biaya (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="estimasi_biaya" :value="estimasiBiaya" readonly required min="1000"
                           class="w-full text-xs font-bold text-slate-800 bg-slate-100 border border-slate-200 rounded-xl p-3 focus:outline-none cursor-not-allowed">
                    <p class="text-[10px] text-slate-400 mt-1">Dihitung otomatis dari rincian barang.</p>
                    @error('estimasi_biaya') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tingkat Urgensi <span class="text-rose-500">*</span></label>
                    <select name="urgensi" required
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                        <option value="biasa" {{ old('urgensi') == 'biasa' ? 'selected' : '' }}>Biasa (Stok Rutin)</option>
                        <option value="mendesak" {{ old('urgensi') == 'mendesak' ? 'selected' : '' }}>Mendesak (Stok Menipis)</option>
                        <option value="sangat_mendesak" {{ old('urgensi') == 'sangat_mendesak' ? 'selected' : '' }}>Sangat Mendesak (Habis/Penting)</option>
                    </select>
                    @error('urgensi') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Rincian Kebutuhan (Dynamic Items) --}}
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Rincian Barang Kebutuhan <span class="text-rose-500">*</span></label>
                    <button type="button" @click="addItem()" class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Barang
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex flex-col sm:flex-row gap-3 items-end bg-white p-3 border border-slate-100 rounded-xl shadow-sm">
                            <div class="w-full sm:w-1/2">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Barang / Bahan</label>
                                <input type="text" x-model="item.nama" required placeholder="Contoh: Lakban Bening" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500/20 outline-none">
                            </div>
                            <div class="w-full sm:w-24">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Qty</label>
                                <input type="number" x-model="item.qty" required min="1" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500/20 outline-none text-center">
                            </div>
                            <div class="w-full sm:w-1/4">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Harga Satuan (Rp)</label>
                                <input type="number" x-model="item.harga" required min="0" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500/20 outline-none text-right">
                            </div>
                            <div class="w-full sm:w-1/4">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jumlah (Rp)</label>
                                <input type="text" :value="(item.qty * item.harga).toLocaleString('id-ID')" readonly class="w-full text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-lg p-2 outline-none text-right cursor-not-allowed">
                            </div>
                            <button type="button" @click="removeItem(index)" class="p-2 mb-0.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus Barang">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea x-model="keterangan" rows="2" placeholder="Catatan ekstra, tujuan pembelian..." class="w-full text-xs bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 outline-none"></textarea>
                </div>
            </div>

            {{-- Lampiran Dokumen (Bon/Penawaran) --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lampiran Dokumen / Penawaran / Foto Barang (Opsional)</label>
                <input type="file" name="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50 p-1.5">
                <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, PNG, atau PDF (Maksimal 3 MB).</p>
                @error('lampiran') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pengajuan_belanja.index') }}"
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    Kirim Formulir Pengajuan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<!-- PDF.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Initialize PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    document.addEventListener('alpine:init', () => {
        Alpine.data('pengajuanForm', () => ({
            items: [ { qty: 1, nama: '', harga: 0 } ],
            keterangan: '',
            alasanHidden: '',

            get estimasiBiaya() {
                return this.items.reduce((sum, item) => sum + (parseInt(item.qty || 0) * parseInt(item.harga || 0)), 0);
            },

            addItem() {
                this.items.push({ qty: 1, nama: '', harga: 0 });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },

            prepareSubmit(e) {
                // Compile the structured format
                let text = "Rincian Pengajuan Belanja:\n";
                this.items.forEach(item => {
                    if (item.nama) {
                        const total = parseInt(item.qty) * parseInt(item.harga);
                        // Make sure no thousand separators are added so parser can easily read it, or use standard ones
                        text += `- ${item.qty}x ${item.nama} (@ Rp${item.harga}) = Rp${total}\n`;
                    }
                });

                if (this.keterangan.trim()) {
                    text += `\nCatatan Tambahan:\n${this.keterangan.trim()}`;
                }

                this.alasanHidden = text;
            },

            init() {
                // Listen for PDF import events
                document.addEventListener('pdf-imported', (e) => {
                    const data = e.detail;
                    
                    if (data.items && data.items.length > 0) {
                        this.items = data.items.map(i => ({
                            qty: i.qty,
                            nama: i.nama,
                            harga: i.harga
                        }));
                    }
                });
            }
        }));
    });

    document.getElementById('import_pdf').addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        showToast('Sedang membaca PDF...', 'info');

        try {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
            
            let fullText = '';
            for (let i = 1; i <= pdf.numPages; i++) {
                const page = await pdf.getPage(i);
                const textContent = await page.getTextContent();
                const pageText = textContent.items.map(item => item.str).join(' ');
                fullText += pageText + '\n';
            }

            // --- Parsing Logic ---
            
            // 1. Extract Nama Pengajuan (Find "FORMULIR PENGAJUAN BELANJA" and the next word/line like "MEI 2026")
            let namaPengajuan = "Pengajuan Belanja TEFA";
            const monthMatch = fullText.match(/FORMULIR PENGAJUAN BELANJA\s+([A-Z]+ \d{4})/i);
            if (monthMatch) {
                namaPengajuan += ' ' + monthMatch[1];
            } else {
                // Try to find any month year if the strict match fails
                const altMatch = fullText.match(/(JANUARI|FEBRUARI|MARET|APRIL|MEI|JUNI|JULI|AGUSTUS|SEPTEMBER|OKTOBER|NOVEMBER|DESEMBER) \d{4}/i);
                if (altMatch) {
                    namaPengajuan += ' ' + altMatch[0];
                }
            }

            // 2. Extract Total Amount
            let estimasiBiaya = '';
            // Match "Total Pengajuan : Rp. 83.000" or similar variations
            const totalMatch = fullText.match(/Total Pengajuan\s*:\s*Rp\.?\s*([\d\.,]+)/i) || fullText.match(/Total.*?Rp\.?\s*([\d\.,]+)/i);
            if (totalMatch) {
                // Remove dots and commas
                estimasiBiaya = totalMatch[1].replace(/[\.,]/g, '');
            }

            // 3. Extract Items for Alasan
            let alasanText = "Rincian Pengajuan Belanja:\n";
            
            // Find text between table header and "Total Pengajuan"
            const headerRegex = /No\s*Qty\s*Nama Barang\/Bahan\s*Harga Satuan \(Rp\)\s*Jumlah \(Rp\)\s*Keterangan/i;
            const totalRegex = /Total Pengajuan/i;
            
            const headerMatch = fullText.match(headerRegex);
            const tMatch = fullText.match(totalRegex);
            
            let foundItems = [];
            
            if (headerMatch && tMatch && tMatch.index > headerMatch.index) {
                const itemsStr = fullText.substring(headerMatch.index + headerMatch[0].length, tMatch.index).trim();
                
                const itemMatches = itemsStr.matchAll(/(\d+)\s+(\d+)\s+([a-zA-Z0-9\s\/\-]+?)\s+Rp([\d\.,]+)\s+Rp([\d\.,]+)/g);
                
                for (const item of itemMatches) {
                    const qty = parseInt(item[2]);
                    const nama = item[3].trim();
                    const harga = parseInt(item[4].replace(/[\.,]/g, ''));
                    
                    foundItems.push({ qty, nama, harga });
                }
            }

            // --- Fill Form ---
            if (namaPengajuan) document.querySelector('input[name="nama_pengajuan"]').value = namaPengajuan;
            
            if (foundItems.length > 0) {
                document.dispatchEvent(new CustomEvent('pdf-imported', { detail: { items: foundItems } }));
            }

            // Remove automatic file attachment as it causes upload errors due to browser security / size limits
            // If the user wants to upload the file, they can select it manually on the input field.

            showToast('Formulir berhasil diisi! Silakan "Choose File" pada Lampiran jika ingin menyimpan PDF ini.', 'success');
            
        } catch (error) {
            console.error('Error parsing PDF:', error);
            showToast('Gagal membaca file PDF. Pastikan formatnya sesuai.', 'error');
        }
        
        // Reset so the same file can be selected again if needed
        e.target.value = '';
    });
</script>
@endpush
