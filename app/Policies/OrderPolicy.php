<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function cancel(User $user, Order $order): bool
    {
        if (!$order->isCancellable()) {
            return false;
        }

        // Gamitin ang Spatie hasRole/hasPermissionTo
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        if ($user->hasPermissionTo('cancel any order')) {
            return true;
        }

        return $user->id === $order->user_id;
    }

    /**
     * Dito ang naging error.
     * Palitan ang $user->role ng Spatie hasAnyRole method.
     */
    public function updateStatus(User $user): bool
    {
        // Spatie method para i-check kung Admin o Staff ang user
        return $user->hasAnyRole(['ADMIN', 'STAFF']);
    }
}