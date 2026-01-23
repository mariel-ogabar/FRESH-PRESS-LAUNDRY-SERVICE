<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        if (!$order->isCancellable()) {
            return false;
        }

        if ($user->hasRole('ADMIN')) {
            return true;
        }

        if ($user->hasPermissionTo('cancel any order')) {
            return true;
        }

        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can update the status of the order.
     */
    public function updateStatus(User $user): bool
    {
        return $user->hasAnyRole(['ADMIN', 'STAFF']);
    }
}