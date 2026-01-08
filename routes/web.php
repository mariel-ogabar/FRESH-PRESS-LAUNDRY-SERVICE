<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard (The Main Page)
    Route::get('/dashboard', [CustomerController::class, 'index'])->name('dashboard');

    // 2. The Booking Page (URL: /book)
    Route::get('/book', [CustomerController::class, 'create'])->name('book.create');

    // 3. The Action to Save the Order
    Route::post('/book', [CustomerController::class, 'store'])->name('book.store');

    // 4. Edit Order
    Route::get('/order/edit/{id}', [CustomerController::class, 'edit'])->name('order.edit');
    Route::put('/order/update/{id}', [CustomerController::class, 'update'])->name('order.update');

    // 5. Cancel Order
    Route::post('/order/cancel/{id}', [CustomerController::class, 'cancel'])->name('order.cancel');

    // 5. Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';