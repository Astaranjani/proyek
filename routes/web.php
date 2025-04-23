<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
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
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/kontak', fn () => view('kontak'))->name('kontak');

// ===========================
// AUTENTIKASI
// ===========================
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// ===========================
// ADMIN
// ===========================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Barang (CRUD)
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    // Optional: edit, update, destroy bisa ditambahkan juga
});

// ===========================
// OWNER
// ===========================
Route::get('/owner/dashboard', [OwnerController::class, 'index'])
    ->middleware('auth')
    ->name('owner.dashboard');

// ===========================
// PELANGGAN (USER)
// ===========================
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

