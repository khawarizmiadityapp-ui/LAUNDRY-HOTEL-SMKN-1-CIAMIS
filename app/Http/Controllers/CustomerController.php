<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display customer listing.
     */
    public function index(Request $request)
    {
        $customers = Customer::withCount('transaksis as total_order')
            ->withMax('transaksis as terakhir_transaksi', 'created_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $stats = [
            'total_customer'  => Customer::count(),
            'aktif_bulan_ini' => Customer::whereHas('transaksis', function($q) {
                $q->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
            })->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Show form to create a customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a new customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'email'  => 'nullable|email|unique:customers,email',
            'no_hp'  => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Show edit form.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update a customer.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'email'  => 'nullable|email|unique:customers,email,' . $id,
            'no_hp'  => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        Customer::findOrFail($id)->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    /**
     * Delete a customer.
     */
    public function destroy(string $id)
    {
        Customer::findOrFail($id)->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}