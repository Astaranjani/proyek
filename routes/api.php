<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// PENTING: Panggil Controller Ongkir di sini
use App\Http\Controllers\OngkirController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- GROUP ROUTE ONGKIR ---
// Laravel otomatis menambahkan awalan "/api" untuk file ini.
// Jadi URL-nya nanti: /api/ongkir/provinces
Route::prefix('ongkir')->group(function () {
    Route::get('provinces', [OngkirController::class, 'getProvinces']);
    Route::get('cities/{province_id}', [OngkirController::class, 'getCities']);
    Route::post('cost', [OngkirController::class, 'getCost']);
});

Route::get('/cek-koneksi', function () {
    return response()->json(['status' => 'Masuk Pak Eko!']);
});