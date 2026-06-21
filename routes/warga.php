<?php

use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Warga Routes
|--------------------------------------------------------------------------
| Semua route di sini dilindungi middleware auth + role:warga.
| Registrasi middleware dilakukan di bootstrap/app.php.
*/

Route::middleware(['auth', 'role:warga'])
    ->prefix('warga')
    ->name('warga.')
    ->group(function () {

        // Endpoint GET utama: Dashboard, Transaksi, Katalog, Penukaran (60 requests / menit)
        Route::middleware('throttle:60,1')->group(function () {
            Route::get('/dashboard', [WargaController::class, 'dashboard'])->name('dashboard');
            Route::get('/transaksi', [WargaController::class, 'transaksi'])->name('transaksi');
            Route::get('/katalog', [WargaController::class, 'katalog'])->name('katalog');
            Route::get('/penukaran', [WargaController::class, 'redemption'])->name('redemption');
        });

        // Endpoint polling realtime API (30 requests / menit)
        Route::get('/api/status', [WargaController::class, 'apiStatus'])
            ->name('api.status')
            ->middleware('throttle:30,1');

        // Endpoint aksi krusial penukaran sembako (10 requests / menit)
        Route::post('/penukaran', [WargaController::class, 'storeRedemption'])
            ->name('redemption.store')
            ->middleware('throttle:10,1');
    });
