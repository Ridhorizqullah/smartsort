<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedemptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Warga yang berhak melakukan penukaran
        return auth()->check() && auth()->user()->role === 'warga';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idempotency_key' => ['required', 'string', 'unique:redemptions,idempotency_key'],
            'reward_id' => ['required', 'integer', 'exists:rewards,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:999'], // Anti bypass qty tidak wajar
        ];
    }

    public function messages(): array
    {
        return [
            'qty.min' => 'Jumlah barang yang ditukar tidak boleh kurang dari 1.',
            'idempotency_key.unique' => 'Permintaan penukaran ini sedang atau telah diproses.',
        ];
    }
}
