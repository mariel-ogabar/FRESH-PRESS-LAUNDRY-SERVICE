<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    
    public function cancel(User $user, Order $order): bool
    {
        if ($user->role === 'ADMIN') {
            return $order->isCancellable();
        }

        return $user->id === $order->user_id && $order->isCancellable();
    }

    public function updateStatus(User $user): bool
    {
        return in_array($user->role, ['ADMIN', 'STAFF']);
    }
}