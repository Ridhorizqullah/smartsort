<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRewardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => ['required', 'string', 'max:255', 'unique:rewards,name,' . $id],
            'point_cost' => ['required', 'numeric', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
