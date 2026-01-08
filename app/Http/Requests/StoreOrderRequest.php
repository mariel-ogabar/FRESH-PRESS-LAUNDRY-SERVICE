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
        $rules = [
            'service_id'        => 'required|exists:main_services,id',
            'load_size'         => 'required|numeric|min:0.1',
            'collection_method' => 'required|in:DROP_OFF,STAFF_PICKUP',
            'return_method'     => 'required|in:PICKUP,DELIVERY',
            'addons'            => 'nullable|array',
        ];

        if ($this->user() && $this->user()->role === 'ADMIN') {
            $rules['email'] = 'required|email';
            $rules['customer_name'] = 'required|string|max:255';
            $rules['contact_no']    = 'nullable|string|max:20';
            $rules['address']       = 'nullable|string|max:500';
        }

        if ($this->collection_method === 'STAFF_PICKUP') {
            $rules['collection_date'] = 'required|date|after_or_equal:today';
            $rules['collection_time'] = 'required';
        }

        return $rules;
    }
}