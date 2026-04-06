<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function store(Request $request)
{
    $price = $request->service_type == 'express' ? 7000 : 5000;

    Transaksi::create([
        'transaction_code' => 'TRX-' . time(),
        'user_id' => 1, // sementara (nanti bisa pakai auth)
        'customer_name' => $request->customer_name,
        'customer_phone' => $request->customer_phone,
        'service_type' => $request->service_type,
        'weight' => $request->weight,
        'price_per_kg' => $price,
        'total_price' => $request->weight * $price,
        'status' => 'diterima',
        'payment_status' => 'belum_lunas',
        'notes' => $request->notes
    ]);

    return back();
}

}
