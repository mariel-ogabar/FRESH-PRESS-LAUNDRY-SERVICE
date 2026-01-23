<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Collection;
use App\Models\LaundryStatus;
use App\Models\Payment;
use App\Models\Delivery;

class UpdateStatusRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize(): bool
    {
        // This allows both ADMIN and STAFF to pass
        return \Illuminate\Support\Facades\Auth::check() && 
            \Illuminate\Support\Facades\Auth::user()->hasAnyRole(['ADMIN', 'STAFF']);
    }
    
    // Get the validation rules that apply to the request
    public function rules(): array
    {
        return [
            'collection_status' => 'nullable|string|in:' . Collection::STATUS_PENDING . ',' . Collection::STATUS_RECEIVED,
            
            'current_status'    => 'nullable|string|in:' . implode(',', [
                LaundryStatus::PENDING,
                LaundryStatus::WASHING,
                LaundryStatus::DRYING,
                LaundryStatus::FOLDING,
                LaundryStatus::IRONING,
                LaundryStatus::READY,
            ]),

            'payment_status'    => 'nullable|string|in:' . Payment::STATUS_PENDING . ',' . Payment::STATUS_PAID,
            
            'delivery_status'   => 'nullable|string|in:' . Delivery::STATUS_READY . ',' . Delivery::STATUS_DELIVERED,
        ];
    }
}