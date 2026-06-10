<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page Utama (SmartSort)
Route::get('/', function () {
    $categories = \App\Models\WasteCategory::all();
    return view('welcome', compact('categories'));
})->name('home');

// ===== AUTH ROUTES =====
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware(['guest', 'throttle:5,1']); // Max 5 percobaan/menit
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware(['guest', 'throttle:3,1']); // Max 3 register/menit

use App\Http\Controllers\WargaController;

// ===== WARGA ROUTES =====
// Dilindungi auth middleware dan role 'warga'
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [WargaController::class, 'dashboard'])->name('dashboard');
    Route::get('/transaksi', [WargaController::class, 'transaksi'])->name('transaksi');
    
    // Redemption Routes
    Route::get('/penukaran', [WargaController::class, 'redemption'])->name('redemption');
    Route::post('/penukaran', [WargaController::class, 'storeRedemption'])->name('redemption.store')->middleware('throttle:10,1'); // Max 10 penukaran/menit
    
    Route::get('/katalog', [WargaController::class, 'katalog'])->name('katalog');
});

use App\Http\Controllers\AdminController;

// ===== ADMIN ROUTES =====
// Dilindungi auth middleware dan role 'admin' atau 'petugas'
Route::middleware(['auth', 'role:admin,petugas'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // POS / Transaksi
    Route::get('/transaksi', [AdminController::class, 'transaksiForm'])->name('transaksi');
    Route::post('/transaksi', [AdminController::class, 'transaksiStore'])->name('transaksi.store');
    
    // Penukaran (Redemption)
    Route::get('/penukaran', [AdminController::class, 'redemptionList'])->name('penukaran');
    Route::post('/penukaran/{id}/approve', [AdminController::class, 'approveRedemption'])->name('penukaran.approve');
    Route::post('/penukaran/{id}/reject', [AdminController::class, 'rejectRedemption'])->name('penukaran.reject');
    
    // Placeholders
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
    Route::get('/reward', [AdminController::class, 'reward'])->name('reward');
});
