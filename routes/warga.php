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

        // Dashboard
        Route::get('/dashboard', [WargaController::class, 'dashboard'])->name('dashboard');

        // Riwayat Setoran Sampah
        Route::get('/transaksi', [WargaController::class, 'transaksi'])->name('transaksi');

        // Katalog Sembako
        Route::get('/katalog', [WargaController::class, 'katalog'])->name('katalog');

        // Penukaran Poin
        Route::get('/penukaran', [WargaController::class, 'redemption'])->name('redemption');
        Route::post('/penukaran', [WargaController::class, 'storeRedemption'])
            ->name('redemption.store')
            ->middleware('throttle:10,1'); // Max 10 penukaran per menit
    });
