<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check(); 
    }

    public function rules(): array
    {
        $user = $this->user();
        
        $rules = [
            'service_id'        => 'required|exists:main_services,id',
            'load_size'         => 'required|numeric|min:0.1',
            'collection_method' => 'required|in:DROP_OFF,STAFF_PICKUP',
            'return_method'     => 'required|in:PICKUP,DELIVERY',
            'addons'            => 'nullable|array',
        ];

        // If Admin is doing a walk-in, they MUST provide these
        if ($user->hasAnyRole(['ADMIN', 'STAFF'])) {
            $rules['email'] = 'required|email';
            $rules['customer_name'] = 'required|string|max:255';
        }

        // Logistic Check: If delivery/pickup is chosen, check if we have info
        $logisticsActive = ($this->collection_method === 'STAFF_PICKUP' || $this->return_method === 'DELIVERY');

        if ($logisticsActive) {
            // Only require in the FORM if the USER doesn't have it in the DB
            $rules['contact_no'] = ($user->contact_no) ? 'nullable|string' : 'required|string|max:20';
            $rules['address']    = ($user->address) ? 'nullable|string' : 'required|string|max:500';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'contact_no.required' => 'We need a contact number to coordinate the pickup/delivery.',
            'address.required'    => 'An address is mandatory so our rider knows where to go.',
            'collection_date.after_or_equal' => 'Collection date cannot be in the past.',
        ];
    }
}