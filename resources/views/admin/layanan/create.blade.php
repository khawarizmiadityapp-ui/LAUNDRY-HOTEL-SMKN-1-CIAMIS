{{-- resources/views/admin/layanan/create.blade.php --}}


{{-- ============================================================
     MODAL: TAMBAH LAYANAN
============================================================ --}}
<div x-data="{ open: false, form: { nama: '', kategori: 'kiloan', harga: '', estimasi: '', badge: '' } }"
     @open-modal.window="if ($event.detail.name === 'tambah-layanan') open = true"
     @keydown.escape.window="open = false"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- Backdrop --}}
    <div @click="open = false"
         x-transition:enter="transition duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div x-transition:enter="transition duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        {{-- Modal header --}}
        <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800 text-base">Tambah Layanan Baru</h2>
                <p class="text-xs text-slate-400 mt-0.5">Isi detail layanan yang ingin ditambahkan</p>
            </div>
            <button @click="open = false"
                    class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal body --}}
        <form method="POST" action="{{ route('admin.layanan.store') }}" class="px-6 py-5 space-y-4">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Layanan</label>
                <input type="text" name="nama" x-model="form.nama" required
                       placeholder="Contoh: Cuci Kiloan Regular"
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl
                              focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition" />
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori</label>
                <select name="kategori" x-model="form.kategori"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition bg-white">
                    <option value="kiloan">Cuci Kiloan</option>
                    <option value="satuan">Cuci Satuan</option>
                </select>
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Harga <span 
                                x-text="form.kategori === &quot;kiloan&quot; ? '(per kg)' : '(per pcs)'"
                            ></span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                    <input type="number" name="harga" x-model="form.harga" required min="0"
                           placeholder="0"
                           class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-slate-200 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition" />
                </div>
            </div>

            {{-- Estimasi --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Estimasi Waktu</label>
                <input type="text" name="estimasi" x-model="form.estimasi"
                       placeholder="Contoh: 2-3 hari pengerjaan"
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl
                              focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition" />
            </div>

            {{-- Badge --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Badge <span class="font-normal text-slate-400">(opsional)</span></label>
                <select name="badge"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition bg-white">
                    <option value="">Tanpa Badge</option>
                    <option value="Populer">Populer</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Baru">Baru</option>
                </select>
            </div>

            {{-- Footer --}}
            <div class="flex gap-3 pt-2">
                <button type="button" @click="open = false"
                        class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600
                               hover:bg-slate-50 active:scale-95 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold
                               shadow-md shadow-brand-600/25 active:scale-95 transition-all">
                    Simpan Layanan
                </button>
            </div>
        </form>

    </div>
</div>
