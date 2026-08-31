
<aside id="sidebar"
           class="fixed lg:relative z-30 w-64 h-full bg-white flex flex-col border-r border-slate-100 shadow-card
                  transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

        <!-- Logo -->
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <img src="<?php echo e(asset('images/logo-bening.png')); ?>" alt="Logo Bening Laundry" class="w-9 h-9 object-contain rounded-xl drop-shadow-sm">
                <div>
                    <p class="font-display text-base font-700 text-slate-900 leading-none"><?php echo e($sidebarBrandName ?? 'Bening Laundry'); ?></p>
                    <p class="text-[10px] font-500 text-slate-400 tracking-widest uppercase mt-0.5"><?php echo e($sidebarBrandTagline ?? 'Management Portal'); ?></p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            <?php
                $sidebarMenus = $sidebarMenus ?? collect();
                $sidebarOnDutyCount = $sidebarOnDutyCount ?? 0;
                $sidebarOnDutyPetugas = $sidebarOnDutyPetugas ?? collect();
            ?>

            <?php $__currentLoopData = $sidebarMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($menu['url']); ?>"
                   class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                          <?php echo e(!empty($menu['active']) ? 'active text-white' : 'text-slate-500'); ?>">
                    <svg class="w-5 h-5 shrink-0 <?php echo e(!empty($menu['active']) ? 'stroke-white' : 'stroke-slate-400'); ?>"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($menu['icon']); ?>" />
                    </svg>
                    <span class="flex-1"><?php echo e($menu['label']); ?></span>
                    <?php if(!empty($menu['badge'])): ?>
                        <span class="inline-flex min-w-6 h-6 items-center justify-center px-1.5 rounded-full text-[11px] font-semibold <?php echo e(!empty($menu['active']) ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700'); ?>">
                            <?php echo e($menu['badge']); ?>

                        </span>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="mt-5 mx-1 p-3 rounded-xl border border-emerald-100 bg-emerald-50/70">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700">Sedang Bertugas</p>
                    <span class="text-[11px] font-semibold text-emerald-700"><?php echo e($sidebarOnDutyCount); ?></span>
                </div>

                <?php if($sidebarOnDutyPetugas->isEmpty()): ?>
                    <p class="mt-2 text-xs text-slate-500">Belum ada petugas aktif saat ini.</p>
                <?php else: ?>
                    <ul class="mt-2 space-y-1.5">
                        <?php $__currentLoopData = $sidebarOnDutyPetugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $petugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-start gap-2 text-xs text-slate-700">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span class="leading-tight">
                                    <span class="font-medium"><?php echo e($petugas->nama); ?></span>
                                    <span class="text-slate-500">(<?php echo e($petugas->shift); ?>)</span>
                                </span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Logout -->
        <div class="px-3 py-4 border-t border-slate-100">
            <button type="button" onclick="confirmLogout(event)"
               class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-rose-500
                      hover:bg-rose-50 transition-all duration-150">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span>Logout</span>
            </button>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </aside>
<?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views/admin/sidebar.blade.php ENDPATH**/ ?>