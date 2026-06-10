<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:waste_categories,name'],
            'description' => ['nullable', 'string'],
            'price_per_kg' => ['required', 'numeric', 'min:0'],
        ];
    }
}
