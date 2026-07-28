{{-- resources/views/admin/pembayaran/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Manajemen Pembayaran - Bening Laundry')
@section('content')

<div class="p-6 md:p-8" x-data="pembayaranApp()">
    <div class="max-w-7xl mx-auto">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Pembayaran</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola seluruh transaksi masuk, status pelunasan pelanggan, dan riwayat metode pembayaran dalam satu alur yang jernih.</p>
            </div>
        </div>

        <!-- STATISTIK CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-green-500 text-sm bg-green-100 px-2 py-1 rounded-full">+12%</span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">↑ 12% dari kemarin</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Transaksi Belum Lunas</p>
                        <p class="text-2xl font-bold mt-1">{{ $transaksiBelumLunas }} Transaksi</p>
                    </div>
                    <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => 'belum_bayar'])) }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-arrow-right mr-1"></i> Lihat Transaksi
                    </a>
                </div>
            </div>
        </div>

        <!-- FILTER TABS -->
        <div class="flex space-x-2 border-b mb-6">
            <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => null])) }}" 
               class="px-5 py-2 text-sm font-medium rounded-t-lg transition {{ !request('status') ? 'bg-white text-blue-700 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                Semua
            </a>
            <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => 'lunas'])) }}" 
               class="px-5 py-2 text-sm font-medium rounded-t-lg transition {{ request('status') == 'lunas' ? 'bg-white text-blue-700 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                Lunas
            </a>
            <a href="{{ route('admin.pembayaran.index', array_merge(request()->except('status'), ['status' => 'belum_bayar'])) }}" 
               class="px-5 py-2 text-sm font-medium rounded-t-lg transition {{ request('status') == 'belum_bayar' ? 'bg-white text-blue-700 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                Belum Lunas
            </a>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">{{ $trx->transaksi_code }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $trx->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($trx->service_type) }} - {{ $trx->weight }} kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trx->payment_status == 'lunas')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-check-circle mr-1 text-xs"></i> Lunas
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-hourglass-half mr-1"></i> Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown('dropdown-pembayaran-{{ $trx->id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 
                                                   hover:bg-slate-100 text-slate-500 transition-all duration-200 focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                                        </svg>
                                    </button>
                                    
                                    <div id="dropdown-pembayaran-{{ $trx->id }}" class="hidden absolute right-0 top-full mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5">
                                        @if($trx->payment_status != 'lunas')
                                        <button onclick="openPaymentModal('{{ $trx->transaksi_code }}', {{ $trx->total_price }})"
                                           class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors text-left">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Bayar
                                        </button>
                                        @endif
                                        <button type="button" @click="openNota('{{ route('pos.nota', $trx->id) }}'); toggleDropdown('dropdown-pembayaran-{{ $trx->id }}')"
                                           class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Cek Nota
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada transaksi ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $transactions->appends(request()->query())->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Proses Pembayaran</h3>
            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="paymentForm" action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="transaksi_id" id="modal_transaksi_id">
            <input type="hidden" name="tanggal_bayar" value="{{ date('Y-m-d') }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">ID Transaksi</label>
                <input type="text" id="modal_transaksi_code_display" class="w-full border border-gray-300 rounded-lg bg-gray-50 p-2.5 text-sm font-mono" readonly>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Tagihan</label>
                <div class="flex items-center">
                    <span class="bg-gray-100 border border-gray-300 border-r-0 px-4 py-2.5 rounded-l-lg text-gray-500 font-medium">Rp</span>
                    <input type="text" id="modal_total_price_display" class="w-full border border-gray-300 rounded-r-lg bg-gray-50 p-2.5 text-sm font-bold text-gray-800" readonly>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Uang Pelanggan <span class="text-red-500">*</span></label>
                <div class="flex items-center">
                    <span class="bg-gray-100 border border-gray-300 border-r-0 px-4 py-2.5 rounded-l-lg text-gray-500 font-medium">Rp</span>
                    <input type="number" name="jumlah_bayar" id="modal_jumlah_bayar" class="w-full border border-gray-300 rounded-r-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none" required min="0">
                </div>
                <p class="text-xs text-gray-500 mt-1.5"><i class="fas fa-info-circle mr-1"></i>Masukkan nominal sama atau lebih besar dari tagihan.</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="metode_pembayaran" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm appearance-none focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                        <option value="Tunai">Tunai</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer BCA">Transfer BCA</option>
                        <option value="Transfer Mandiri">Transfer Mandiri</option>
                        <option value="Transfer BRI">Transfer BRI</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran <span class="text-gray-400 text-xs font-normal">(Opsional)</span></label>
                <input type="file" name="bukti_pembayaran" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closePaymentModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
                    <i class="fas fa-check"></i> Proses Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

    {{-- ═══════════ NOTA MODAL ═══════════ --}}
    <div x-show="showNotaModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="closeNotaModal()" class="bg-white shadow-2xl w-full max-w-[400px] mx-4 animate-fade-up overflow-hidden rounded-xl flex flex-col h-[85vh]">
            {{-- Header --}}
            <div class="px-5 py-4 flex items-center justify-between bg-[#0b172a] text-white shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="font-bold text-xs tracking-widest uppercase">DETAIL TRANSAKSI</h3>
                </div>
                <button @click="closeNotaModal()" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            {{-- Iframe Container --}}
            <div class="flex-1 overflow-hidden relative bg-slate-50">
                <template x-if="notaUrl">
                    <iframe x-ref="notaIframe" :src="notaUrl" class="w-full h-full border-0 absolute inset-0"></iframe>
                </template>
            </div>
            
            {{-- Footer / Actions --}}
            <div class="px-5 py-4 border-t border-slate-100 flex gap-3 justify-center bg-white shrink-0">
                <button @click="kirimWa()"
                        class="px-6 py-2.5 bg-[#25D366] text-white text-xs font-bold rounded-full hover:bg-[#128C7E] transition shadow-md flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    KIRIM WA
                </button>
                <button @click="printNota()"
                        class="px-6 py-2.5 bg-black text-white text-xs font-bold rounded-full hover:bg-slate-800 transition shadow-md flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    CETAK STRUK
                </button>
                <button @click="closeNotaModal()"
                        class="px-8 py-2.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full hover:bg-slate-200 transition active:scale-95">
                    TUTUP
                </button>
            </div>
        </div>
    </div>

