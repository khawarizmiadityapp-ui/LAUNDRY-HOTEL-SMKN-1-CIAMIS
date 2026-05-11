<?php

use App\Services\MenuService;

if (!function_exists('menu_service')) {
    /**
     * Get MenuService instance
     *
     * @return MenuService
     */
    function menu_service(): MenuService
    {
        return app(MenuService::class);
    }
}

if (!function_exists('get_user_menus')) {
    /**
     * Get menus for current user
     *
     * @param string $type 'admin' or 'petugas'
     * @return array
     */
    function get_user_menus(string $type = 'petugas'): array
    {
        return menu_service()->getMenusForUser($type);
    }
}

if (!function_exists('get_division_label')) {
    /**
     * Get division label for display
     *
     * @param string|null $division
     * @return string
     */
    function get_division_label(?string $division): string
    {
        return menu_service()->getDivisionLabel($division);
    }
}

if (!function_exists('get_user_initials')) {
    /**
     * Get user initials for avatar
     *
     * @param string|null $name
     * @return string
     */
    function get_user_initials(?string $name): string
    {
        return menu_service()->getUserInitials($name);
    }
}

if (!function_exists('format_rupiah')) {
    /**
     * Format number to Rupiah currency
     *
     * @param float|int $amount
     * @param bool $withPrefix
     * @return string
     */
    function format_rupiah($amount, bool $withPrefix = true): string
    {
        $formatted = number_format($amount, 0, ',', '.');
        return $withPrefix ? "Rp {$formatted}" : $formatted;
    }
}

if (!function_exists('rupiah')) {
    /**
     * Format number to Rupiah currency (alias for format_rupiah)
     *
     * @param float|int $amount
     * @param bool $withPrefix
     * @return string
     */
    function rupiah($amount, bool $withPrefix = true): string
    {
        return format_rupiah($amount, $withPrefix);
    }
}

if (!function_exists('status_badge_class')) {
    /**
     * Get CSS class for status badge
     *
     * @param string $status
     * @return string
     */
    function status_badge_class(string $status): string
    {
        return match (strtolower($status)) {
            'pending', 'menunggu' => 'bg-yellow-100 text-yellow-700',
            'in_progress', 'proses', 'processing' => 'bg-blue-100 text-blue-700',
            'completed', 'selesai', 'done' => 'bg-green-100 text-green-700',
            'cancelled', 'dibatalkan', 'canceled' => 'bg-red-100 text-red-700',
            'paid', 'lunas' => 'bg-emerald-100 text-emerald-700',
            'unpaid', 'belum_lunas' => 'bg-orange-100 text-orange-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

if (!function_exists('status_label')) {
    /**
     * Get human-readable status label
     *
     * @param string $status
     * @return string
     */
    function status_label(string $status): string
    {
        return match (strtolower($status)) {
            'pending' => 'Menunggu',
            'in_progress', 'processing' => 'Dalam Proses',
            'completed', 'done' => 'Selesai',
            'cancelled', 'canceled' => 'Dibatalkan',
            'paid' => 'Lunas',
            'unpaid' => 'Belum Lunas',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
