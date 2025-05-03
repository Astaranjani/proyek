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
// ADMIN
// ===========================

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Barang (CRUD)
    Route::resource('barang', BarangController::class);
// });


// Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    
//     // Dashboard Admin
//     Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

//     // Barang (CRUD)
//     Route::resource('/barang', BarangController::class, [
//         'as' => 'admin' // otomatis bikin admin.barang.index, admin.barang.create, dst
//     ]);
//     Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
//     Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
//     Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
//     Route::get('/barang/{barang}/destroy', [BarangController::class, 'destroy'])->name('barang.destroy');



    // // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    // Laporan Barang
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.barang');
    Route::get('/laporan/barang-pdf', [LaporanController::class, 'laporanBarangPDF'])->name('laporan.barang_pdf');
});

// ===========================
// OWNER
// ===========================
Route::middleware('auth')->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
});

// ===========================
// PELANGGAN (USER)
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', fn () => view('profile'))->name('profile');
    Route::get('/produk/{barang}', [HomeController::class, 'detail'])->name('produk.detail');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');

});

// ===========================
// PEMBAYARAN
// ===========================
Route::get('/checkout', [PembayaranController::class, 'index'])->name('keranjang.checkout');
Route::post('/checkout', [PembayaranController::class, 'proses'])->name('pembayaran.proses');