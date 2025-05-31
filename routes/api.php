<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BarangController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini kamu bisa mendefinisikan route API yang akan diakses
| oleh aplikasi mobile (misalnya React Native atau Ionic).
|
*/

// 🔐 Endpoint untuk login user
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'apiLogout']);


// 🔒 Protected routes (butuh token Sanctum untuk akses)
Route::middleware('auth:sanctum')->group(function () {
    // ✅ Endpoint untuk mengambil data produk (barang)
    Route::get('/products', [BarangController::class, 'index']);

    // Tambahkan route lain yang membutuhkan autentikasi di sini
});
