<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage services');
    }

    public function rules(): array
    {
        return [
            'addon_name' => 'required|string|max:255',
            'addon_price' => 'required|numeric|min:0',
        ];
    }
}