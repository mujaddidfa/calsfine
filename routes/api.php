<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/locations', [LocationController::class, 'index']);
Route::post('/order', [OrderController::class, 'store']);