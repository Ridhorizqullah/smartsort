<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya admin atau petugas yang boleh menginput transaksi timbangan
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'petugas']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'idempotency_key' => ['required', 'string', 'unique:transactions,idempotency_key'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.waste_category_id' => ['required', 'integer', 'exists:waste_categories,id'],
            'items.*.weight' => ['required', 'numeric', 'min:0.1'], // Minimal 0.1 Kg
        ];
    }
    
    public function messages(): array
    {
        return [
            'items.*.weight.min' => 'Berat sampah tidak boleh kurang dari 0.1 Kg.',
            'items.min' => 'Minimal harus ada 1 item sampah yang ditimbang.',
            'idempotency_key.unique' => 'Transaksi ini sedang atau telah diproses.',
        ];
    }
}
