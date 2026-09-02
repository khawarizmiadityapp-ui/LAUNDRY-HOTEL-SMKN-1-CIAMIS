
<div id="exportModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeExportModal()"></div>
        
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-file-export text-blue-600 text-lg"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                            Pilih Data untuk Export
                        </h3>
                        
                        <form id="exportForm" method="GET" x-data="{ 
                            exportType: 'excel', 
                            filter: 'bulanan',
                            showCustomDate: false,
                            updateFilter() {
                                this.showCustomDate = this.filter === 'custom';
                            }
                        }">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Laporan Export</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <label class="relative flex flex-col items-center justify-center cursor-pointer rounded-xl border bg-white p-3 shadow-sm focus:outline-none transition" :class="exportType === 'bku_pdf' ? 'border-blue-600 ring-2 ring-blue-600 bg-blue-50/30' : 'border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="exportType" x-model="exportType" value="bku_pdf" class="sr-only">
                                        <i class="fas fa-book text-blue-600 text-xl mb-1"></i>
                                        <span class="text-xs font-bold text-gray-900 text-center leading-tight">BKU (PDF)</span>
                                        <span class="text-[9px] text-blue-600 font-semibold mt-0.5">Buku Kas</span>
                                    </label>
                                    <label class="relative flex flex-col items-center justify-center cursor-pointer rounded-xl border bg-white p-3 shadow-sm focus:outline-none transition" :class="exportType === 'excel' ? 'border-blue-600 ring-2 ring-blue-600 bg-blue-50/30' : 'border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="exportType" x-model="exportType" value="excel" class="sr-only">
                                        <i class="fas fa-file-excel text-green-600 text-xl mb-1"></i>
                                        <span class="text-xs font-bold text-gray-900 text-center leading-tight">Excel</span>
                                        <span class="text-[9px] text-gray-500 font-medium mt-0.5">Spreadsheet</span>
                                    </label>
                                    <label class="relative flex flex-col items-center justify-center cursor-pointer rounded-xl border bg-white p-3 shadow-sm focus:outline-none transition" :class="exportType === 'pdf' ? 'border-blue-600 ring-2 ring-blue-600 bg-blue-50/30' : 'border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="exportType" x-model="exportType" value="pdf" class="sr-only">
                                        <i class="fas fa-file-pdf text-red-600 text-xl mb-1"></i>
                                        <span class="text-xs font-bold text-gray-900 text-center leading-tight">Transaksi (PDF)</span>
                                        <span class="text-[9px] text-gray-500 font-medium mt-0.5">Ringkasan</span>
                                    </label>
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Data</label>
                                <select name="filter" x-model="filter" @change="updateFilter()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="bulanan">Bulan Ini</option>
                                    <option value="tahunan">1 Tahun</option>
                                    <option value="custom">Custom Range (Rentang Tanggal)</option>
                                </select>
                            </div>

                            
                            <div x-show="showCustomDate" x-cloak class="mb-4 space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                    <input type="date" name="dari" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                    <input type="date" name="sampai" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-700 flex items-start">
                                    <i class="fas fa-info-circle mt-0.5 mr-2 shrink-0"></i>
                                    <span><strong>BKU (Buku Kas Umum) PDF</strong> menyajikan rekapitulasi penerimaan (debet), pengeluaran (kredit), dan saldo kas berjalan lengkap dengan format resmi sekolah.</span>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                <button type="button" onclick="
                    const form = document.getElementById('exportForm');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const exportType = document.querySelector('input[name=\'exportType\']:checked')?.value || 'bku_pdf';
                    
                    let route = '<?php echo e(route('admin.laporan_keuangan.bku_pdf')); ?>';
                    if (exportType === 'excel') {
                        route = '<?php echo e(route('export.transaksi.excel')); ?>';
                    } else if (exportType === 'pdf') {
                        route = '<?php echo e(route('export.transaksi.pdf')); ?>';
                    }
                    
                    window.location.href = route + '?' + params.toString();
                    closeExportModal();
                " class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                    <i class="fas fa-download mr-2"></i> Export Sekarang
                </button>
                <button type="button" onclick="closeExportModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeExportModal();
    }
});
</script>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/laporan_keuangan/partials/export_modal.blade.php ENDPATH**/ ?>