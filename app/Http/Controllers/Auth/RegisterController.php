<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * Proses registrasi warga baru.
     * Validasi ditangani oleh RegisterRequest (FormRequest).
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'nik'      => $request->nik,
            'name'     => $request->name,
            'rt_rw'    => $request->rt_rw,
            'password' => Hash::make($request->password),
            'role'     => 'warga',
        ]);

        // Auto login setelah mendaftar
        Auth::login($user);

        return redirect()->route('warga.dashboard');
    }
}
