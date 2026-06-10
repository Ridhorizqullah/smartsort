<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveRedemptionRequest extends FormRequest
{
    /**
     * Hanya admin dan petugas yang bisa approve penukaran.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'petugas']);
    }

    public function rules(): array
    {
        return [
            'tanggal_ambil' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_ambil.required'        => 'Tanggal pengambilan wajib diisi.',
            'tanggal_ambil.date'            => 'Format tanggal tidak valid.',
            'tanggal_ambil.after_or_equal'  => 'Tanggal pengambilan tidak boleh sebelum hari ini.',
        ];
    }
}
