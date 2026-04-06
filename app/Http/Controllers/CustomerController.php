<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomerController extends Controller
{
    /**
     * Dummy customer data — replace with Eloquent queries in production.
     */
    private function dummyCustomers(): Collection
    {
        return collect([
            [
                'id'                => 'CST-2491',
                'nama'              => 'Ahmad Sofyan',
                'email'             => 'ahmad.s@email.com',
                'telepon'           => '0812-3456-7890',
                'terakhir_transaksi'=> '2023-10-14 14:20:00',
                'total_order'       => 24,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2492',
                'nama'              => 'Budi Kusuma',
                'email'             => 'budi_k@email.com',
                'telepon'           => '0821-9876-5432',
                'terakhir_transaksi'=> '2023-10-12 09:15:00',
                'total_order'       => 12,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2493',
                'nama'              => 'Citra Dewi',
                'email'             => 'citra.dewi@email.com',
                'telepon'           => '0856-1234-5678',
                'terakhir_transaksi'=> '2023-09-25 16:45:00',
                'total_order'       => 3,
                'status'            => 'nonaktif',
            ],
            [
                'id'                => 'CST-2494',
                'nama'              => 'Deni Setiawan',
                'email'             => 'denis_88@email.com',
                'telepon'           => '0819-2233-4455',
                'terakhir_transaksi'=> '2023-10-13 11:10:00',
                'total_order'       => 45,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2495',
                'nama'              => 'Eka Rahmawati',
                'email'             => 'eka.r@email.com',
                'telepon'           => '0877-6655-4433',
                'terakhir_transaksi'=> '2023-10-10 08:30:00',
                'total_order'       => 8,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2496',
                'nama'              => 'Fajar Nugroho',
                'email'             => 'fajar.n@email.com',
                'telepon'           => '0831-1122-3344',
                'terakhir_transaksi'=> '2023-10-08 13:00:00',
                'total_order'       => 19,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2497',
                'nama'              => 'Gita Pertiwi',
                'email'             => 'gita.p@email.com',
                'telepon'           => '0852-9988-7766',
                'terakhir_transaksi'=> '2023-09-20 10:50:00',
                'total_order'       => 1,
                'status'            => 'nonaktif',
            ],
            [
                'id'                => 'CST-2498',
                'nama'              => 'Hendra Wijaya',
                'email'             => 'hendra.w@email.com',
                'telepon'           => '0813-5544-6677',
                'terakhir_transaksi'=> '2023-10-11 15:20:00',
                'total_order'       => 31,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2499',
                'nama'              => 'Indah Sari',
                'email'             => 'indah.s@email.com',
                'telepon'           => '0896-3322-1100',
                'terakhir_transaksi'=> '2023-10-05 09:45:00',
                'total_order'       => 7,
                'status'            => 'aktif',
            ],
            [
                'id'                => 'CST-2500',
                'nama'              => 'Joko Santoso',
                'email'             => 'joko.s@email.com',
                'telepon'           => '0822-4433-5566',
                'terakhir_transaksi'=> '2023-09-18 14:00:00',
                'total_order'       => 2,
                'status'            => 'nonaktif',
            ],
        ]);
    }

    /**
     * Display customer listing.
     */
    public function index(Request $request)
    {
        $customers = $this->dummyCustomers();

        // Paginate manually (replace with Eloquent paginate() in production)
        $perPage     = 4;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedItems  = $customers->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $pagedItems,
            1240,            // total — replace with real count
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $stats = [
            'total_customer'     => 1240,
            'aktif_bulan_ini'    => 342,
            'metode_favorit'     => 'Kiloan Express',
            'metode_favorit_pct' => 65,
        ];

        return view('admin.customers.index', compact('customers', 'stats'))
            ->with('customers', $paginator);
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
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email|unique:customers,email',
            'telepon' => 'required|string|max:20',
        ]);

        // Customer::create($validated); // uncomment for Eloquent

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Show edit form.
     */
    public function edit(string $id)
    {
        // $customer = Customer::findOrFail($id);
        $customer = $this->dummyCustomers()->firstWhere('id', $id);

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update a customer.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email',
            'telepon' => 'required|string|max:20',
        ]);

        // Customer::findOrFail($id)->update($validated); // uncomment for Eloquent

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    /**
     * Delete a customer.
     */
    public function destroy(string $id)
    {
        // Customer::findOrFail($id)->delete(); // uncomment for Eloquent

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}