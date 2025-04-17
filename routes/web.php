<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\Admin\BarangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', function () {
    return view('home');
});

// Redirect setelah login
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// Dashboard umum (bisa disesuaikan)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');


// ===========================
// Autentikasi
// ===========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


// ===========================
// Halaman Umum
// ===========================
Route::get('/produk', function () {
    return view('produk');
})->name('produk');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');


// ===========================
// ADMIN
// ===========================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Barang (CRUD)
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    // (Optional: edit, update, delete bisa ditambahkan di sini juga)
});


// ===========================
// OWNER
// ===========================
Route::get('/owner/dashboard', [OwnerController::class, 'index'])
    ->middleware(['auth', 'role:owner'])
    ->name('owner.dashboard');


// ===========================
// PELANGGAN (USER)
// ===========================
Route::get('/pelanggan/dashboard', [PelangganController::class, 'index'])
    ->middleware(['auth', 'role:pelanggan'])
    ->name('pelanggan.dashboard');
