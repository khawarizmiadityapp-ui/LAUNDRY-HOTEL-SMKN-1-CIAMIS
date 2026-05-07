<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $currentRole = strtolower((string) optional(Auth::user())->role);
            $isAdminLike = str_contains($currentRole, 'admin');

            $sidebarMenus = collect(config('sidebar.menus', []))
                ->filter(function (array $menu) use ($currentRole, $isAdminLike) {
                    $roles = collect($menu['roles'] ?? [])
                        ->map(static fn ($role) => strtolower((string) $role))
                        ->all();

                    if (empty($roles)) {
                        return true;
                    }

                    // Fail-open so sidebar menu does not disappear when role context is missing.
                    if ($currentRole === '') {
                        return true;
                    }

                    // Allow users with roles containing "admin" (e.g. "super admin")
                    if ($isAdminLike && in_array('admin', $roles, true)) {
                        return true;
                    }

                    return in_array($currentRole, $roles, true);
                })
                ->map(function (array $menu) {
                    $routeName = (string) ($menu['route'] ?? '');
                    $activePatterns = $menu['active'] ?? [$routeName];

                    return [
                        'icon' => $menu['icon'] ?? '',
                        'label' => $menu['label'] ?? '-',
                        'url' => $routeName !== '' && Route::has($routeName) ? route($routeName) : '#',
                        'active' => request()->routeIs(...$activePatterns),
                        'badge' => null,
                    ];
                })
                ->values();

            // Defensive fallback: if role filtering removed all menus, show full menu set (safe default)
            if ($sidebarMenus->isEmpty()) {
                $sidebarMenus = collect(config('sidebar.menus', []))
                    ->map(function (array $menu) {
                        $routeName = (string) ($menu['route'] ?? '');
                        $activePatterns = $menu['active'] ?? [$routeName];

                        return [
                            'icon' => $menu['icon'] ?? '',
                            'label' => $menu['label'] ?? '-',
                            'url' => $routeName !== '' && Route::has($routeName) ? route($routeName) : '#',
                            'active' => false,
                            'badge' => null,
                        ];
                    })
                    ->values();
            }

            $view->with('sidebarBrandName', config('sidebar.brand.name', 'Bening Laundry'));
            $view->with('sidebarBrandTagline', config('sidebar.brand.tagline', 'Management Portal'));
            $view->with('sidebarMenus', $sidebarMenus);

            try {
                $onlineStaff = Cache::get('online_staff_users', []);
                $lastAllowed = now()->subMinutes((int) config('session.lifetime', 120))->timestamp;

                $onlineIds = collect($onlineStaff)
                    ->filter(static fn ($lastSeen) => (int) $lastSeen >= $lastAllowed)
                    ->keys()
                    ->map(static fn ($id) => (int) $id)
                    ->values()
                    ->all();

                if (count($onlineIds) !== count($onlineStaff)) {
                    $freshOnlineStaff = [];

                    foreach ($onlineIds as $id) {
                        $freshOnlineStaff[$id] = now()->timestamp;
                    }

                    Cache::forever('online_staff_users', $freshOnlineStaff);
                }

                $activePetugas = User::query()
                    ->where('role', 'staff')
                    ->whereIn('id', $onlineIds)
                    ->orderBy('name')
                    ->get(['id', 'name', 'division'])
                    ->map(function (User $user) {
                        return (object) [
                            'id' => $user->id,
                            'nama' => $user->name,
                            'shift' => $user->division ? ucwords(str_replace('_', ' ', $user->division)) : '-',
                        ];
                    });

                $sidebarOnDutyCount = $activePetugas->count();

                $sidebarMenus = $sidebarMenus->map(function (array $menu) use ($sidebarOnDutyCount) {
                    if (($menu['label'] ?? '') === 'Petugas' && $sidebarOnDutyCount > 0) {
                        $menu['badge'] = $sidebarOnDutyCount;
                    }

                    return $menu;
                });

                $view->with('sidebarMenus', $sidebarMenus);
                $view->with('sidebarOnDutyCount', $sidebarOnDutyCount);
                $view->with('sidebarOnDutyPetugas', $activePetugas->take(5));
            } catch (Throwable $e) {
                $view->with('sidebarOnDutyCount', 0);
                $view->with('sidebarOnDutyPetugas', collect());
            }
        });
    }
}
