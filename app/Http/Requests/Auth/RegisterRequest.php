<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    /**
     * Semua user bisa mendaftar (guest).
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik'      => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', 'unique:users,nik'],
            'name'     => ['required', 'string', 'max:255'],
            'rt_rw'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'  => 'NIK wajib diisi.',
            'nik.size'      => 'NIK harus terdiri dari 16 digit angka.',
            'nik.regex'     => 'NIK hanya boleh berisi angka.',
            'nik.unique'    => 'NIK ini sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'rt_rw.required' => 'Alamat RT/RW wajib diisi.',
        ];
    }
}
