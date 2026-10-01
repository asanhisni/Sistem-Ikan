<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\IkanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Authentication ───────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Protected Routes (perlu login) ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('transaksi', TransaksiController::class);
    Route::post('/transaksi/{transaksi}/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
    Route::delete('/pembayaran/{pembayaran}', [TransaksiController::class, 'hapusPembayaran'])->name('pembayaran.destroy');
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('ikan', IkanController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::resource('users', UserController::class);
});
