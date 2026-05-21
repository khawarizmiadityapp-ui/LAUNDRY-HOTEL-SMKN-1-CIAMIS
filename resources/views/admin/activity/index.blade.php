{{-- resources/views/admin/activity/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Activity Log - Bening Laundry')
@section('content')

<div class="container mx-auto px-4 py-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Activity Log</h1>
            <p class="text-gray-500 text-sm mt-1">Track semua aktivitas pengguna di sistem</p>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.activity.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari aktivitas..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Model Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                <select name="model" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Model</option>
                    @foreach($models as $model)
                        <option value="{{ $model['value'] }}" {{ request('model') == $model['value'] ? 'selected' : '' }}>
                            {{ $model['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- User Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Event Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                <select name="event" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Event</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.activity.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- ACTIVITY TABLE -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $activity->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $activity->causer->name ?? 'System' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $activity->causer->email ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($activity->event == 'created')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-plus-circle"></i> Created
                                </span>
                            @elseif($activity->event == 'updated')
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    <i class="fas fa-edit"></i> Updated
                                </span>
                            @elseif($activity->event == 'deleted')
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                    <i class="fas fa-trash"></i> Deleted
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                    {{ $activity->event }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ class_basename($activity->subject_type) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $activity->description }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($activity->properties->has('attributes'))
                                <button onclick="showChanges({{ $activity->id }})" 
                                        class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Tidak ada activity log ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $activities->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

<!-- Modal for Changes -->
<div id="changesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Activity Changes</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div id="changesContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
function showChanges(activityId) {
    // In a real implementation, you would fetch the activity details via AJAX
    // For now, we'll show a placeholder
    const modal = document.getElementById('changesModal');
    const content = document.getElementById('changesContent');
    
    content.innerHTML = '<p class="text-gray-600">Loading changes...</p>';
    modal.classList.remove('hidden');
    
    // Simulate loading (in real app, use fetch/axios)
    setTimeout(() => {
        content.innerHTML = `
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Old Values:</h4>
                    <pre class="bg-gray-50 p-4 rounded-lg text-sm">Loading...</pre>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">New Values:</h4>
                    <pre class="bg-gray-50 p-4 rounded-lg text-sm">Loading...</pre>
                </div>
            </div>
        `;
    }, 500);
}

function closeModal() {
    document.getElementById('changesModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('changesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection
