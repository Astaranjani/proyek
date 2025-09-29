<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BarangController;

// 🔓 Public Routes
Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/login', [AuthController::class, 'apiLogin']);

// 🔒 Protected Routes (Butuh Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    Route::get('/products', [BarangController::class, 'index']);
});
