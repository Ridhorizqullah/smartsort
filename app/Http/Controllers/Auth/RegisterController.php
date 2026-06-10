<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'rt_rw' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'rt_rw' => $request->rt_rw,
            'password' => Hash::make($request->password),
            'role' => 'warga',
        ]);

        // Auto login setelah mendaftar
        Auth::login($user);

        return redirect()->route('warga.dashboard');
    }
}
