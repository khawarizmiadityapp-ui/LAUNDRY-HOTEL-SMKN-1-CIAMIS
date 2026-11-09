{{-- Export Filter Modal --}}
<div id="exportModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeExportModal()"></div>
        
        {{-- Center modal --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
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
                            {{-- Export Type Selection --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Laporan Export</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <label class="relative flex flex-col items-center justify-center cursor-pointer rounded-xl border bg-white p-3 shadow-sm focus:outline-none transition" :class="exportType === 'bku_pdf' ? 'border-blue-600 ring-2 ring-blue-600 bg-blue-50/30' : 'border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="exportType" x-model="exportType" value="bku_pdf" class="sr-only">
                                        <svg class="w-6 h-6 text-blue-600 mb-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                        <span class="text-xs font-bold text-gray-900 text-center leading-tight">BKU (PDF)</span>
                                        <span class="text-[9px] text-blue-600 font-semibold mt-0.5">Buku Kas</span>
                                    </label>
                                    <label class="relative flex flex-col items-center justify-center cursor-pointer rounded-xl border bg-white p-3 shadow-sm focus:outline-none transition" :class="exportType === 'excel' ? 'border-blue-600 ring-2 ring-blue-600 bg-blue-50/30' : 'border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="exportType" x-model="exportType" value="excel" class="sr-only">
                                        <svg class="w-6 h-6 text-emerald-600 mb-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125" />
                                        </svg>
                                        <span class="text-xs font-bold text-gray-900 text-center leading-tight">Excel</span>
                                        <span class="text-[9px] text-gray-500 font-medium mt-0.5">Spreadsheet</span>
                                    </label>
                                    <label class="relative flex flex-col items-center justify-center cursor-pointer rounded-xl border bg-white p-3 shadow-sm focus:outline-none transition" :class="exportType === 'pdf' ? 'border-blue-600 ring-2 ring-blue-600 bg-blue-50/30' : 'border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="exportType" x-model="exportType" value="pdf" class="sr-only">
                                        <svg class="w-6 h-6 text-rose-600 mb-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <span class="text-xs font-bold text-gray-900 text-center leading-tight">Transaksi (PDF)</span>
                                        <span class="text-[9px] text-gray-500 font-medium mt-0.5">Ringkasan</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Filter Period Selection --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Data</label>
                                <select name="filter" x-model="filter" @change="updateFilter()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="bulanan">Bulan Ini</option>
                                    <option value="tahunan">1 Tahun</option>
                                    <option value="custom">Custom Range (Rentang Tanggal)</option>
                                </select>
                            </div>

                            {{-- Custom Date Range --}}
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

                            {{-- Input Saldo Awal Kas Khusus BKU --}}
                            <div x-show="exportType === 'bku_pdf'" class="mb-4">
                                <label class="flex items-center gap-1.5 text-xs font-bold text-gray-700 mb-1">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                    </svg>
                                    <span>Saldo Awal Kas / Modal Kas (Opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-xs">Rp</span>
                                    <input type="number" name="saldo_awal" id="exportSaldoAwalInput" min="0" placeholder="0 (atau masukkan modal kas awal)"
                                           class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <p class="text-[10px] text-gray-500 mt-1">
                                    Tentukan modal kas fisik awal periode jika ada uang kas di laci kasir. Jika dikosongkan, saldo awal akan dihitung otomatis dari sisa kas lalu (minimal Rp 0 agar tidak minus).
                                </p>
                            </div>

                            {{-- Info Box --}}
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-700 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                    <span><strong>BKU (Buku Kas Umum) PDF</strong> menyajikan rekapitulasi penerimaan (debet), pengeluaran (kredit), dan saldo kas berjalan lengkap dengan format resmi sekolah.</span>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                <button type="button" onclick="
                    const form = document.getElementById('exportForm');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const exportType = document.querySelector('input[name=\'exportType\']:checked')?.value || 'bku_pdf';
                    
                    let route = '{{ route('admin.laporan_keuangan.bku_pdf') }}';
                    if (exportType === 'excel') {
                        route = '{{ route('export.transaksi.excel') }}';
                    } else if (exportType === 'pdf') {
                        route = '{{ route('export.transaksi.pdf') }}';
                    }
                    
                    window.location.href = route + '?' + params.toString();
                    closeExportModal();
                " class="w-full inline-flex items-center justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Export Sekarang</span>
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
