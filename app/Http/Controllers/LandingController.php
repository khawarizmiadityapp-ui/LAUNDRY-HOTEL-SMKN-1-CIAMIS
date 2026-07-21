<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Layanan;
use App\Models\ServicePrice; // Untuk fallback harga
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Home / Landing Page
     */
    public function index()
    {
        $layanans = Layanan::aktif()->get();
        $heroImage = \App\Models\Setting::getValue('hero_image', 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?q=80&w=800&auto=format&fit=crop');
        $logoImage = \App\Models\Setting::getValue('logo_image', asset('images/logobening.jpeg'));
        return view('welcome', compact('layanans', 'heroImage', 'logoImage'));
    }

    /**
     * Public Order Tracking
     */
    public function trackStatus(Request $request)
    {
        $request->validate([
            'nota_number' => 'required|string|max:50',
        ]);

        $order = Transaksi::with(['tasks.petugas', 'details.layanan'])
            ->where('transaksi_code', $request->nota_number)
            ->first();

        if (!$order) {
            return back()->with('error', "Nomor nota/resi '{$request->nota_number}' tidak ditemukan.")
                ->withInput();
        }

        return view('pages.track-result', compact('order'));
    }
}
