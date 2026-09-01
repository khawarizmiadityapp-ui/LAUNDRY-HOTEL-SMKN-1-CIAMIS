<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Get menus for the current user based on role and division
     *
     * @param string $type 'admin' or 'petugas'
     * @return array
     */
    public function getMenusForUser(string $type = 'petugas'): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return [];
        }

        $role = strtolower((string) ($user->role ?? 'staff'));
        $division = $this->normalizeDivision((string) ($user->division ?? ''));
        
        // Get menus from config dynamically without caching to prevent stale menu state
        $configKey = $type === 'admin' ? 'admin_menus' : 'petugas_menus';
        $allMenus = config("sidebar.{$configKey}", []);

        // Filter menus based on role and division
        $filteredMenus = collect($allMenus)->filter(function ($menu) use ($role, $division) {
            return $this->canAccessMenu($menu, $role, $division);
        })->map(function ($menu) {
            return $this->processMenu($menu);
        })->values()->all();

        // If no menus found, return all (fallback for safety)
        if (empty($filteredMenus)) {
            return collect($allMenus)->map(function ($menu) {
                return $this->processMenu($menu);
            })->values()->all();
        }

        return $filteredMenus;
    }

    /**
     * Check if user can access a menu
     *
     * @param array $menu
     * @param string $role
     * @param string $division
     * @return bool
     */
    protected function canAccessMenu(array $menu, string $role, string $division): bool
    {
        $isAdminRole = in_array($role, ['super_admin', 'super admin', 'superadmin', 'admin'], true);

        // Super Admin and Admin can access everything (bypass division check)
        if ($isAdminRole) {
            return true;
        }

        // Check role permission for staff / others
        $allowedRoles = $menu['roles'] ?? ['admin', 'staff'];
        if (!in_array($role, $allowedRoles, true)) {
            return false;
        }

        // Check division permission for staff
        $allowedDivisions = $menu['divisions'] ?? [];
        
        // Exclude 'Daftar Layanan' / 'Layanan' from sidebar for staff (including all_roles)
        $label = strtolower($menu['label'] ?? '');
        $route = strtolower($menu['route'] ?? '');
        if (str_contains($label, 'layanan') || str_contains($route, 'layanan')) {
            return false;
        }

        // If no division restriction, allow access
        if (empty($allowedDivisions)) {
            return true;
        }

        // Special division 'all_roles' has access to everything
        if ($division === 'all_roles') {
            return true;
        }

        // If user has no division, deny access (staff must have division)
        if (empty($division)) {
            return false;
        }

        // Check if user's division is in allowed divisions
        return in_array($division, $allowedDivisions, true);
    }

    /**
     * Process menu item (add route URL, check active state, etc.)
     *
     * @param array $menu
     * @return array
     */
    protected function processMenu(array $menu): array
    {
        // Generate route URL if route name is provided
        if (isset($menu['route']) && Route::has($menu['route'])) {
            $menu['url'] = route($menu['route']);
        } else {
            $menu['url'] = '#';
        }

        // Check if menu is active
        $menu['is_active'] = $this->isMenuActive($menu);

        return $menu;
    }

    /**
     * Check if menu is currently active
     *
     * @param array $menu
     * @return bool
     */
    protected function isMenuActive(array $menu): bool
    {
        $activePatterns = $menu['active'] ?? [];

        if (empty($activePatterns)) {
            // Fallback to route name
            if (isset($menu['route'])) {
                return request()->routeIs($menu['route']);
            }
            return false;
        }

        foreach ($activePatterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize division name using aliases
     *
     * @param string $division
     * @return string
     */
    public function normalizeDivision(string $division): string
    {
        $division = strtolower(trim($division));
        
        $aliases = config('sidebar.division_aliases', [
            'kasir' => 'customer_service',
            'customer service' => 'customer_service',
            'cs' => 'customer_service',
            'ironing' => 'setrika',
        ]);

        return $aliases[$division] ?? $division;
    }

    /**
     * Get division label for display
     *
     * @param string|null $division
     * @return string
     */
    public function getDivisionLabel(?string $division): string
    {
        if (empty($division)) {
            return 'Staff';
        }

        $normalizedDivision = $this->normalizeDivision($division);
        
        $labels = config('sidebar.division_labels', [
            'washing' => 'Washing',
            'setrika' => 'Ironing',
            'ironing' => 'Ironing',
            'packing' => 'Packing',
            'customer_service' => 'Customer Service',
            'inventory' => 'Inventory',
        ]);

        return $labels[$normalizedDivision] ?? ucfirst($normalizedDivision);
    }

    /**
     * Get user initials for avatar
     *
     * @param string|null $name
     * @return string
     */
    public function getUserInitials(?string $name): string
    {
        if (empty($name)) {
            return 'SP';
        }

        return collect(explode(' ', $name))
            ->take(2)
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->join('');
    }

    /**
     * Get brand information
     *
     * @return array
     */
    public function getBrandInfo(): array
    {
        return config('sidebar.brand', [
            'name' => 'Bening Laundry',
            'tagline' => 'Management Portal',
        ]);
    }
}
