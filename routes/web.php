<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\ManualTransaksiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OngkirController;

// ===========================
// HALAMAN UTAMA
// ===========================
Route::get('/', fn () => view('home'));
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/kontak', fn () => view('kontak'))->name('kontak');

// ===========================
// AUTH
// ===========================
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// ===========================
// ADMIN AREA
// ===========================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Barang
    Route::resource('barang', BarangController::class);

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::put('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakPDF'])->name('transaksi.cetak');
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // Laporan
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.barang');
    Route::get('/laporan/barang-pdf', [LaporanController::class, 'laporanBarangPDF'])->name('laporan.barang_pdf');

    // Manual Transaksi
    Route::get('/manual', fn() => view('admin.manual'))->name('manual');
    Route::post('/manual', [ManualTransaksiController::class, 'store'])->name('manual.store');

    // Voucher
    Route::get('/voucher/create', [DashboardAdminController::class, 'createVoucher'])->name('voucher.create');
    Route::post('/voucher/store', [DashboardAdminController::class, 'storeVoucher'])->name('voucher.store');
    Route::get('/voucher/{voucher}/edit', [DashboardAdminController::class, 'editVoucher'])->name('voucher.edit');
    Route::put('/voucher/{voucher}', [DashboardAdminController::class, 'updateVoucher'])->name('voucher.update');
    Route::delete('/voucher/{voucher}', [DashboardAdminController::class, 'destroyVoucher'])->name('voucher.destroy');

    // Chat Admin
    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat');

    // Promo Admin
    Route::get('/promo', [DashboardAdminController::class, 'promoIndex'])->name('promo');
    Route::post('/promo/store', [DashboardAdminController::class, 'promoStore'])->name('promo.store');
    Route::delete('/promo/{promo}', [DashboardAdminController::class, 'promoDestroy'])->name('promo.destroy');
});

// ===========================
// OWNER
// ===========================
Route::middleware('auth')->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
});

// ===========================
// USER ROUTES
// ===========================
Route::middleware('auth')->group(function () {

    // Dashboard / profile
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Produk
    Route::get('/produk/{barang}', [HomeController::class, 'detail'])->name('produk.detail');

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::post('/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::post('/keranjang/apply-promo', [KeranjangController::class, 'applyPromo'])->name('keranjang.applyPromo');
    Route::post('/keranjang/remove-promo', [KeranjangController::class, 'removePromo'])->name('keranjang.removePromo');
    Route::post('/keranjang/tambah-dan-bayar', [KeranjangController::class, 'tambahDanBayar'])->name('keranjang.tambah-dan-bayar');

    // Voucher Check
    Route::post('/voucher/check', [KeranjangController::class, 'checkVoucher'])->name('voucher.check');

    // Checkout
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');

    // ===========================
    // PEMBAYARAN - INI YANG PENTING!
    // ===========================
    // Route untuk halaman pembayaran dari keranjang (GET)
   Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pembayaran.index');
    
    // POST - Terima form dari keranjang atau halaman pembayaran
    Route::post('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pembayaran'); // ← INI YANG DIBUTUHKAN oleh keranjang.blade.php
    
    // POST - Proses pembayaran
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])
        ->name('pembayaran.proses');
    
    // POST - Beli sekarang
    Route::post('/pembayaran/beli-sekarang', [PembayaranController::class, 'beliSekarang'])
        ->name('beli.sekarang');
    
    // POST - Create snap token
    Route::post('/pembayaran/create-snap-token', [PembayaranController::class, 'createSnapToken'])
        ->name('create.snap');
});

    // Riwayat User
    Route::get('/riwayat-pesanan', [RiwayatController::class, 'index'])->name('riwayat.pesanan');

    // Chat user
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');

// ===========================
// MIDTRANS CALLBACK
// ===========================
Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback']);

Route::post('/keranjang/check-promo-validity', [KeranjangController::class, 'checkPromoValidity'])->name('keranjang.checkPromoValidity');

Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    
    // Proses beli sekarang
    Route::post('/pembayaran/beli', [PembayaranController::class, 'beliSekarang'])->name('pembayaran.beli');
    
    // Get snap token
    Route::post('/pembayaran/snap-token', [PembayaranController::class, 'getSnapToken'])->name('pembayaran.snapToken');
    
    // Proses callback
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
    
    // API ongkir
    Route::get('/api/districts', [PembayaranController::class, 'getDistricts']);
    Route::post('/api/cost', [PembayaranController::class, 'getCost']);
    // ======================
//  ONGKIR ROUTES
// ======================
Route::get('/ongkir/districts', function () {
    return response()->json([
        "Indramayu", "Jatibarang", "Karangampel", "Lohbener",
        "Kertasemaya", "Cikedung", "Widasari"
    ]);
})->name('ongkir.districts');

