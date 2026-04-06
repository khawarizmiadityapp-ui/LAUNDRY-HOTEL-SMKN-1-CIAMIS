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
use App\Models\User;
use App\Models\Transaction;
use App\Models\ServicePrice;
use App\Models\Petugas;
use App\models\Layanan;
use App\Models\Pengeluaran;


Route::get('/', function () {
    return view('welcome');
});
// Public Routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes (Opsional)
// Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');

// Middleware 'auth' memastikan hanya yang sudah login bisa akses
Route::group([],function () {
    
    // Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
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
    Route::get('/admin/laporan_keuangan', function () {return view('admin.laporan_keuangan.index');})->name('admin.laporan_keuangan.index');
    
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
});