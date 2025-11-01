<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;

// ========================================================
// 🔓 PUBLIC ROUTES (TIDAK PERLU LOGIN)
// ========================================================
Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.register');
Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');

// 🔹 Produk bisa diakses tanpa token
Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('api.products.show');

// ========================================================
// 🔒 PROTECTED ROUTES (HARUS LOGIN DENGAN TOKEN SANCTUM)
// ========================================================
Route::middleware('auth:sanctum')->group(function () {

    // ================== AUTH ==================
    Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');

    // ================== CART ==================
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('api.cart.index');
        Route::post('/add', [CartController::class, 'store'])->name('api.cart.add');
        Route::put('/update/{id}', [CartController::class, 'update'])->name('api.cart.update');
        Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('api.cart.remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('api.cart.clear'); // ✅ tambahan
    });

    // ================== PROFILE ==================
    Route::get('/profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('api.profile.update');

    // ================== CHECKOUT & PAYMENT ==================
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('api.checkout.store');
    Route::get('/checkout/history', [CheckoutController::class, 'history'])->name('api.checkout.history');

    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('api.payment.process');
    Route::get('/payment/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('api.payment.status');
});

// ========================================================
// 🚫 HANDLE ROUTE TIDAK DITEMUKAN
// ========================================================
Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint tidak ditemukan. Periksa URL API Anda.',
    ], 404);
});
