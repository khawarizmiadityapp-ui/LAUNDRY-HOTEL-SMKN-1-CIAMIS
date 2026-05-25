@extends('layouts.admin')

@section('title', 'Error Log Details')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.errors.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Back to Error Logs
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Error Log Details</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $errorLog->severity_badge_class }}">
                    {{ $errorLog->severity }}
                </span>
                @if($errorLog->resolved)
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                    Resolved
                </span>
                @else
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">
                    Unresolved
                </span>
                @endif
            </div>
            @if(!$errorLog->resolved)
            <button onclick="resolveModal({{ $errorLog->id }})" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                Mark as Resolved
            </button>
            @endif
        </div>

        <!-- Details -->
        <div class="px-6 py-6 space-y-6">
            <!-- Message -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Message</h3>
                <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $errorLog->message }}</p>
            </div>

            @if($errorLog->context)
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Context</h3>
                <p class="text-gray-700">{{ $errorLog->context }}</p>
            </div>
            @endif

            <!-- File & Line -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">File</h3>
                    <p class="text-gray-900 font-mono text-sm">{{ $errorLog->file ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Line</h3>
                    <p class="text-gray-900 font-mono text-sm">{{ $errorLog->line ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- User Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">User</h3>
                    <p class="text-gray-900">{{ $errorLog->user_email ?? 'System' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">IP Address</h3>
                    <p class="text-gray-900 font-mono text-sm">{{ $errorLog->ip_address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Request Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">URL</h3>
                    <p class="text-gray-900 font-mono text-sm break-all">{{ $errorLog->url ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Method</h3>
                    <p class="text-gray-900 font-mono text-sm">{{ $errorLog->method ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Created At</h3>
                    <p class="text-gray-900">{{ $errorLog->created_at->format('M d, Y H:i:s') }}</p>
                </div>
                @if($errorLog->resolved)
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Resolved At</h3>
                    <p class="text-gray-900">{{ $errorLog->resolved_at ? $errorLog->resolved_at->format('M d, Y H:i:s') : 'N/A' }}</p>
                </div>
                @endif
            </div>

            @if($errorLog->resolved)
            <!-- Resolution Info -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Resolution</h3>
                <p class="text-gray-900 mb-2">Resolved by: {{ $errorLog->resolver?->email ?? 'Unknown' }}</p>
                @if($errorLog->resolution_notes)
                <p class="text-gray-700">{{ $errorLog->resolution_notes }}</p>
                @endif
            </div>
            @endif

            <!-- Stack Trace -->
            @if($errorLog->trace)
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Stack Trace</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-xs">{{ $errorLog->trace }}</pre>
            </div>
            @endif

            <!-- Additional Data -->
            @if($errorLog->additional_data)
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Additional Data</h3>
                <pre class="bg-gray-50 p-4 rounded-lg overflow-x-auto text-xs">{{ json_encode($errorLog->additional_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 border-t border-gray-200 flex gap-2">
            <form action="{{ route('admin.errors.destroy', $errorLog->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this error log?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div id="resolveModal" class="hidden fixed inset-0 bg-black/50 z-[9999] overflow-y-auto p-4">
    <div class="flex items-start justify-center min-h-screen">
        <div class="bg-white p-6 rounded-xl w-full max-w-md my-8 shadow-xl">
            <h2 class="font-bold text-lg mb-4 text-slate-800">Resolve Error</h2>
            <form action="{{ route('admin.errors.resolve', $errorLog->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resolution Notes</label>
                    <textarea name="resolution_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add notes about how this was resolved..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Mark as Resolved
                    </button>
                    <button type="button" onclick="closeResolveModal()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function resolveModal(id) {
    document.getElementById('resolveModal').classList.remove('hidden');
}

function closeResolveModal() {
    document.getElementById('resolveModal').classList.add('hidden');
}
</script>
@endpush
@endsection
