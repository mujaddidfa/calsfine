<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/order', [OrderController::class, 'index'])->name('order');

// Fortify akan otomatis handle routes ini:
// GET /admin/login
// POST /admin/login  
// POST /admin/logout

// Admin dashboard routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/menus', [AdminController::class, 'menus'])->name('menus');
});

// Redirect /admin to dashboard
Route::get('/admin', function() {
    if (auth('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect('/admin/login');
});