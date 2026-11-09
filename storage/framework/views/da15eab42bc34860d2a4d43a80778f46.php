<?php $__env->startSection('title', 'Error Log Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="mb-6">
        <a href="<?php echo e(route('admin.errors.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Back to Error Logs
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Error Log Details</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full <?php echo e($errorLog->severity_badge_class); ?>">
                    <?php echo e($errorLog->severity); ?>

                </span>
                <?php if($errorLog->resolved): ?>
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                    Resolved
                </span>
                <?php else: ?>
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">
                    Unresolved
                </span>
                <?php endif; ?>
            </div>
            <?php if(!$errorLog->resolved): ?>
            <button onclick="resolveModal(<?php echo e($errorLog->id); ?>)" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                Mark as Resolved
            </button>
            <?php endif; ?>
        </div>

        <!-- Details -->
        <div class="px-6 py-6 space-y-6">
            <!-- Message -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Message</h3>
                <p class="text-gray-900 bg-gray-50 p-4 rounded-lg"><?php echo e($errorLog->message); ?></p>
            </div>

            <?php if($errorLog->context): ?>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Context</h3>
                <p class="text-gray-700"><?php echo e($errorLog->context); ?></p>
            </div>
            <?php endif; ?>

            <!-- File & Line -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">File</h3>
                    <p class="text-gray-900 font-mono text-sm"><?php echo e($errorLog->file ?? 'N/A'); ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Line</h3>
                    <p class="text-gray-900 font-mono text-sm"><?php echo e($errorLog->line ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- User Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">User</h3>
                    <p class="text-gray-900"><?php echo e($errorLog->user_email ?? 'System'); ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">IP Address</h3>
                    <p class="text-gray-900 font-mono text-sm"><?php echo e($errorLog->ip_address ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- Request Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">URL</h3>
                    <p class="text-gray-900 font-mono text-sm break-all"><?php echo e($errorLog->url ?? 'N/A'); ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Method</h3>
                    <p class="text-gray-900 font-mono text-sm"><?php echo e($errorLog->method ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Created At</h3>
                    <p class="text-gray-900"><?php echo e($errorLog->created_at->format('M d, Y H:i:s')); ?></p>
                </div>
                <?php if($errorLog->resolved): ?>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Resolved At</h3>
                    <p class="text-gray-900"><?php echo e($errorLog->resolved_at ? $errorLog->resolved_at->format('M d, Y H:i:s') : 'N/A'); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <?php if($errorLog->resolved): ?>
            <!-- Resolution Info -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Resolution</h3>
                <p class="text-gray-900 mb-2">Resolved by: <?php echo e($errorLog->resolver?->email ?? 'Unknown'); ?></p>
                <?php if($errorLog->resolution_notes): ?>
                <p class="text-gray-700"><?php echo e($errorLog->resolution_notes); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Stack Trace -->
            <?php if($errorLog->trace): ?>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Stack Trace</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-xs"><?php echo e($errorLog->trace); ?></pre>
            </div>
            <?php endif; ?>

            <!-- Additional Data -->
            <?php if($errorLog->additional_data): ?>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Additional Data</h3>
                <pre class="bg-gray-50 p-4 rounded-lg overflow-x-auto text-xs"><?php echo e(json_encode($errorLog->additional_data, JSON_PRETTY_PRINT)); ?></pre>
            </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 border-t border-gray-200 flex gap-2">
            <form action="<?php echo e(route('admin.errors.destroy', $errorLog->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this error log?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
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
            <form action="<?php echo e(route('admin.errors.resolve', $errorLog->id)); ?>" method="POST">
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

<?php $__env->startPush('scripts'); ?>
<script>
function resolveModal(id) {
    document.getElementById('resolveModal').classList.remove('hidden');
}

function closeResolveModal() {
    document.getElementById('resolveModal').classList.add('hidden');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\admin\errors\show.blade.php ENDPATH**/ ?>