<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreTransaksiRequest;

class TransaksiController extends Controller
{
    public function store(StoreTransaksiRequest $request)
{
    // Validation already handled by FormRequest
    $validated = $request->validated();

    $price = $validated['service_type'] == 'express' ? 7000 : 5000;
    $totalPrice = $validated['weight'] * $price;

    $monthlyIncomeLimit = (int) env('MONTHLY_INCOME_LIMIT', 50000000);
    $currentMonthIncome = Transaksi::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_price');

    if (($currentMonthIncome + $totalPrice) > $monthlyIncomeLimit) {
        return back()->withErrors([
            'weight' => 'Transaksi melebihi batas pemasukan bulanan. Sisa kuota: Rp ' . number_format(max(0, $monthlyIncomeLimit - $currentMonthIncome), 0, ',', '.'),
        ])->withInput();
    }

    Transaksi::create([
        'transaksi_code' => 'TRX-' . time(),
        'user_id' => auth()->id(),
        'customer_name' => $validated['customer_name'],
        'customer_phone' => $validated['customer_phone'],
        'service_type' => $validated['service_type'],
        'weight' => $validated['weight'],
        'price_per_kg' => $price,
        'total_price' => $totalPrice,
        'status' => 'diterima',
        'payment_status' => 'belum_lunas',
        'notes' => $validated['notes'] ?? null
    ]);

    return back();
}

    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'laporan-keuangan.xlsx');
    }

    public function exportPdf()
    {
    $data = Transaksi::with(['user', 'customer', 'details.layanan'])->get();

    $pdf = Pdf::loadView('admin.pdf.transaksi', compact('data'))->setPaper('a4', 'landscape');

    return $pdf->download('laporan-keuangan.pdf');
    }

}
