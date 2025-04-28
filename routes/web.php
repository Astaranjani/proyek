<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\BarangController;

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
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');

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
});
