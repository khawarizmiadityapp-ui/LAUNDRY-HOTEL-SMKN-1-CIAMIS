{{--
  Contoh struktur halaman CRUD untuk setiap divisi
  File ini bisa diletakkan di resources/views/petugas_piket/
  dengan nama: washing.blade.php, setrika.blade.php, packing.blade.php, dll
--}}

@extends('layouts.petugas_piket')

@section('title', 'Washing | Dashboard Petugas')

@section('content')
<div class="flex-1 flex flex-col">

    {{-- Header Section --}}
    <div class="bg-white border-b border-slate-100 px-8 py-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Washing Tasks</h1>
                <p class="text-slate-600 text-sm mt-1">Kelola dan monitor semua tugas washing</p>
            </div>
            <a href="{{ route('petugas_piket.washing.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors w-fit">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Task
            </a>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="flex-1 p-8">

        {{-- Filter Section (Optional) --}}
        <div class="mb-6 flex gap-3 flex-wrap">
            <button class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                Semua (12)
            </button>
            <button class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                Pending (5)
            </button>
            <button class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                In Progress (4)
            </button>
            <button class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                Completed (3)
            </button>
        </div>

        {{-- Tasks Table --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">ID Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    {{-- Sample Row 1 --}}
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">#TRX-001</td>
                        <td class="px-6 py-4 text-sm text-slate-600">Budi Santoso</td>
                        <td class="px-6 py-4 text-sm text-slate-600">15 pieces</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium text-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-600"></span>
                                In Progress
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 65%"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-700">65%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="#" class="p-2 hover:bg-slate-100 rounded-lg transition text-blue-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <a href="#" class="p-2 hover:bg-slate-100 rounded-lg transition text-red-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Sample Row 2 --}}
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">#TRX-002</td>
                        <td class="px-6 py-4 text-sm text-slate-600">Siti Nurhaliza</td>
                        <td class="px-6 py-4 text-sm text-slate-600">8 pieces</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-200 rounded-full h-2">
                                    <div class="bg-green-600 h-full rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-700">100%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="#" class="p-2 hover:bg-slate-100 rounded-lg transition text-blue-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Sample Row 3 --}}
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">#TRX-003</td>
                        <td class="px-6 py-4 text-sm text-slate-600">Ahmad Wijaya</td>
                        <td class="px-6 py-4 text-sm text-slate-600">22 pieces</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 0%"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-700">0%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="#" class="p-2 hover:bg-slate-100 rounded-lg transition text-blue-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <a href="#" class="p-2 hover:bg-slate-100 rounded-lg transition text-red-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination (Optional) --}}
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm text-slate-600">Showing 1 to 3 of 12 results</p>
            <div class="flex gap-2">
                <button class="px-3 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">
                    ← Previous
                </button>
                <button class="px-3 py-2 border border-blue-600 bg-blue-600 rounded-lg text-sm font-medium text-white">
                    1
                </button>
                <button class="px-3 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">
                    2
                </button>
                <button class="px-3 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Next →
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handler untuk status update
    function updateStatus(taskId, newStatus) {
        fetch(`{{ route('petugas_piket.tasks.updateStatus', ':id') }}`.replace(':id', taskId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            // Refresh page atau update UI
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
