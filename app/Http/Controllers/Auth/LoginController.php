<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Google2FAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        // Khusus Super Admin & Admin: Wajib 2FA Google Authenticator
        if ($user->isAdmin()) {
            // Pastikan admin memiliki secret key standar (16 karakter Base32)
            if (empty($user->google2fa_secret) || strlen($user->google2fa_secret) > 16) {
                $user->google2fa_secret = Google2FAService::generateSecretKey(10);
                $user->save();
            }

            // Simpan pending auth di session dengan batas waktu 2 menit
            $request->session()->put('2fa_admin_id', $user->id);
            $request->session()->put('2fa_expires_at', now()->addMinutes(2)->timestamp);

            return redirect()->route('login.2fa');
        }

        // Staff / Role Lain: Login langsung tanpa 2FA
        Auth::login($user, (bool) $request->remember);
        $request->session()->regenerate();

        if ($user->isStaff()) {
            $onlineStaff = Cache::get('online_staff_users', []);
            $onlineStaff[$user->id] = now()->timestamp;
            Cache::forever('online_staff_users', $onlineStaff);
        }

        return $this->redirectBasedOnRole($user);
    }

    public function show2fa(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $adminId = $request->session()->get('2fa_admin_id');
        $expiresAt = $request->session()->get('2fa_expires_at');

        if (!$adminId || !$expiresAt || $expiresAt < time()) {
            $request->session()->forget(['2fa_admin_id', '2fa_expires_at']);
            return redirect()->route('login')->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan login kembali.');
        }

        $admin = User::find($adminId);
        if (!$admin || !$admin->isAdmin()) {
            $request->session()->forget(['2fa_admin_id', '2fa_expires_at']);
            return redirect()->route('login');
        }

        if (empty($admin->google2fa_secret)) {
            $admin->google2fa_secret = Google2FAService::generateSecretKey();
            $admin->save();
        }

        $company = 'Bening Laundry';
        $qrCodeUrl = Google2FAService::getQrCodeImageUrl($company, $admin->email, $admin->google2fa_secret);
        $formattedSecret = Google2FAService::formatSecretKey($admin->google2fa_secret);
        $rawSecret = $admin->google2fa_secret;
        $expiresIn = max(0, $expiresAt - time());

        return view('auth.2fa', compact('admin', 'qrCodeUrl', 'formattedSecret', 'rawSecret', 'expiresIn'));
    }

    public function verify2fa(Request $request)
    {
        $adminId = $request->session()->get('2fa_admin_id');
        $expiresAt = $request->session()->get('2fa_expires_at');

        if (!$adminId || !$expiresAt || $expiresAt < time()) {
            $request->session()->forget(['2fa_admin_id', '2fa_expires_at']);
            return redirect()->route('login')->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan login kembali.');
        }

        $admin = User::find($adminId);
        if (!$admin || !$admin->isAdmin()) {
            $request->session()->forget(['2fa_admin_id', '2fa_expires_at']);
            return redirect()->route('login');
        }

        // Ambil kode OTP (bisa dari field 'code', 'otp', atau array 'digits')
        $code = $request->input('code') ?? $request->input('otp');
        if (is_array($request->input('digits'))) {
            $code = implode('', $request->input('digits'));
        }

        $code = preg_replace('/\s+/', '', (string) $code);

        if (empty($code) || strlen($code) !== 6 || !ctype_digit($code)) {
            return back()->with('error', 'Masukkan 6 digit kode verifikasi numerik yang valid.');
        }

        if (!Google2FAService::verifyKey($admin->google2fa_secret, $code)) {
            return back()->with('error', 'Kode verifikasi Google Authenticator salah atau telah kedaluwarsa. Silakan coba lagi.');
        }

        // Verifikasi berhasil: selesaikan proses login
        $request->session()->forget(['2fa_admin_id', '2fa_expires_at']);
        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Verifikasi 2FA berhasil. Selamat datang kembali!');
    }

    public function cancel2fa(Request $request)
    {
        $request->session()->forget(['2fa_admin_id', '2fa_expires_at']);
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->isStaff()) {
            $onlineStaff = Cache::get('online_staff_users', []);
            unset($onlineStaff[$user->id]);
            Cache::forever('online_staff_users', $onlineStaff);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStaff()) {
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

        return redirect('/');
    }
}

