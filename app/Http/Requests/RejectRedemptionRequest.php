<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectRedemptionRequest extends FormRequest
{
    /**
     * Hanya admin dan petugas yang bisa menolak penukaran.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'petugas']);
    }

    public function rules(): array
    {
        return [
            'catatan' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
            'catatan.max'      => 'Catatan tidak boleh lebih dari 255 karakter.',
        ];
    }
}
