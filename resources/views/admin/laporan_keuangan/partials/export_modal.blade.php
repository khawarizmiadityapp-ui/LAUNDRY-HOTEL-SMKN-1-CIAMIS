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
                            {{-- Export Type Selection --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Export</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="exportType === 'excel' ? 'border-blue-600 ring-2 ring-blue-600' : 'border-gray-300'">
                                        <input type="radio" x-model="exportType" value="excel" class="sr-only">
                                        <div class="flex flex-1 items-center justify-center">
                                            <i class="fas fa-file-excel text-green-600 text-2xl mr-2"></i>
                                            <span class="text-sm font-medium text-gray-900">Excel</span>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="exportType === 'pdf' ? 'border-blue-600 ring-2 ring-blue-600' : 'border-gray-300'">
                                        <input type="radio" x-model="exportType" value="pdf" class="sr-only">
                                        <div class="flex flex-1 items-center justify-center">
                                            <i class="fas fa-file-pdf text-red-600 text-2xl mr-2"></i>
                                            <span class="text-sm font-medium text-gray-900">PDF</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Filter Period Selection --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Data</label>
                                <select name="filter" x-model="filter" @change="updateFilter()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="bulanan">Bulan Ini</option>
                                    <option value="tahunan">1 Tahun (2026)</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>

                            {{-- Custom Date Range --}}
                            <div x-show="showCustomDate" x-cloak class="mb-4 space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                    <input type="date" name="dari" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                    <input type="date" name="sampai" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            {{-- Info Box --}}
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-700 flex items-start">
                                    <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                                    <span>Data yang di-export akan sesuai dengan periode yang Anda pilih. Pastikan sudah memilih periode yang tepat.</span>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                <button type="button" @click="
                    const form = document.getElementById('exportForm');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const exportType = document.querySelector('input[name=\\'exportType\\']:checked')?.value || 'excel';
                    const route = exportType === 'excel' ? '{{ route('export.transaksi.excel') }}' : '{{ route('export.transaksi.pdf') }}';
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
