<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ServicePriceController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PengeluaranController;

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Exports\TransactionsExport;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ErrorLogController;
use App\Models\User;
use App\Models\Transaction;
use App\Models\ServicePrice;
use App\Models\Layanan;
use App\Models\Pengeluaran;
use App\Http\Controllers\LandingController;

// Public UI Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/track', [LandingController::class, 'trackStatus'])->name('track.status');

// Public Routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Middleware 'auth' memastikan hanya yang sudah login bisa akses
Route::group(['middleware' => ['auth']], function () {

    // ================= ADMIN ROUTES (Protected) =================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/update-target', [AdminController::class, 'updateTarget'])->name('update_target');
        
        // POS (Pesanan Baru)
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        
        // Transaksi Management
        Route::prefix('transaksi')->name('transactions.')->group(function () {
            Route::get('/', [AdminController::class, 'transactions'])->name('index');
            Route::post('/', [AdminController::class, 'storeTransaction'])->middleware('throttle:30,1')->name('store');
            Route::put('/{id}', [AdminController::class, 'updateTransaction'])->middleware('throttle:30,1')->name('update');
            Route::patch('/{id}/status', [AdminController::class, 'updateStatus'])->middleware('throttle:60,1')->name('status');
            Route::patch('/{id}/payment', [AdminController::class, 'updatePayment'])->middleware('throttle:60,1')->name('payment');
            Route::delete('/{id}', [AdminController::class, 'destroyTransaction'])->middleware('throttle:20,1')->name('destroy');
        });
        
        // Customer Management
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/create', [CustomerController::class, 'create'])->name('create');
            Route::post('/', [CustomerController::class, 'store'])->middleware('throttle:30,1')->name('store');
            Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::patch('/{id}', [CustomerController::class, 'update'])->middleware('throttle:30,1')->name('update');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->middleware('throttle:20,1')->name('destroy');
        });
        
        // Layanan Management
        Route::resource('layanan', LayananController::class)->except(['show', 'create', 'edit'])->middleware('throttle:30,1');
        Route::patch('layanan/{layanan}/toggle-status', [LayananController::class, 'toggleStatus'])->middleware('throttle:60,1')->name('layanan.toggle-status');
        
        // Petugas Management
        Route::prefix('petugas')->name('petugas.')->group(function () {
            Route::get('/', [PetugasController::class, 'index'])->name('index');
            Route::post('/', [PetugasController::class, 'store'])->middleware('throttle:30,1')->name('store');
            Route::put('/{id}', [PetugasController::class, 'update'])->middleware('throttle:30,1')->name('update');
            Route::delete('/{id}', [PetugasController::class, 'destroy'])->middleware('throttle:20,1')->name('destroy');
        });
        
        // Laporan Keuangan
        Route::get('/laporan_keuangan', [LaporanController::class, 'index'])->name('laporan_keuangan.index');
        
        // Prices Management
        Route::get('/prices', [AdminController::class, 'prices'])->name('prices');
        Route::post('/prices', [AdminController::class, 'updatePrices'])->middleware('throttle:20,1')->name('prices.update');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->middleware('throttle:20,1')->name('users.store');
        
        // Activity Log
        Route::prefix('activity')->name('activity.')->group(function () {
            Route::get('/', [App\Http\Controllers\ActivityController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('show');
        });
        
        // Pembayaran
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [PembayaranController::class, 'index'])->name('index');
            Route::get('/create', [PembayaranController::class, 'create'])->name('create');
            Route::post('/', [PembayaranController::class, 'store'])->middleware('throttle:30,1')->name('store');
        });
        
        // Pengeluaran
        Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
            Route::get('/', [PengeluaranController::class, 'index'])->name('index');
            Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
            Route::post('/', [PengeluaranController::class, 'store'])->middleware('throttle:30,1')->name('store');
            Route::get('/export/csv', [PengeluaranController::class, 'export'])->name('export');
            Route::get('/{pengeluaran}', [PengeluaranController::class, 'show'])->name('show');
            Route::get('/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('edit');
            Route::put('/{pengeluaran}', [PengeluaranController::class, 'update'])->middleware('throttle:30,1')->name('update');
            Route::delete('/{pengeluaran}', [PengeluaranController::class, 'destroy'])->middleware('throttle:20,1')->name('destroy');
        });
        
        // Inventory
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::post('/', [InventoryController::class, 'store'])->name('store');
            Route::post('/{id}/update', [InventoryController::class, 'updateQty'])->middleware('throttle:30,1')->name('update');
            Route::delete('/{id}', [InventoryController::class, 'destroy'])->middleware('throttle:20,1')->name('destroy');
            Route::post('/request/{id}/approve', [InventoryController::class, 'approveAdjustment'])->middleware('throttle:30,1')->name('request.approve');
            Route::post('/request/{id}/reject', [InventoryController::class, 'rejectAdjustment'])->middleware('throttle:30,1')->name('request.reject');
        });

        // Error Logs
        Route::prefix('errors')->name('errors.')->group(function () {
            Route::get('/', [ErrorLogController::class, 'index'])->name('index');
            Route::get('/{errorLog}', [ErrorLogController::class, 'show'])->name('show');
            Route::post('/{errorLog}/resolve', [ErrorLogController::class, 'resolve'])->name('resolve');
            Route::delete('/{errorLog}', [ErrorLogController::class, 'destroy'])->name('destroy');
            Route::post('/clear-old', [ErrorLogController::class, 'clearOld'])->name('clear-old');
        });
    });

    // ================= SHARED ROUTES (Admin + Staff) =================
    
    // POS Routes (Accessible by Admin + CS Staff)
    Route::middleware(['throttle:100,1'])->group(function () {
        Route::get('/petugas/customer-service', [PosController::class, 'index'])->name('petugas.pos.index');
        Route::get('/pos/customer/search', [PosController::class, 'searchCustomer'])->name('pos.customer.search');
        Route::post('/pos/customer', [PosController::class, 'storeCustomer'])->middleware('throttle:60,1')->name('pos.customer.store');
        Route::post('/pos/order', [PosController::class, 'store'])->middleware('throttle:60,1')->name('pos.order.store');
        Route::get('/transaksi/{id}/nota', [PosController::class, 'nota'])->name('pos.nota');
        Route::post('/transaksi/{id}/pickup', [PosController::class, 'pickup'])->middleware('throttle:60,1')->name('pos.pickup');
    });
    
    // Export Routes (Admin + Staff with permission)
    Route::middleware(['throttle:10,1'])->group(function () {
        Route::get('/export-transaksi', [TransaksiController::class, 'exportExcel'])->name('export.transaksi.excel');
        Route::get('/export-transaksi-pdf', [TransaksiController::class, 'exportPdf'])->name('export.transaksi.pdf');
    });

    // ================= PETUGAS/STAFF ROUTES =================
    Route::prefix('petugas')->name('petugas_piket.')->middleware(['throttle:100,1'])->group(function () {
        Route::get('/', [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::get('/washing', [PetugasController::class, 'washing'])->name('washing.index');
        Route::get('/setrika', [PetugasController::class, 'setrika'])->name('setrika.index');
        Route::get('/packing', [PetugasController::class, 'packing'])->name('packing.index');
        
        Route::post('/tasks/{id}/status', [PetugasController::class, 'updateTaskStatus'])->middleware('throttle:60,1')->name('tasks.updateStatus');
        Route::post('/tasks/{id}/complete', [PetugasController::class, 'completeTask'])->middleware('throttle:60,1')->name('tasks.complete');
        
        Route::get('/inventory', [PetugasController::class, 'inventory'])->name('inventory.index');
        Route::post('/inventory', [PetugasController::class, 'storeInventory'])->middleware('throttle:30,1')->name('inventory.store');
        Route::post('/inventory/{id}/adjust', [PetugasController::class, 'adjustInventory'])->middleware('throttle:30,1')->name('inventory.adjust');
        
        Route::get('/history', [PetugasController::class, 'history'])->name('history.index');
        Route::get('/transaksi', [PetugasController::class, 'transactions'])->name('transaksi.index');
    });
});
