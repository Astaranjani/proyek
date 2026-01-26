<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\VoucherController; 
use App\Http\Controllers\PembayaranController;
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
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']); // ✅ ROUTE INI PENTING
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);


    // ================== PROFILE ==================
    Route::get('/profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('api.profile.update');
    //==================ongkir=======================
    Route::get('/provinces', [OngkirController::class, 'getProvinces']);
     Route::get('/cities/{province_id}', [OngkirController::class, 'getCities']);
     Route::post('/ongkir/cost', [OngkirController::class, 'getCost']);

    
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

Route::middleware('auth:sanctum')->group(function () {
    
    // ... route lain yang sudah ada ...
    
    // Promo routes - menggunakan KeranjangController
    Route::post('/promo/apply', [KeranjangController::class, 'applyPromoApi']);
    Route::post('/promo/check', [KeranjangController::class, 'checkPromoApi']);
    Route::get('/promos', [KeranjangController::class, 'getActivePromosApi']); 
});

Route::middleware('auth:api')->group(function () {
    Route::post('payment/create', [PaymentController::class, 'processPayment']);
    Route::get('payment/status/{orderId}', [PaymentController::class, 'checkStatus']);
});
     Route::get('/voucher/cek', [VoucherController::class, 'cekVoucher']);
    
Route::middleware('auth:sanctum')->group(function () {
    // ... existing routes ...
    Route::post('/voucher/check', [KeranjangController::class, 'checkVoucherApi']);
    Route::post('/voucher/apply', [KeranjangController::class, 'applyVoucherApi']);
});
Route::post('/voucher/check', [KeranjangController::class, 'checkVoucherApi']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pembayaran/create-snap-token', [PembayaranController::class, 'createSnapToken']);
});
Route::middleware('auth:sanctum')->group(function () {
    // ... route API lainmu

    // Simpan pesanan dari mobile setelah pembayaran sukses
    Route::post('/mobile/orders/store', [PembayaranController::class, 'storeMobileOrder']);

    // Ambil riwayat pesanan user (untuk halaman /orders di mobile)
    Route::get('/mobile/orders', [PembayaranController::class, 'getMobileOrders']);
});
Route::middleware('auth:sanctum')->group(function () {
    // mobile create order (setelah pembayaran sukses)
    Route::post('/mobile/orders', [PembayaranController::class, 'storeMobileOrder']);

    // mobile get riwayat transaksi (gabungan web + mobile, karena 1 tabel)
    Route::get('/mobile/orders', [PembayaranController::class, 'getMobileOrders']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);          // untuk fetch data mobile
    Route::post('/profile/update', [ProfileController::class, 'update']); // untuk update + upload foto
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']); // ⬅️ POST
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});
