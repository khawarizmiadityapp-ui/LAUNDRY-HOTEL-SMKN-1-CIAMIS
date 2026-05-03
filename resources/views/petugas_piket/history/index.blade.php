@extends('layouts.petugas_piket')
@section('title', 'Riwayat Tugas')
@section('content')

<div class="p-6 max-w-7xl mx-auto animate-fade-in">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Tugas</h1>
        <p class="text-slate-500 mt-1">Daftar tugas yang telah diselesaikan oleh divisi {{ ucfirst($division ?? 'Piket') }}.</p>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Daftar Selesai</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-left px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">No. Transaksi</th>
                        <th class="text-left px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Petugas Piket</th>
                        <th class="text-left px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Waktu Selesai</th>
                        <th class="text-left px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if(isset($completedTasks) && $completedTasks->count() > 0)
                        @foreach($completedTasks as $task)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-lg">
                                        #{{ $task->transaksi->transaksi_code ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800">{{ $task->transaksi->customer_name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $task->petugas_name ?? $task->petugas->nama ?? 'Sistem' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm">
                                    {{ $task->completed_at ? $task->completed_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[11px] font-bold uppercase tracking-wider border border-emerald-100/50">
                                        {{ $task->transaksi->status ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada riwayat tugas yang diselesaikan.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($completedTasks) && $completedTasks->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $completedTasks->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>
@endpush
