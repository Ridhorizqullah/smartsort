<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Semua route di sini dilindungi middleware auth + role:admin,petugas.
| Registrasi middleware dilakukan di bootstrap/app.php.
*/

Route::middleware(['auth', 'role:admin,petugas'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // POS Transaksi Sampah
        Route::get('/transaksi', [AdminController::class, 'transaksiForm'])->name('transaksi');
        Route::post('/transaksi', [AdminController::class, 'transaksiStore'])->name('transaksi.store');

        // Penukaran Barang (Redemption)
        Route::get('/penukaran/export', [AdminController::class, 'exportRedemption'])->name('penukaran.export');
        Route::get('/penukaran', [AdminController::class, 'redemptionList'])->name('penukaran');
        Route::post('/penukaran/{id}/approve', [AdminController::class, 'approveRedemption'])->name('penukaran.approve');
        Route::post('/penukaran/{id}/reject', [AdminController::class, 'rejectRedemption'])->name('penukaran.reject');
        Route::post('/penukaran/{id}/ready', [AdminController::class, 'markRedemptionReady'])->name('penukaran.ready');
        Route::post('/penukaran/{id}/complete', [AdminController::class, 'markRedemptionCompleted'])->name('penukaran.complete');

        // Master Data (Hanya Admin)
        Route::middleware(['role:admin'])->group(function () {
            // Master Data: Users
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
            Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
            Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
            Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
            Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

            // Master Data: Kategori
            Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
            Route::get('/kategori/create', [AdminController::class, 'kategoriCreate'])->name('kategori.create');
            Route::post('/kategori', [AdminController::class, 'kategoriStore'])->name('kategori.store');
            Route::get('/kategori/{id}/edit', [AdminController::class, 'kategoriEdit'])->name('kategori.edit');
            Route::put('/kategori/{id}', [AdminController::class, 'kategoriUpdate'])->name('kategori.update');
            Route::delete('/kategori/{id}', [AdminController::class, 'kategoriDestroy'])->name('kategori.destroy');

            // Master Data: Reward
            Route::get('/reward', [AdminController::class, 'reward'])->name('reward');
            Route::get('/reward/create', [AdminController::class, 'rewardCreate'])->name('reward.create');
            Route::post('/reward', [AdminController::class, 'rewardStore'])->name('reward.store');
            Route::get('/reward/{id}/edit', [AdminController::class, 'rewardEdit'])->name('reward.edit');
            Route::put('/reward/{id}', [AdminController::class, 'rewardUpdate'])->name('reward.update');
            Route::delete('/reward/{id}', [AdminController::class, 'rewardDestroy'])->name('reward.destroy');
        });
    });
