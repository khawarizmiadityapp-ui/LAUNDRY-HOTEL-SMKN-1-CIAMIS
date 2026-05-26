<?php

return [
    'brand' => [
        'name' => 'Bening Laundry',
        'tagline' => 'Management Portal',
    ],

    // Division aliases untuk normalisasi
    'division_aliases' => [
        'kasir' => 'customer_service',
        'customer service' => 'customer_service',
        'cs' => 'customer_service',
        'ironing' => 'setrika',
    ],

    // Division labels untuk display
    'division_labels' => [
        'washing' => 'Washing',
        'setrika' => 'Setrika',
        'packing' => 'Packing',
        'customer_service' => 'Customer Service',
        'inventory' => 'Inventory',
    ],

    // Admin menus
    'admin_menus' => [
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

    // Petugas/Staff menus
    'petugas_menus' => [
        [
            'label' => 'Dashboard',
            'route' => 'petugas_piket.dashboard',
            'active' => ['petugas_piket.dashboard'],
            'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
            'divisions' => ['washing', 'setrika', 'packing', 'customer_service', 'inventory'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'Customer Service',
            'route' => 'petugas.pos.index',
            'active' => ['petugas.pos.*'],
            'icon' => 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'divisions' => ['customer_service'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'Transaksi',
            'route' => 'petugas_piket.transaksi.index',
            'active' => ['petugas_piket.transaksi.*'],
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            'divisions' => ['customer_service'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'Washing',
            'route' => 'petugas_piket.washing.index',
            'active' => ['petugas_piket.washing.*'],
            'icon' => 'M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48zm0 5.784a3.75 3.75 0 00-.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0115.362 10.998z',
            'divisions' => ['washing'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'Setrika',
            'route' => 'petugas_piket.setrika.index',
            'active' => ['petugas_piket.setrika.*'],
            'icon' => 'M7 3.25H17A4.25 4.25 0 0121.25 7.5v8a4.25 4.25 0 01-4.25 4.25H7A4.25 4.25 0 012.75 15.5v-8A4.25 4.25 0 017 3.25zM10 11.25h4',
            'divisions' => ['setrika'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'Packing',
            'route' => 'petugas_piket.packing.index',
            'active' => ['petugas_piket.packing.*'],
            'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
            'divisions' => ['packing'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'Inventory',
            'route' => 'petugas_piket.inventory.index',
            'active' => ['petugas_piket.inventory.*'],
            'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125',
            'divisions' => ['inventory'],
            'roles' => ['admin', 'staff'],
        ],
        [
            'label' => 'History',
            'route' => 'petugas_piket.history.index',
            'active' => ['petugas_piket.history.*'],
            'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'divisions' => ['washing', 'setrika', 'packing', 'customer_service', 'inventory'],
            'roles' => ['admin', 'staff'],
        ],
    ],

    // Legacy support - keep for backward compatibility
    'menus' => [],
];
