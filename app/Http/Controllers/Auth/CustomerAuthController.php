<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomerAuthController extends Controller
{
    // Tampilkan View Login
    public function loginView()
    {
        if(!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        return view('auth.customer-login');
    }

    // Tampilkan View Register
    public function registerView()
    {
        if(!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        return view('auth.customer-register');
    }

    // Proses Register Customer
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        // Jika ada attempt order yang nyangkut, kembali ke booking
        if (session()->has('booking_draft') || session()->has('booking_data')) {
            return redirect()->route('order.booking');
        }

        return redirect()->route('home')->with('success', 'Berhasil mendaftar dan masuk!');
    }

    // Proses Login Customer
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Periksa apabila user ini login saat pemesanan
            return redirect()->intended(route('home'))->with('success', 'Anda telah masuk.');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah keluar akun.');
    }
}
