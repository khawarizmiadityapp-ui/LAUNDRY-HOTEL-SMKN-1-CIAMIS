<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999);

        // simpan OTP ke cache (5 menit)
        Cache::put('otp_'.$request->email, $otp, now()->addMinutes(5));

        // kirim email
        Mail::to($request->email)->send(new SendOTP($otp));

        return back()->with('success', 'OTP berhasil dikirim!');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $savedOtp = Cache::get('otp_'.$request->email);

        if ($savedOtp == $request->otp) {
            return redirect()->route('reset.password', ['email' => $request->email]);
        }

        return back()->with('error', 'OTP salah atau expired!');
    }
}