Route::post('/ongkir/cost', function (Illuminate\Http\Request $request) {
    $destination = $request->destination;

    // Contoh hardcode ongkir (silakan diganti sesuai logikamu)
    return response()->json([
        [
            "cost" => [
                [
                    "value" => 15000,
                    "etd" => "1-2 Hari"
                ]
            ]
        ]
    ]);
})->name('ongkir.cost');
Route::post('/pembayaran/get-token', [PembayaranController::class, 'getToken'])
    ->name('pembayaran.getToken');

Route::post('/pembayaran/snap-token', [PembayaranController::class, 'snapToken'])
    ->name('pembayaran.snapToken');

Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])
    ->name('pembayaran.proses');

Route::post('/ongkir/cost', [PembayaranController::class, 'getCost'])->name('ongkir.cost');
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');
    
    // API untuk ongkir
    Route::get('/api/districts', [PembayaranController::class, 'getDistricts']);
    Route::post('/api/get-cost', [PembayaranController::class, 'getCost']);
    
    Route::post('/create-snap-token', [PembayaranController::class, 'createSnapToken']);
    Route::post('/get-snap-token', [PembayaranController::class, 'getSnapToken']);
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::post('/ongkir/cost', [PembayaranController::class, 'ongkirCost'])
    ->name('ongkir.cost');

Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');
Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])
    ->name('pembayaran.beli');
Route::post('/ongkir/cost', [OngkirController::class, 'getCost'])->name('ongkir.cost');
Route::get('/ongkir/districts', [OngkirController::class, 'getDistricts'])->name('ongkir.districts');

// Route untuk Pembayaran
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
Route::post('/pembayaran/snap-token', [PembayaranController::class, 'createSnapToken'])->name('pembayaran.snapToken');
Route::post('/pembayaran/get-token', [PembayaranController::class, 'getSnapToken'])->name('pembayaran.getToken');
Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');

Route::post('/ongkir/cost', [App\Http\Controllers\OngkirController::class, 'getCost'])->name('ongkir.cost');
Route::post('/pembayaran/snap-token', [PembayaranController::class, 'createSnapToken'])->name('pembayaran.snapToken');
Route::post('/pembayaran/get-token', [PembayaranController::class, 'getSnapToken'])->name('pembayaran.getToken');
Route::post('/pembayaran/get-ongkir', [PembayaranController::class, 'getOngkir'])->name('pembayaran.getOngkir');
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');

    // buat token snap (checkout cart)
    Route::post('/pembayaran/snap-token', [PembayaranController::class, 'createSnapToken'])
        ->name('pembayaran.snapToken');

    // buat token untuk beli_sekarang (transaksi existing)
    Route::post('/pembayaran/get-token', [PembayaranController::class, 'getSnapToken'])
        ->name('pembayaran.getToken');

    // proses hasil pembayaran (form submit dari client)
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])
        ->name('pembayaran.proses');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    
    // Test dengan ANY method
    Route::any('/pembayaran/snap-token', [PembayaranController::class, 'createSnapToken'])
        ->name('pembayaran.snapToken');
    
    Route::any('/pembayaran/get-token', [PembayaranController::class, 'getSnapToken'])
        ->name('pembayaran.getToken');
    
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])
        ->name('pembayaran.proses');
    Route::any('/test-endpoint', function() {
    return response()->json([
        'success' => true,
        'message' => 'Endpoint working!',
        'timestamp' => now()
    ]);
});
Route::post('/pembayaran/cart-token', [PembayaranController::class, 'createSnapToken'])
    ->name('pembayaran.snapToken');

// checkout BELI SEKARANG
Route::post('/pembayaran/get-token', [PembayaranController::class, 'getSnapToken'])
    ->name('pembayaran.getToken');

// proses setelah sukses/pending Midtrans
Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])
    ->name('pembayaran.proses');
    // TAMPIL PROFIL (GET)
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');

    // UPDATE PROFIL (PUT)
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
        Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::delete('/admin/promo/{id}', [DashboardAdminController::class, 'promoDestroy'])
    ->name('admin.promo.destroy');

Route::get(
    'admin/transaksi/download',
    [\App\Http\Controllers\Admin\TransaksiController::class, 'download']
)->name('admin.transaksi.download');

Route::resource(
    'admin/transaksi',
    \App\Http\Controllers\Admin\TransaksiController::class
);
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('transaksi-download', [TransaksiController::class, 'download'])
        ->name('transaksi.download');

    Route::resource('transaksi', TransaksiController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {

    // ✅ TARUH DI ATAS
    Route::get('transaksi/download', [TransaksiController::class, 'download'])
        ->name('transaksi.download');

    // ✅ BARU RESOURCE
    Route::resource('transaksi', TransaksiController::class);
});


