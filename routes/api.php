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
        // Get cart
        Route::get('/', [CartController::class, 'index'])->name('api.cart.index');
        
        // Add to cart
        Route::post('/add', [CartController::class, 'store'])->name('api.cart.add');
        
        // ✅ FIXED: Update quantity dengan PUT & PATCH support
        Route::put('/{id}', [CartController::class, 'update'])->name('api.cart.update.put');
        Route::patch('/{id}', [CartController::class, 'update'])->name('api.cart.update.patch');
        
        // Alternative update route (backward compatibility)
        Route::put('/update/{id}', [CartController::class, 'update'])->name('api.cart.update');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('api.cart.update.alt');
        
        // Delete cart item
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('api.cart.destroy');
        Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('api.cart.remove');
        
        // Clear all cart
        Route::delete('/clear', [CartController::class, 'clear'])->name('api.cart.clear');
    });

    // ================== PROFILE ==================
    Route::get('/profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('api.profile.update');

    // ================== CHECKOUT & PAYMENT ==================
    Route::prefix('payment')->group(function () {
        Route::post('/create', [PaymentController::class, 'create'])->name('api.payment.create');
        Route::get('/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('api.payment.status');
    });
});

// ========================================================
// 🌐 WEBHOOK ROUTES (NO AUTH REQUIRED)
// ========================================================
// Payment notification webhook from payment gateway
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('api.payment.notification');

// ========================================================
// 🚫 HANDLE ROUTE TIDAK DITEMUKAN (404)
// ========================================================
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan. Periksa URL API Anda.',
        'available_endpoints' => [
            'auth' => [
                'POST /api/register',
                'POST /api/login',
                'POST /api/logout (auth required)',
            ],
            'products' => [
                'GET /api/products',
                'GET /api/products/{id}',
            ],
            'cart' => [
                'GET /api/cart (auth required)',
                'POST /api/cart/add (auth required)',
                'PUT /api/cart/{id} (auth required)',
                'DELETE /api/cart/{id} (auth required)',
            ],
            'profile' => [
                'GET /api/profile (auth required)',
                'PUT /api/profile/update (auth required)',
            ],
            'payment' => [
                'POST /api/payment/create (auth required)',
                'GET /api/payment/status/{orderId} (auth required)',
            ],
        ],
    ], 404);
});