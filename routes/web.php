<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\IkanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

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

// ─── Lupa & Reset Password (No HP / PIN Pemulihan) ────────────────────────────
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password/verify', [AuthController::class, 'verifyIdentity'])->name('password.verify');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// ─── Protected Routes (perlu login) ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Detail & Update Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    Route::resource('transaksi', TransaksiController::class);
    Route::post('/transaksi/{transaksi}/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
    Route::delete('/pembayaran/{pembayaran}', [TransaksiController::class, 'hapusPembayaran'])->name('pembayaran.destroy');
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('ikan', IkanController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::resource('users', UserController::class);
    Route::post('/cabang', [\App\Http\Controllers\CabangController::class, 'store'])->name('cabang.store');
    Route::delete('/cabang/{cabang}', [\App\Http\Controllers\CabangController::class, 'destroy'])->name('cabang.destroy');

    // Beralih Cepat ke Kepala Cabang Suci & Kembali
    Route::post('/switch-cabang-suci', [AuthController::class, 'switchCabangSuci'])->name('auth.switch.suci');
    Route::post('/switch-back-admin', [AuthController::class, 'switchBackAdmin'])->name('auth.switch.back');
});
