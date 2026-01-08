<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * DASHBOARD ACCESS (Role-Based Redirection)
 */
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/**
 * PROTECTED ROUTES (RBAC IMPLEMENTATION)
 */
Route::middleware('auth')->group(function () {
    
    // --- Profile Management (Default Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Order Operations (Role Restricted) ---

    // Create Order (Admin for walk-ins, Customer for requests)
    Route::get('/orders/create', [OrderController::class, 'create'])
        ->middleware('role:ADMIN,CUSTOMER')
        ->name('orders.create');

    Route::post('/orders', [OrderController::class, 'store'])
        ->middleware('role:ADMIN,CUSTOMER')
        ->name('orders.store');

    // View Order Details
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Update Collection Status (Admin/Staff only)
    Route::patch('/orders/{id}/collection', [OrderController::class, 'updateCollection'])
        ->middleware('role:ADMIN,STAFF')
        ->name('orders.updateCollection');

    // Update Laundry Status (Admin/Staff only)
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:ADMIN,STAFF')
        ->name('orders.updateStatus');

    // Update Payment Information (ADMIN ONLY - base sa iyong hiling)
    Route::patch('/orders/{id}/payment', [OrderController::class, 'updatePayment'])
        ->middleware('role:ADMIN')
        ->name('orders.updatePayment');

    // Update Delivery Status (Admin/Staff only)
    Route::patch('/orders/{id}/delivery', [OrderController::class, 'updateDelivery'])
        ->middleware('role:ADMIN,STAFF')
        ->name('orders.updateDelivery');

    Route::patch('/orders/{id}/set-schedule', [OrderController::class, 'setDeliverySchedule'])
        ->name('orders.setDeliverySchedule');

    // Cancel Order (Admin and Customer only)
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])
        ->middleware('role:ADMIN,CUSTOMER')
        ->name('orders.cancel');

    // Delete Order (Admin only)
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])
        ->middleware('role:ADMIN')
        ->name('orders.destroy');

    /**
     * ADVANCED SQL: Atomic Payment Processing (Admin Only)
     */
    Route::post('/orders/{id}/pay', function ($id) {
        DB::statement("CALL sp_ProcessPayment(?)", [$id]);
        return back()->with('success', 'Payment processed and order completed.');
    })->middleware('role:ADMIN')->name('orders.pay');

});

require __DIR__.'/auth.php';