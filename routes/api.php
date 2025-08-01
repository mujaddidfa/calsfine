<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PickupTimeController;
use App\Http\Controllers\PaymentController;

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/pickup-times/{location}', [PickupTimeController::class, 'getByLocation']);
Route::post('/order', [OrderController::class, 'store']);
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');