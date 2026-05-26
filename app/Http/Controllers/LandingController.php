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
        return view('welcome', compact('layanans'));
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
