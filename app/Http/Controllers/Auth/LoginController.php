<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
            // Regaining session security after verifying credentials to avoid fixation attacks
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'staff') {
                $onlineStaff = Cache::get('online_staff_users', []);
                $onlineStaff[$user->id] = now()->timestamp;
                Cache::forever('online_staff_users', $onlineStaff);
            }

            // Route user to their specific portal based on their permission level
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'staff') {
                $division = strtolower((string) $user->division);

                return match ($division) {
                    'washing' => redirect()->route('petugas_piket.washing.index'),
                    'ironing', 'setrika' => redirect()->route('petugas_piket.setrika.index'),
                    'packing' => redirect()->route('petugas_piket.packing.index'),
                    'customer_service' => redirect()->route('petugas.pos.index'),
                    'inventory' => redirect()->route('petugas_piket.inventory.index'),
                    default => redirect()->route('petugas_piket.dashboard'),
                };
            }

            // Default route for unidentified roles
            return redirect('/');
            }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'staff') {
            $onlineStaff = Cache::get('online_staff_users', []);
            unset($onlineStaff[$user->id]);
            Cache::forever('online_staff_users', $onlineStaff);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
