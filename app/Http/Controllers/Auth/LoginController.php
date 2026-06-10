<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login warga.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login warga.
     * Hanya user dengan role 'warga' yang diizinkan masuk melalui halaman ini.
     * Admin menggunakan panel /admin (Filament).
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik'      => ['required', 'string'],
            'password' => ['required'],
        ], [
            'nik.required'      => 'NIK wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $credentials = $request->only('nik', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Jika role admin/petugas, redirect ke admin dashboard custom
            if (in_array(Auth::user()->role, ['admin', 'petugas'])) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('warga.dashboard'));
        }

        return back()->withErrors([
            'nik' => 'NIK atau kata sandi tidak cocok.',
        ])->onlyInput('nik');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
