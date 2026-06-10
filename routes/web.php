<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Public)
|--------------------------------------------------------------------------
| Hanya berisi route yang bisa diakses publik tanpa login:
| landing page, login, register, dan logout.
|
| Route admin  → routes/admin.php
| Route warga  → routes/warga.php
*/

// Landing Page
Route::get('/', function () {
    $categories = \App\Models\WasteCategory::all();
    return view('welcome', compact('categories'));
})->name('home');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
