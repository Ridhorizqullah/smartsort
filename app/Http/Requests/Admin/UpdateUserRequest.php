<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'nik' => ['required', 'string', 'size:16', 'unique:users,nik,' . $id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:admin,petugas,warga'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'rt_rw' => ['required', 'string', 'max:10'],
        ];
    }
}
