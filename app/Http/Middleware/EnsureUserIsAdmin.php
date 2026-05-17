<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Check if user is admin
        if ($user->role !== 'admin') {
            // If user is staff, redirect to petugas dashboard
            if ($user->role === 'staff') {
                return redirect()->route('petugas_piket.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin. Dialihkan ke dashboard petugas.');
            }
            
            // For other roles, show forbidden
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }
        
        return $next($request);
    }
}
