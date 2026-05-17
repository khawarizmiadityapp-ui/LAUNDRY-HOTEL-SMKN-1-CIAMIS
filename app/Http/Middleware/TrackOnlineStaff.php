<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackOnlineStaff
{
    /**
     * Handle an incoming request.
     *
     * Track staff users as online by updating cache with their last seen timestamp.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request first
        $response = $next($request);
        
        // Then track the user (after authentication)
        $user = Auth::user();
        
        // Only track staff users
        if ($user && $user->role === 'staff') {
            try {
                // Get current online staff from cache
                $onlineStaff = Cache::get('online_staff_users', []);
                
                // Update this user's last seen timestamp
                $onlineStaff[$user->id] = now()->timestamp;
                
                // Save back to cache (forever, will be cleaned up by AppServiceProvider)
                Cache::forever('online_staff_users', $onlineStaff);
            } catch (\Exception $e) {
                // Silently fail - don't break the request
                \Log::error('TrackOnlineStaff failed: ' . $e->getMessage());
            }
        }
        
        return $response;
    }
}
