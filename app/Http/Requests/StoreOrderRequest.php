<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize(): bool
    {
        return Auth::check(); 
    }

    // Get the validation rules that apply to the request
    public function rules(): array
    {
        /** @var \App\Models\User $user */
        $user = $this->user();
        
        $rules = [
            'service_id'        => 'required|exists:main_services,id',
            'load_size'         => 'required|numeric|min:0.1',
            'collection_method' => 'required|in:DROP_OFF,STAFF_PICKUP',
            'return_method'     => 'required|in:PICKUP,DELIVERY',
            'addons'            => 'nullable|array',
            
            // Ensure these are ALWAYS validated so they reach the controller
            'contact_no'        => 'required|string|max:20',
            'address'           => 'required|string|max:500',
        ];

        if ($user->hasAnyRole(['ADMIN', 'STAFF'])) {
            $rules['email'] = 'required|email';
            $rules['customer_name'] = 'required|string|max:255';
        }

        if ($this->collection_method === 'STAFF_PICKUP') {
            $rules['collection_date'] = 'required|date|after_or_equal:today';
            $rules['collection_time'] = 'required';
        }

        return $rules;
    }

    // Custom messages for validation errors
    public function messages(): array
    {
        return [
            'contact_no.required' => 'We need a contact number to coordinate the pickup/delivery.',
            'address.required'    => 'An address is mandatory so our rider knows where to go.',
            'collection_date.after_or_equal' => 'Collection date cannot be in the past.',
        ];
    }
}