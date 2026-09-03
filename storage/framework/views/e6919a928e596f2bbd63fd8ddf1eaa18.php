<?php $__env->startSection('title', 'Formulir Pengajuan Belanja Baru'); ?>
<?php $__env->startSection('page-title', 'Formulir Pengajuan Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">

    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="<?php echo e(route('admin.pengeluaran.index')); ?>" class="hover:text-blue-600">Pengeluaran</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>" class="hover:text-blue-600">Pengajuan Belanja</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 font-semibold">Formulir Baru</span>
        </div>
        <div class="flex items-center gap-3">
            <label for="import_pdf" class="cursor-pointer px-3.5 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold rounded-xl shadow-sm transition flex items-center gap-1.5">
                <i class="fas fa-file-pdf text-rose-500"></i> Import dari PDF
            </label>
            <input type="file" id="import_pdf" accept=".pdf" class="hidden">
            
            <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>"
               class="px-3.5 py-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
        
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold">Formulir Pengajuan Belanja / Pengadaan</h1>
                <p class="text-xs text-blue-100 mt-0.5">Isi detail kebutuhan barang/operasional laundry untuk persetujuan manajemen.</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                <i class="fas fa-file-signature text-lg"></i>
            </div>
        </div>

        
        <form action="<?php echo e(route('admin.pengajuan_belanja.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kode Pengajuan (Otomatis)</label>
                    <input type="text" value="<?php echo e($kodePengajuan); ?>" readonly
                           class="w-full text-xs font-mono font-bold text-blue-700 bg-blue-50/50 border border-blue-200 rounded-xl p-3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" value="<?php echo e(old('tanggal_pengajuan', date('Y-m-d'))); ?>" required
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                    <?php $__errorArgs = ['tanggal_pengajuan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Barang / Keperluan Belanja <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_pengajuan" value="<?php echo e(old('nama_pengajuan')); ?>" required
                       placeholder="Contoh: Pembelian Deterjen Cair Matik 5 Jerigen & Plastik Packing Ukuran L"
                       class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                <?php $__errorArgs = ['nama_pengajuan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Pengeluaran <span class="text-rose-500">*</span></label>
                    <select name="kategori_id" required
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                        <option value="">Pilih Kategori</option>
                        <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kat->id); ?>" <?php echo e(old('kategori_id') == $kat->id ? 'selected' : ''); ?>><?php echo e($kat->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['kategori_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estimasi Biaya (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="estimasi_biaya" value="<?php echo e(old('estimasi_biaya')); ?>" required min="1000"
                           placeholder="500000"
                           class="w-full text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                    <?php $__errorArgs = ['estimasi_biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tingkat Urgensi <span class="text-rose-500">*</span></label>
                    <select name="urgensi" required
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                        <option value="biasa" <?php echo e(old('urgensi') == 'biasa' ? 'selected' : ''); ?>>Biasa (Stok Rutin)</option>
                        <option value="mendesak" <?php echo e(old('urgensi') == 'mendesak' ? 'selected' : ''); ?>>Mendesak (Stok Menipis)</option>
                        <option value="sangat_mendesak" <?php echo e(old('urgensi') == 'sangat_mendesak' ? 'selected' : ''); ?>>Sangat Mendesak (Habis/Penting)</option>
                    </select>
                    <?php $__errorArgs = ['urgensi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alasan & Rincian Kebutuhan <span class="text-rose-500">*</span></label>
                <textarea name="alasan" rows="4" required
                          placeholder="Jelaskan secara rinci kebutuhan barang, jumlah unit yang diperlukan, estimasi harga per unit, dan alasan pengadaannya..."
                          class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"><?php echo e(old('alasan')); ?></textarea>
                <?php $__errorArgs = ['alasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lampiran Dokumen / Penawaran / Foto Barang (Opsional)</label>
                <input type="file" name="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50 p-1.5">
                <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, PNG, atau PDF (Maksimal 3 MB).</p>
                <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="<?php echo e(route('admin.pengajuan_belanja.index')); ?>"
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2 active:scale-95">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Formulir Pengajuan
                </button>
            </div>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- PDF.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Initialize PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

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
            // Since pdf.js text items are separated by spaces, we might need a more flexible approach
            const headerRegex = /No\s*Qty\s*Nama Barang\/Bahan\s*Harga Satuan \(Rp\)\s*Jumlah \(Rp\)\s*Keterangan/i;
            const totalRegex = /Total Pengajuan/i;
            
            const headerMatch = fullText.match(headerRegex);
            const tMatch = fullText.match(totalRegex);
            
            if (headerMatch && tMatch && tMatch.index > headerMatch.index) {
                const itemsStr = fullText.substring(headerMatch.index + headerMatch[0].length, tMatch.index).trim();
                
                // Splitting items can be tricky due to how pdf.js concatenates text.
                // A typical row might look like: "1 3 Lakban Bening Besar Rp15,000 Rp45,000"
                // Let's try to match lines starting with a number
                const itemMatches = itemsStr.matchAll(/(\d+)\s+(\d+)\s+([a-zA-Z0-9\s]+?)\s+Rp([\d\.,]+)\s+Rp([\d\.,]+)/g);
                
                let foundItems = false;
                for (const item of itemMatches) {
                    foundItems = true;
                    const no = item[1];
                    const qty = item[2];
                    const nama = item[3].trim();
                    const harga = item[4];
                    const total = item[5];
                    
                    alasanText += `- ${qty}x ${nama} (@ Rp${harga}) = Rp${total}\n`;
                }

                if (!foundItems) {
                    // Fallback if the strict regex fails: just dump the raw text nicely
                    alasanText += itemsStr;
                }
            } else {
                alasanText += "Detail barang ditemukan pada PDF yang dilampirkan.";
            }

            // --- Fill Form ---
            if (namaPengajuan) document.querySelector('input[name="nama_pengajuan"]').value = namaPengajuan;
            if (estimasiBiaya) document.querySelector('input[name="estimasi_biaya"]').value = estimasiBiaya;
            if (alasanText) document.querySelector('textarea[name="alasan"]').value = alasanText;

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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/pengajuan_belanja/create.blade.php ENDPATH**/ ?>