<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only Admins can create or update services
        return $this->user()->hasRole('ADMIN');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_name' => 'required|string|max:255',
            'pricing_type' => 'required|in:PER_KG,PER_ITEM',
            'service_base_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Custom messages for better UX
     */
    public function messages(): array
    {
        return [
            'service_name.required' => 'The service needs a name (e.g., Comforter Wash).',
            'pricing_type.in' => 'Please select a valid pricing unit (KG or Item).',
            'service_base_price.numeric' => 'The price must be a valid number.',
        ];
    }
}