<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    
    public function cancel(User $user, Order $order): bool
    {
        // 1. If the order isn't in a cancellable state (e.g., already received), no one can cancel.
        if (!$order->isCancellable()) {
            return false;
        }

        // 2. Admin can always cancel cancellable orders
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        // 3. Staff can cancel if they have the specific permission
        if ($user->hasPermissionTo('cancel any order')) {
            return true;
        }

        // 4. Customers can only cancel their own orders
        return $user->id === $order->user_id;
    }

    public function updateStatus(User $user): bool
    {
        return in_array($user->role, ['ADMIN', 'STAFF']);
    }
}