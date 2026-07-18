<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\LapanganController as UserLapanganController;
use App\Http\Controllers\User\PembayaranController as UserPembayaranController;
use App\Http\Controllers\User\QrisController;
use App\Http\Controllers\User\UlasanController;
use Illuminate\Support\Facades\Route;

// Redirect root sesuai status login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }

    return redirect()->route('login');
});

// ---------- Guest Routes (belum login) ----------
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// ---------- Authenticated Routes ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllRead'])->name('notifikasi.read-all');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('lapangan', LapanganController::class)->except(['show']);
        Route::resource('jadwal', JadwalController::class)->except(['show']);

        Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
        Route::post('/booking/{booking}/approve', [AdminBookingController::class, 'approve'])->name('booking.approve');
        Route::post('/booking/{booking}/reject', [AdminBookingController::class, 'reject'])->name('booking.reject');

        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::post('/pembayaran/{pembayaran}/verify', [AdminPembayaranController::class, 'verify'])->name('pembayaran.verify');
        Route::post('/pembayaran/{pembayaran}/reject', [AdminPembayaranController::class, 'reject'])->name('pembayaran.reject');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'exportCsv'])->name('laporan.export');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });

    // User only
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');

        Route::get('/lapangan', [UserLapanganController::class, 'index'])->name('lapangan.index');
        Route::get('/lapangan/{lapangan}', [UserLapanganController::class, 'show'])->name('lapangan.show');

        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/riwayat', [BookingController::class, 'index'])->name('booking.riwayat');
        Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

        Route::get('/booking/{booking}/pembayaran', [UserPembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/booking/{booking}/pembayaran', [UserPembayaranController::class, 'store'])->name('pembayaran.store');

        Route::post('/booking/{booking}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');

        Route::get('/booking/{booking}/qris', [QrisController::class, 'create'])->name('qris.create');
        Route::post('/booking/{booking}/qris', [QrisController::class, 'confirm'])->name('qris.confirm');
        Route::get('/booking/{booking}/qris/upload', [QrisController::class, 'uploadForm'])->name('qris.upload');
        Route::post('/booking/{booking}/qris/upload', [QrisController::class, 'uploadStore'])->name('qris.upload.store');
        Route::post('/booking/{booking}/bayar-di-tempat', [QrisController::class, 'bayarDiTempat'])->name('qris.bayar-di-tempat');
    });
});