<script>
    function openPaymentModal(transaksiCode, totalPrice) {
        document.getElementById('modal_transaksi_id').value = transaksiCode;
        document.getElementById('modal_transaksi_code_display').value = transaksiCode;
        document.getElementById('modal_total_price_display').value = new Intl.NumberFormat('id-ID').format(totalPrice);
        
        // Default jumlah bayar to total price
        let jumlahBayarInput = document.getElementById('modal_jumlah_bayar');
        jumlahBayarInput.value = totalPrice;
        jumlahBayarInput.min = totalPrice;
        
        document.getElementById('paymentModal').classList.remove('hidden');
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('pembayaranApp', () => ({
            showNotaModal: false,
            notaUrl: '',
            
            openNota(url) {
                this.notaUrl = url;
                this.showNotaModal = true;
            },
            
            closeNotaModal() {
                this.showNotaModal = false;
                setTimeout(() => {
                    this.notaUrl = '';
                }, 200);
            },
            
            printNota() {
                const iframe = this.$refs.notaIframe;
                if (iframe && iframe.contentWindow) {
                    iframe.contentWindow.print();
                }
            },
            
            kirimWa() {
                const iframe = this.$refs.notaIframe;
                if (iframe && iframe.contentDocument) {
                    const waLink = iframe.contentDocument.getElementById('waLink');
                    if (waLink) {
                        window.open(waLink.href, '_blank');
                    } else {
                        alert('Link WhatsApp tidak ditemukan.');
                    }
                }
            }
        }));
    });
</script>

</main>
@endsection
