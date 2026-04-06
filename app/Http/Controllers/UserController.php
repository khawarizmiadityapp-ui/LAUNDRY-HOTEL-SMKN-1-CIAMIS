<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class UserController extends Controller
{
   public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        Transaction::create([
            'customer_name' => $request->customer_name,
            'service' => $request->service,
            'weight' => $request->weight,
            'status' => 'diterima',
            'paid' => false,
            'total' => $request->weight * 7000
        ]);

        return back();
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();
        return back();
    }

}
