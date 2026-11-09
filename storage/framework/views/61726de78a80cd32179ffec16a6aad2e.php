<?php $__env->startSection('title', 'Error Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Error Logs Dashboard</h1>
        <p class="text-gray-600 mt-1">Monitor and manage system errors</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Errors</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($stats['total']); ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Critical</p>
                    <p class="text-2xl font-bold text-red-600 mt-1"><?php echo e($stats['critical_count']); ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Errors</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1"><?php echo e($stats['error_count']); ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Warnings</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1"><?php echo e($stats['warning_count']); ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form action="<?php echo e(route('admin.errors.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                <select name="severity" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    <option value="CRITICAL" <?php echo e(request('severity') == 'CRITICAL' ? 'selected' : ''); ?>>Critical</option>
                    <option value="ERROR" <?php echo e(request('severity') == 'ERROR' ? 'selected' : ''); ?>>Error</option>
                    <option value="WARNING" <?php echo e(request('severity') == 'WARNING' ? 'selected' : ''); ?>>Warning</option>
                    <option value="INFO" <?php echo e(request('severity') == 'INFO' ? 'selected' : ''); ?>>Info</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="resolved" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    <option value="unresolved" <?php echo e(request('resolved') == 'unresolved' ? 'selected' : ''); ?>>Unresolved</option>
                    <option value="resolved" <?php echo e(request('resolved') == 'resolved' ? 'selected' : ''); ?>>Resolved</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search message..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                <a href="<?php echo e(route('admin.errors.index')); ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Error Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $errorLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($errorLog->severity_badge_class); ?>">
                            <?php echo e($errorLog->severity); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-md truncate" title="<?php echo e($errorLog->message); ?>">
                            <?php echo e($errorLog->message); ?>

                        </div>
                        <?php if($errorLog->context): ?>
                        <div class="text-xs text-gray-500 mt-1"><?php echo e($errorLog->context); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <?php echo e($errorLog->user_email ?? 'System'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <?php echo e($errorLog->created_at->format('M d, Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($errorLog->resolved): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                            Resolved
                        </span>
                        <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">
                            Unresolved
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="<?php echo e(route('admin.errors.show', $errorLog->id)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        <?php if(!$errorLog->resolved): ?>
                        <button onclick="resolveModal(<?php echo e($errorLog->id); ?>)" class="text-green-600 hover:text-green-900 mr-3">Resolve</button>
                        <?php endif; ?>
                        <form action="<?php echo e(route('admin.errors.destroy', $errorLog->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this error log?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        No error logs found
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($errorLogs->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($errorLogs->onEachSide(1)->links('vendor.pagination.custom')); ?>

        </div>
        <?php endif; ?>
    </div>

    <!-- Clear Old Logs Button -->
    <div class="mt-6 flex justify-end">
        <button onclick="clearOldModal()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
            Clear Old Logs
        </button>
    </div>
</div>

<!-- Resolve Modal -->
<div id="resolveModal" class="hidden fixed inset-0 bg-black/50 z-[9999] overflow-y-auto p-4">
    <div class="flex items-start justify-center min-h-screen">
        <div class="bg-white p-6 rounded-xl w-full max-w-md my-8 shadow-xl">
            <h2 class="font-bold text-lg mb-4 text-slate-800">Resolve Error</h2>
            <form action="<?php echo e(route('admin.errors.resolve', ':id')); ?>" method="POST" id="resolveForm">
                <?php echo csrf_field(); ?>
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

<!-- Clear Old Logs Modal -->
<div id="clearOldModal" class="hidden fixed inset-0 bg-black/50 z-[9999] overflow-y-auto p-4">
    <div class="flex items-start justify-center min-h-screen">
        <div class="bg-white p-6 rounded-xl w-full max-w-md my-8 shadow-xl">
            <h2 class="font-bold text-lg mb-4 text-slate-800">Clear Old Error Logs</h2>
            <form action="<?php echo e(route('admin.errors.clear-old')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delete logs older than (days)</label>
                    <input type="number" name="days" value="30" min="1" max="365" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Only resolved logs will be deleted</p>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Clear Logs
                    </button>
                    <button type="button" onclick="closeClearOldModal()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function resolveModal(id) {
    const form = document.getElementById('resolveForm');
    form.action = form.action.replace(':id', id);
    document.getElementById('resolveModal').classList.remove('hidden');
}

function closeResolveModal() {
    document.getElementById('resolveModal').classList.add('hidden');
}

function clearOldModal() {
    document.getElementById('clearOldModal').classList.remove('hidden');
}

function closeClearOldModal() {
    document.getElementById('clearOldModal').classList.add('hidden');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\errors\index.blade.php ENDPATH**/ ?>