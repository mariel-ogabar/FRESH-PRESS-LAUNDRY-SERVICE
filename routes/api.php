<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Login Required)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Login Required via Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Shared: Profile and Logout
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('/logout', [AuthController::class, 'logout']);

    // READ: Everyone (Customer, Staff, Admin) can view orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // CREATE: Only CUSTOMER and ADMIN (STAFF is now blocked)
    Route::post('/orders', [OrderController::class, 'store'])
         ->middleware('role:CUSTOMER,ADMIN');

    // UPDATE: STAFF and ADMIN can manage the laundry lifecycle
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])
         ->middleware('role:STAFF,ADMIN');

    // DELETE: Strictly ADMIN only
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])
         ->middleware('role:ADMIN');
});