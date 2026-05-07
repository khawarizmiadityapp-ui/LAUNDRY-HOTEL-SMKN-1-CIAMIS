<?php

return [
    'brand' => [
        'name' => 'Bening Laundry',
        'tagline' => 'Management Portal',
    ],

    'menus' => [
        [
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => ['admin.dashboard'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'label' => 'Pesanan Baru',
            'route' => 'admin.pos.index',
            'active' => ['admin.pos.*'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            'label' => 'Transaksi',
            'route' => 'admin.transactions.index',
            'active' => ['admin.transactions.*'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            'label' => 'Customer',
            'route' => 'admin.customers.index',
            'active' => ['admin.customers.*'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'label' => 'Petugas',
            'route' => 'admin.petugas.index',
            'active' => ['admin.petugas.*'],
            'badge' => 'sidebarOnDutyCount',
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
            'label' => 'Pembayaran',
            'route' => 'admin.pembayaran.index',
            'active' => ['admin.pembayaran.*'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            'label' => 'Laporan Keuangan',
            'route' => 'admin.laporan_keuangan.index',
            'active' => ['admin.laporan_keuangan.*'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
            'label' => 'Pengeluaran',
            'route' => 'admin.pengeluaran.index',
            'active' => ['admin.pengeluaran.*'],
            'roles' => ['admin'],
        ],
        [
            'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125',
            'label' => 'Inventory',
            'route' => 'admin.inventory.index',
            'active' => ['admin.inventory.*'],
            'roles' => ['admin'],
        ],
    ],
];
