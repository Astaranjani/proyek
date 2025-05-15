<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===========================
// HALAMAN UTAMA & UMUM
// ===========================
Route::get('/', fn () => view('home'));
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/kontak', fn () => view('kontak'))->name('kontak');

// ===========================
// AUTENTIKASI
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
// ADMIN ROUTES
// ===========================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Barang (CRUD)
    Route::resource('barang', BarangController::class);

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::put('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakPDF'])->name('transaksi.cetak');

    // Laporan Barang
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.barang');
    Route::get('/laporan/barang-pdf', [LaporanController::class, 'laporanBarangPDF'])->name('laporan.barang_pdf');
});

// ===========================
// OWNER ROUTES
// ===========================
Route::middleware('auth')->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
});

// ===========================
// PELANGGAN (USER) ROUTES
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', fn () => view('profile'))->name('profile');
    Route::get('/produk/{barang}', [HomeController::class, 'detail'])->name('produk.detail');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
    Route::get('/riwayat-pesanan', fn () => view('riwayat_pesanan'))->name('riwayat.pesanan');
    Route::post('/keranjang/tambah-dan-bayar', [KeranjangController::class, 'tambahDanBayar'])->name('keranjang.tambah-dan-bayar');
});

// ===========================
// PEMBAYARAN ROUTES
// ===========================
// Route::get('/keranjang/checkout', [KeranjangController::class, 'index'])->name('pembayaran');
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
Route::get('/pembayaran', [PembayaranController::class, 'index']);
Route::any('/pembayaran', [PembayaranController::class, 'method']);

// ===========================
// PROFIL ROUTES
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update']);
});

// ===========================
// MIDTRANS CALLBACK
// ===========================
Route::post('/payment/midtrans-callback', [App\Http\Controllers\PaymentController::class, 'midtransCallback']);


// Route untuk Riwayat Pesanan
Route::get('/riwayat-pesanan', [HomeController::class, 'riwayatPesanan'])->name('Riwayat Pesanan');