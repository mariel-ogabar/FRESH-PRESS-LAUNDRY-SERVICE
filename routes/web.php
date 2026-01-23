<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ServiceController; 
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

// 1. PUBLIC SERVICE VIEW: No middleware, strictly for viewing.
Route::get('/services', [ServiceController::class, 'publicIndex'])->name('services.index');

/**
 * DASHBOARD ACCESS
 */
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); 

/**
 * PROTECTED ROUTES (Spatie Implementation)
 */
Route::middleware('auth')->group(function () {
    
    // --- Profile Management ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Order Booking ---
    Route::middleware('role_or_permission:ADMIN|CUSTOMER|create orders')->group(function () {
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    });

    // --- Specific Order Actions ---
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])
        ->middleware('role_or_permission:ADMIN|CUSTOMER|cancel any order')
        ->name('orders.cancel');

    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // --- Laundry Operations ---
    Route::middleware('permission:update order status')->group(function () {
        Route::patch('/orders/{id}/collection', [OrderController::class, 'updateCollection'])->name('orders.updateCollection');
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('/orders/{id}/delivery', [OrderController::class, 'updateDelivery'])->name('orders.updateDelivery');
        Route::patch('/orders/{id}/set-schedule', [OrderController::class, 'setDeliverySchedule'])->name('orders.setDeliverySchedule');
        Route::patch('/orders/{id}/collection-schedule', [OrderController::class, 'setCollectionSchedule'])->name('orders.setCollectionSchedule');
    });

    // --- Administrative Modules ---
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Staff Management
        Route::middleware('role_or_permission:ADMIN|manage staff')->group(function () {
            Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
            Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
            Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
            Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
            Route::patch('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
            Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
        });

        // Service & Pricing Management (Changed to role_or_permission to prevent 403)
        Route::middleware('role_or_permission:ADMIN|manage services')->group(function () {
            // Main Services
            Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
            Route::post('/services', [ServiceController::class, 'storeService'])->name('services.store');
            Route::patch('/services/{id}', [ServiceController::class, 'updateService'])->name('services.update');
            Route::patch('/services/{id}/toggle', [ServiceController::class, 'toggleService'])->name('services.toggle');
            
            // Addon Routes
            Route::post('/addons', [ServiceController::class, 'storeAddon'])->name('addons.store');
            Route::patch('/addons/{id}', [ServiceController::class, 'updateAddon'])->name('addons.update');
            Route::patch('/addons/{id}/toggle', [ServiceController::class, 'toggleAddon'])->name('addons.toggle');
        });
    });

    Route::patch('/orders/{id}/payment', [OrderController::class, 'updatePayment'])
    ->middleware('auth', 'role_or_permission:ADMIN|process payments')
    ->name('orders.updatePayment');

    // --- Strictly ADMIN-ONLY Actions ---
    Route::middleware('role:ADMIN')->group(function () {
            
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        
        Route::post('/orders/{id}/pay', function ($id) {
            DB::statement("CALL sp_ProcessPayment(?)", [$id]);
            return back()->with('success', 'Payment processed.');
        })->name('orders.pay');
    });
});

require __DIR__.'/auth.php';