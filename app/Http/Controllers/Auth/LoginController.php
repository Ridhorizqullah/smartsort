<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['admin', 'petugas'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('warga.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login via NIK.
     * Validasi ditangani oleh LoginRequest (FormRequest).
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('nik', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
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

        return redirect('/');
    }
}
