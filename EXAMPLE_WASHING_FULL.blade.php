{{--
  CONTOH IMPLEMENTASI LENGKAP HALAMAN WASHING

  Copy dan ganti:
  - Ganti "Washing" dengan nama division lain (Setrika, Packing, dll)
  - Ganti "washing" dengan division name
  - Adjust model/table references sesuai kebutuhan

  Files yang perlu dibuat:
  1. resources/views/petugas_piket/washing.blade.php (file ini)
  2. Update app/Http/Controllers/PetugasController.php dengan method washing()
  3. Ensure route ada di routes/web.php
--}}

@extends('layouts.petugas_piket')

@section('title', 'Washing Tasks | Dashboard Petugas')

@section('content')
<div class="flex-1 flex flex-col">

    {{-- ========== HEADER SECTION ========== --}}
    <div class="bg-white border-b border-slate-100 px-8 py-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Washing Tasks</h1>
                <p class="text-slate-600 text-sm mt-1">Kelola dan monitor semua tugas washing Anda</p>
            </div>
            <button onclick="document.getElementById('newTaskModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors w-fit">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Task
            </button>
        </div>
    </div>

    {{-- ========== CONTENT SECTION ========== --}}
    <div class="flex-1 p-8">

        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 flex gap-3">
                <svg class="w-5 h-5 mt-0.5 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="font-semibold text-red-800">Error</h4>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-800">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 flex gap-3">
                <svg class="w-5 h-5 mt-0.5 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="font-semibold text-green-800">Success</h4>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Filter Section --}}
        <div class="mb-6 flex gap-3 flex-wrap">
            <a href="{{ route('petugas_piket.washing.index') }}"
               class="px-4 py-2 {{ request('status') == null ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }} rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                Semua ({{ $allCount ?? 0 }})
            </a>
            <a href="{{ route('petugas_piket.washing.index', ['status' => 'pending']) }}"
               class="px-4 py-2 {{ request('status') == 'pending' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }} rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                Pending ({{ $pendingCount ?? 0 }})
            </a>
            <a href="{{ route('petugas_piket.washing.index', ['status' => 'in_progress']) }}"
               class="px-4 py-2 {{ request('status') == 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }} rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                In Progress ({{ $inProgressCount ?? 0 }})
            </a>
            <a href="{{ route('petugas_piket.washing.index', ['status' => 'completed']) }}"
               class="px-4 py-2 {{ request('status') == 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }} rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                Completed ({{ $completedCount ?? 0 }})
            </a>
        </div>

        {{-- Tasks Table --}}
        @if ($tasks->count() > 0)
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
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
                            @foreach ($tasks as $task)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $task->transaksi->nomor_nota ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $task->transaksi->customer->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $task->quantity }} pieces</td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-600', 'label' => 'Pending'],
                                                'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-600', 'label' => 'In Progress'],
                                                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-600', 'label' => 'Completed'],
                                            ];
                                            $config = $statusConfig[$task->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $config['bg'] }} {{ $config['text'] }} font-medium text-xs">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                            {{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-slate-200 rounded-full h-2 min-w-[100px]">
                                                <div class="bg-blue-600 h-full rounded-full transition-all" style="width: {{ $task->progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-slate-700 min-w-fit">{{ $task->progress }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- View --}}
                                            <a href="#"
                                               onclick="showTaskDetail({{ $task->id }})"
                                               class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-600 hover:text-blue-600"
                                               title="View details">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Update Status Dropdown --}}
                                            <div class="relative group">
                                                <button class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-600"
                                                        title="Update status">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>

                                                {{-- Dropdown Menu --}}
                                                <div class="hidden group-hover:block absolute right-0 mt-0 w-40 bg-white border border-slate-200 rounded-lg shadow-lg z-10">
                                                    @foreach (['pending', 'in_progress', 'completed'] as $status)
                                                        <form method="POST"
                                                              action="{{ route('petugas_piket.tasks.updateStatus', $task->id) }}"
                                                              class="block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="{{ $status }}">
                                                            <button type="submit"
                                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-slate-50 first:rounded-t-lg last:rounded-b-lg {{ $task->status === $status ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700' }}">
                                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                @if ($task->status === $status)
                                                                    <span class="float-right">✓</span>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Edit --}}
                                            <a href="#"
                                               onclick="editTask({{ $task->id }})"
                                               class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-600 hover:text-amber-600"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route('petugas_piket.washing.destroy', $task->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-600 hover:text-red-600"
                                                        title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-slate-600">
                    Showing {{ ($tasks->currentPage() - 1) * $tasks->perPage() + 1 }} to
                    {{ min($tasks->currentPage() * $tasks->perPage(), $tasks->total()) }}
                    of {{ $tasks->total() }} results
                </p>
                <div class="flex gap-2">
                    {{ $tasks->links('pagination::tailwind') }}
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-lg font-bold text-slate-900 mb-1">No Tasks Found</h3>
                <p class="text-slate-600 text-sm mb-4">There are no washing tasks at the moment.</p>
                <button onclick="document.getElementById('newTaskModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create First Task
                </button>
            </div>
        @endif
    </div>
</div>

{{-- ========== MODALS ========== --}}

{{-- New Task Modal --}}
<div id="newTaskModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full shadow-xl">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Add New Task</h3>
            <button type="button"
                    onclick="document.getElementById('newTaskModal').classList.add('hidden')"
                    class="text-slate-500 hover:text-slate-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('petugas_piket.washing.store') }}" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Transaction ID</label>
                <input type="number" name="transaksi_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Quantity</label>
                <input type="number" name="quantity" value="1" required class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button"
                        onclick="document.getElementById('newTaskModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show task detail
    function showTaskDetail(taskId) {
        alert('View detail task ' + taskId);
        // TODO: Implement detail modal
    }

    // Edit task
    function editTask(taskId) {
        alert('Edit task ' + taskId);
        // TODO: Implement edit modal
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endpush
