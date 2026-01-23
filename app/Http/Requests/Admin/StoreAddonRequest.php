<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddonRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize(): bool
    {
        return $this->user()->can('manage services');
    }

    // Get the validation rules that apply to the request
    public function rules(): array
    {
        return [
            'addon_name' => 'required|string|max:255',
            'addon_price' => 'required|numeric|min:0',
        ];
    }
}