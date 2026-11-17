<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceNoticeMiddleware
{
    /**
     * Handle an incoming request.
     * When full-lock maintenance mode is enabled by Super Admin,
     * show maintenance page to everyone except Super Admin and login/2FA routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenance = get_maintenance_config();

        if (!($maintenance['enabled'] ?? false) || ($maintenance['mode'] ?? 'banner') !== 'full') {
            return $next($request);
        }

        // Selalu izinkan rute login, 2FA, logout, dan health check agar Super Admin tetap bisa masuk
        if ($request->is('login', 'login/*', 'logout', 'up')) {
            return $next($request);
        }

        // Super Admin selalu memiliki akses penuh selama maintenance
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $maintenance['title'],
                'detail' => $maintenance['message'],
                'eta' => $maintenance['eta'],
            ], 503);
        }

        return response()->view('errors.maintenance', [
            'maintenance' => $maintenance,
        ], 503);
    }
}
