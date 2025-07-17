<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AdminOrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/order', [OrderController::class, 'index'])->name('order');

// Fortify akan otomatis handle routes ini:
// GET /admin/login
// POST /admin/login  
// POST /admin/logout

// Admin dashboard routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Main Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/history-data', [AdminController::class, 'getHistoryData'])->name('history.data');
    
    // Order Management Routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/tomorrow', [AdminOrderController::class, 'tomorrow'])->name('orders.tomorrow');
    Route::get('/orders/report', [AdminOrderController::class, 'report'])->name('orders.report');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/completed', [AdminOrderController::class, 'markCompleted'])->name('orders.completed');
    Route::patch('/orders/{order}/cancelled', [AdminOrderController::class, 'markCancelled'])->name('orders.cancelled');
    Route::post('/orders/bulk-update', [AdminOrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    
    // Menu Management Routes
    Route::get('/menus', [MenuController::class, 'adminIndex'])->name('menus');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    
    // Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    
    // Location Management Routes
    Route::get('/locations', [LocationController::class, 'adminIndex'])->name('locations');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
    Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    Route::patch('/locations/{location}/toggle', [LocationController::class, 'toggleStatus'])->name('locations.toggle');
});

// Redirect /admin to dashboard
Route::get('/admin', function() {
    if (auth('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect('/admin/login');
});