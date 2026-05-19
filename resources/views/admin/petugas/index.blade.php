{{-- resources/views/petugas/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div x-data="petugasManager()" x-init="initData()" x-cloak>
    {{-- Header & Stats --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Petugas</h1>
        <p class="text-gray-500 mt-1">Kelola akses, peran, dan status keaktifan tim operasional</p>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Total Petugas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1" x-text="totalPetugas"></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl"><i class="fas fa-users text-blue-600 text-xl"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Aktif Sekarang</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1" x-text="aktifSekarang"></p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl"><i class="fas fa-user-check text-green-600 text-xl"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Admin Sistem</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1" x-text="adminSistem"></p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl"><i class="fas fa-user-cog text-purple-600 text-xl"></i></div>
            </div>
        </div>
    </div>

    {{-- Filter Kategori & Search (search sudah di header, tapi kita sinkronkan) --}}
    <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
        <div class="flex space-x-2 bg-gray-100 p-1 rounded-xl">
            <button @click="activeFilter = 'Semua'; currentPage = 1" :class="activeFilter === 'Semua' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'" class="px-5 py-2 rounded-lg font-medium transition-all">Semua</button>
            <button @click="activeFilter = 'Admin'; currentPage = 1" :class="activeFilter === 'Admin' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'" class="px-5 py-2 rounded-lg font-medium transition-all">Admin</button>
            <button @click="activeFilter = 'Operasional'; currentPage = 1" :class="activeFilter === 'Operasional' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'" class="px-5 py-2 rounded-lg font-medium transition-all">Operasional</button>
        </div>
        <div class="relative w-64">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nama petugas..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    {{-- Grid Petugas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="petugas in paginatedData" :key="petugas.id">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-smooth hover-card">
                <div class="p-5">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg" x-text="petugas.nama.charAt(0)"></div>
                            <div>
                                <h3 class="font-semibold text-gray-800" x-text="petugas.nama"></h3>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span x-show="petugas.role === 'Admin'" class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">Admin</span>
                                    <span x-show="petugas.role === 'Operasional'" class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700 font-medium">Staff</span>
                                    <span x-show="petugas.role === 'Kurir'" class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-medium">Kurir</span>
                                    <span class="text-xs text-gray-400" x-text="petugas.idPetugas"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button @click="editPetugas(petugas)" class="text-gray-400 hover:text-blue-600 transition" title="Edit"><i class="fas fa-edit"></i></button>
                            <button @click="confirmDelete(petugas)" class="text-gray-400 hover:text-red-500 transition" title="Hapus"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span x-show="petugas.status === 'Aktif'" class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span></span>
                                <span x-show="petugas.status === 'Off Duty'" class="relative flex h-2.5 w-2.5"><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-gray-400"></span></span>
                                <span class="text-sm font-medium" :class="petugas.status === 'Aktif' ? 'text-green-600' : 'text-gray-500'" x-text="petugas.status"></span>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                            <span>Selesai Kerja:</span>
                            <span class="font-bold text-gray-700"><span x-text="petugas.total_completed || 0"></span> tugas</span>
                        </div>
                        <button @click="detailPetugas(petugas)" class="w-full mt-2 text-center text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition font-medium">Detail & Kinerja</button>
                    </div>
                </div>
            </div>
        </template>

        {{-- Card Tambah Petugas --}}
        <div @click="openAddModal" class="bg-white rounded-xl border-2 border-dashed border-gray-300 hover:border-blue-400 transition-all flex flex-col items-center justify-center p-6 cursor-pointer min-h-[220px] hover:shadow-md">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-blue-50">
                <i class="fas fa-plus text-2xl text-gray-500"></i>
            </div>
            <p class="mt-3 font-medium text-gray-600">Tambah Petugas Baru</p>
            <p class="text-xs text-gray-400 mt-1">Klik untuk menambahkan</p>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex flex-wrap justify-between items-center">
        <div class="text-sm text-gray-500" x-text="`Menampilkan ${startItem} - ${endItem} dari ${filteredData.length} petugas`"></div>
        <div class="flex space-x-2">
            <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"><i class="fas fa-chevron-left"></i></button>
            <template x-for="page in totalPages" :key="page">
                <button @click="currentPage = page" :class="currentPage === page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300'" class="px-3 py-1 rounded-lg hover:bg-blue-50 transition" x-text="page"></button>
            </template>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

<div x-show="modalMode" x-cloak
     class="fixed inset-0 flex items-center justify-center z-50 bg-black/50"
     @click.self="closeModal()"
     style="display: none;">

    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold"
                x-text="modalMode === 'add' ? 'Tambah Petugas' :
                       modalMode === 'edit' ? 'Edit Petugas' :
                       'Detail Petugas'">
            </h3>

            <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <!-- FORM (ADD & EDIT) -->
        <template x-if="modalMode === 'add' || modalMode === 'edit'">
            <form @submit.prevent="modalMode === 'add' ? saveNewPetugas() : updatePetugas()">
                <div class="space-y-4">

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama</label>
                        <input type="text" x-model="selectedPetugas.nama" required
                               class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Role</label>
                        <select x-model="selectedPetugas.role" required
                                class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                            <option>Admin</option>
                            <option>Operasional</option>
                            <option>Kurir</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select x-model="selectedPetugas.status" required
                                class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                            <option>Aktif</option>
                            <option>Off Duty</option>
                        </select>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="closeModal()" class="px-4 py-2 border rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition">
                        Simpan
                    </button>
                </div>
            </form>
        </template>

        <!-- DETAIL VIEW -->
        <template x-if="modalMode === 'detail'">
            <div class="space-y-4 mt-4 text-gray-700">
                <div class="space-y-2 pb-4 border-b border-gray-100 text-sm">
                    <p class="flex justify-between"><span class="text-gray-400">Nama:</span> <strong class="text-gray-800" x-text="selectedPetugas.nama"></strong></p>
                    <p class="flex justify-between"><span class="text-gray-400">ID Petugas:</span> <span class="font-mono bg-slate-50 px-2 py-0.5 rounded text-gray-600" x-text="selectedPetugas.idPetugas"></span></p>
                    <p class="flex justify-between"><span class="text-gray-400">Peran/Role:</span> <span class="font-semibold" x-text="selectedPetugas.role"></span></p>
                    <p class="flex justify-between"><span class="text-gray-400">Status Keaktifan:</span> <span class="font-semibold" :class="selectedPetugas.status === 'Aktif' ? 'text-green-600' : 'text-gray-500'" x-text="selectedPetugas.status"></span></p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Kinerja Petugas (Tugas Selesai)</h4>
                    <div class="grid grid-cols-3 gap-3 text-center mb-4">
                        <div class="bg-blue-50/70 p-3 rounded-2xl border border-blue-100/50">
                            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">Cuci</p>
                            <p class="text-2xl font-black text-blue-700 mt-1" x-text="selectedPetugas.completed_washing || 0"></p>
                        </div>
                        <div class="bg-amber-50/70 p-3 rounded-2xl border border-amber-100/50">
                            <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">Setrika</p>
                            <p class="text-2xl font-black text-amber-700 mt-1" x-text="selectedPetugas.completed_ironing || 0"></p>
                        </div>
                        <div class="bg-emerald-50/70 p-3 rounded-2xl border border-emerald-100/50">
                            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Packing</p>
                            <p class="text-2xl font-black text-emerald-700 mt-1" x-text="selectedPetugas.completed_packing || 0"></p>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-500">Total Kontribusi Pekerjaan</span>
                        <strong class="text-slate-800 text-lg font-black" x-text="selectedPetugas.total_completed || 0"></strong>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" @click="closeModal()" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-gray-700 font-semibold rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </template>

    </div>
</div>


<script>
    function petugasManager() {
        const initialPetugas = @json($petugasData ?? []);

        return {
            petugasList: [],
            activeFilter: 'Semua',
            searchQuery: '',
            currentPage: 1,
            perPage: 8,
            modalMode: null, // 'add' | 'detail' | 'edit'
            selectedPetugas: null,

            async initData() {
                this.petugasList = Array.isArray(initialPetugas) ? initialPetugas : [];
            },

            get filteredData() {
                let result = this.petugasList;
                if (this.activeFilter !== 'Semua') result = result.filter(p => p.role === this.activeFilter);
                if (this.searchQuery.trim() !== '') result = result.filter(p => p.nama.toLowerCase().includes(this.searchQuery.toLowerCase()));
                return result;
            },

            get totalPages() { return Math.ceil(this.filteredData.length / this.perPage) || 1; },
            get paginatedData() { const start = (this.currentPage - 1) * this.perPage; return this.filteredData.slice(start, start + this.perPage); },
            get startItem() { return this.filteredData.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1; },
            get endItem() { return Math.min(this.currentPage * this.perPage, this.filteredData.length); },
            get totalPetugas() { return this.petugasList.length; },
            get aktifSekarang() { return this.petugasList.filter(p => p.status === 'Aktif').length; },
            get adminSistem() { return this.petugasList.filter(p => p.role === 'Admin').length; },

            prevPage() { if (this.currentPage > 1) this.currentPage--; },
            nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },

            openAddModal() {
                this.selectedPetugas = { nama: '', role: 'Operasional', status: 'Aktif', shift: '-' };
                this.modalMode = 'add';
            },
            async saveNewPetugas() {
                try {
                    const response = await fetch('{{ route("admin.petugas.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.selectedPetugas)
                    });

                    if (!response.ok) {
                        const err = await response.json();
                        alert('Gagal menambah petugas: ' + JSON.stringify(err.errors || err.message));
                        return;
                    }

                    const result = await response.json();
                    
                    // Format response keys for Alpine binding
                    result.idPetugas = result.id_petugas;
                    result.completed_washing = 0;
                    result.completed_ironing = 0;
                    result.completed_packing = 0;
                    result.total_completed = 0;

                    this.petugasList.push(result);
                    this.closeModal();
                    alert('Petugas berhasil ditambahkan!');
                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat menghubungi server.');
                }
            },
            editPetugas(petugas) {
                this.selectedPetugas = { ...petugas };
                this.modalMode = 'edit';
            },
            detailPetugas(petugas) {
                this.selectedPetugas = petugas ;
                this.modalMode = 'detail';
            },
            closeModal() {
                this.modalMode = null;
                this.selectedPetugas = null;
            },
            async updatePetugas() {
                try {
                    const response = await fetch(`/admin/petugas/${this.selectedPetugas.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.selectedPetugas)
                    });

                    if (!response.ok) {
                        const err = await response.json();
                        alert('Gagal mengupdate petugas: ' + JSON.stringify(err.errors || err.message));
                        return;
                    }

                    const result = await response.json();
                    const index = this.petugasList.findIndex(p => p.id === result.id);
                    if (index !== -1) {
                        this.petugasList[index] = {
                            ...this.petugasList[index],
                            ...result,
                            idPetugas: result.id_petugas
                        };
                    }
                    this.closeModal();
                    alert('Data petugas berhasil diupdate!');
                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat menghubungi server.');
                }
            },
            confirmDelete(petugas) {
                if (confirm(`Yakin ingin menghapus petugas "${petugas.nama}"?`)) {
                    this.deletePetugas(petugas.id);
                }
            },
            async deletePetugas(id) {
                try {
                    const response = await fetch(`/admin/petugas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!response.ok) {
                        alert('Gagal menghapus petugas dari server.');
                        return;
                    }

                    this.petugasList = this.petugasList.filter(p => p.id !== id);
                    alert('Petugas berhasil dihapus!');
                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat menghubungi server.');
                }
            }
        }
    }
</script>
@endsection
