<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => ['required', 'string', 'max:255', 'unique:waste_categories,name,' . $id],
            'description' => ['nullable', 'string'],
            'price_per_kg' => ['required', 'numeric', 'min:1'],
        ];
    }
}
