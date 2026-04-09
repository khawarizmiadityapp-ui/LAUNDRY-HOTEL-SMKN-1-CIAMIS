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

        $order = Transaksi::where('transaksi_code', $request->nota_number)->first();

        // Beberapa notasi lama mungkin menggunakan huruf kecil, atau jika database schema berbeda
        if (!$order) {
            return back()->with('error', "Nomor nota/resi '{$request->nota_number}' tidak ditemukan.");
        }

        return view('pages.track-result', compact('order'));
    }

    /**
     * Public Order Booking Page
     */
    public function booking()
    {
        $layanans = Layanan::aktif()->get();
        return view('pages.booking', compact('layanans'));
    }

    /**
     * Store Public Booking
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'nullable|string|max:255',
            'layanan_id'    => 'required|exists:layanans,id',
            'delivery_type' => 'required|in:regular,express',
            'weight_estimate' => 'nullable|numeric|min:0.5',
            'payment_method'  => 'required|in:cash,dana,qris',
            'note'          => 'nullable|string|max:500',
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);

        // Generate Kode Transaksi Unik sesuai format Admin
        $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        
        $weight = $validated['weight_estimate'] ?? 0;
        
        // Cek harga dari tbl ServicePrice untuk kompatibilitas Admin
        // AdminController.php uses ServicePrice where 'service_type' = 'regular' or 'express'
        $price = ServicePrice::where('service_type', $validated['delivery_type'])->first();
        $pricePerKg = $price ? $price->price_per_kg : $layanan->harga; 
        
        $totalPrice = $weight * $pricePerKg;

        $order = Transaksi::create([
            'transaksi_code' => $transactionCode,
            'user_id'        => auth()->id(), // Terhubung_dengan akun Google
            'customer_name'  => $validated['customer_name'],
            'customer_phone' => $validated['phone'],
            'service_type'   => $validated['delivery_type'], // Disimpan sebagai 'regular' / 'express'
            // Kita bisa taruh nama layanan ($layanan->nama) di notes / kolom lain jika perlu
            'weight'         => $weight,
            'price_per_kg'   => $pricePerKg,
            'total_price'    => $totalPrice,
            'status'         => 'diterima',
            'payment_status' => 'belum_bayar',
            'payment_method' => $validated['payment_method'],
            'notes'          => "Layanan: " . $layanan->nama . "\nAlamat: " . $validated['address'] . "\nCatatan: " . $validated['note'],
        ]);

        return redirect()
            ->route('home')
            ->with('success', "Pesanan Anda berhasil dikirim! Silakan tunggu konfirmasi admin kami atau lacak nomer: {$transactionCode}");
    }
}
