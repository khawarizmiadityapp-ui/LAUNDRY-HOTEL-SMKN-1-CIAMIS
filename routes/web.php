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
use App\Http\Controllers\OTPController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Exports\TransactionsExport;
use App\Http\Controllers\LaporanController;
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

// Password Reset Routes (Opsional)
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');
// OTP Routes
Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('send.otp');

Route::get('/otp', function () {return view('auth.otp');})->name('otp.form');

Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('verify.otp');

Route::get('/reset-password', function () {return view('auth.reset_password');})->name('reset.password');

Route::post('/update-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'password' => 'required|confirmed|min:6'
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();
    $user->password = bcrypt($request->password);
    $user->save();

    return redirect('/login')->with('success', 'Password berhasil diubah!');})->name('update.password');

// Middleware 'auth' memastikan hanya yang sudah login bisa akses
Route::group(['middleware' => ['auth']],function () {

    // Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/petugas', [PetugasController::class, 'dashboard'])->middleware(['auth'])->name('petugas.dashboard');

    // ================= POS (Pesanan Baru) =================
    Route::get('/admin/pos', [PosController::class, 'index'])->name('admin.pos.index');
    Route::get('/petugas/customer-service', [PosController::class, 'index'])->name('petugas.pos.index');
    Route::get('/pos/customer/search', [PosController::class, 'searchCustomer'])->name('pos.customer.search');
    Route::post('/pos/customer', [PosController::class, 'storeCustomer'])->name('pos.customer.store');
    Route::post('/pos/order', [PosController::class, 'store'])->name('pos.order.store');
    Route::get('/transaksi/{id}/nota', [PosController::class, 'nota'])->name('pos.nota');

    // Transaksi
    Route::get('/admin/transaksi', [AdminController::class, 'transactions'])->name('admin.transactions.index');
    Route::post('/admin/transaksi', [AdminController::class, 'storeTransaction'])->name('admin.transactions.store');
    Route::patch('/admin/transaksi/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.transactions.status');
    Route::patch('/admin/transaksi/{id}/payment', [AdminController::class, 'updatePayment'])->name('admin.transactions.payment');
    Route::delete('/admin/transaksi/{id}', [AdminController::class, 'destroyTransaction'])
    ->name('admin.transactions.destroy');

    // Customer
    Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/admin/customers/create', [CustomerController::class, 'create'])->name('admin.customers.create');
    Route::post('/admin/customers', [CustomerController::class, 'store'])->name('admin.customers.store');
    Route::get('/admin/customers/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
    Route::patch('/admin/customers/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
    Route::delete('/admin/customers/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

    // Layanan
    Route::prefix('admin')->name('admin.')->group(function () {

    // Layanan resource routes
    Route::resource('layanan', LayananController::class)->except(['show', 'create', 'edit']);

    // AJAX toggle status
    Route::patch('layanan/{layanan}/toggle-status', [LayananController::class, 'toggleStatus'])
        ->name('layanan.toggle-status');
    });

    //petugas
    Route::get('/admin/petugas', [PetugasController::class, 'index'])->name('admin.petugas.index');

    // Laporan
    Route::get('/admin/laporan_keuangan', [LaporanController::class, 'index'])->name('admin.laporan_keuangan.index');

    // Export Laporan
    Route::get('/export-transaksi', [TransaksiController::class, 'exportExcel'])->name('export.transaksi.excel');
    Route::get('/export-transaksi-pdf', [TransaksiController::class, 'exportPdf'])->name('export.transaksi.pdf');

    // Harga
    Route::get('/admin/prices', [AdminController::class, 'prices'])->name('admin.prices');
    Route::post('/admin/prices', [AdminController::class, 'updatePrices'])->name('admin.prices.update');

    // Users
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');

    //pembayaran
    Route::get('/admin/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::get('/admin/pembayaran/create', [PembayaranController::class, 'create'])->name('admin.pembayaran.create');

    // Pengeluaran
    Route::prefix('admin/pengeluaran')->name('admin.pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{pengeluaran}', [PengeluaranController::class, 'show'])->name('show');
        Route::get('/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{pengeluaran}', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [PengeluaranController::class, 'export'])->name('export');
    });

    // Inventory
    Route::prefix('admin/inventory')->name('admin.inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::post('/{id}/update', [InventoryController::class, 'updateQty'])->name('update');
        Route::post('/request/{id}/approve', [InventoryController::class, 'approveAdjustment'])->name('request.approve');
        Route::post('/request/{id}/reject', [InventoryController::class, 'rejectAdjustment'])->name('request.reject');
    });

// ================= PETUGAS =================
Route::prefix('petugas')->name('petugas_piket.')->middleware(['auth'])->group(function () {

    Route::get('/', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/washing', [PetugasController::class, 'washing'])->name('washing.index');
    Route::get('/setrika', [PetugasController::class, 'setrika'])->name('setrika.index');
    Route::get('/packing', [PetugasController::class, 'packing'])->name('packing.index');

    Route::post('/tasks/{id}/status', [PetugasController::class, 'updateTaskStatus'])->name('tasks.updateStatus');
    Route::post('/tasks/{id}/complete', [PetugasController::class, 'completeTask'])->name('tasks.complete');
    Route::get('/inventory', [PetugasController::class, 'inventory'])->name('inventory.index');
    Route::post('/inventory/{id}/adjust', [PetugasController::class, 'adjustInventory'])->name('inventory.adjust');
    Route::get('/history', [PetugasController::class, 'history'])->name('history.index');
});
});
