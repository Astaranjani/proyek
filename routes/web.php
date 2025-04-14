<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('home');
});

// Redirect ke dashboard setelah login
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');


// Pastikan hanya pengguna yang sudah login yang bisa mengakses dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Rute untuk autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // Redirect ke halaman utama setelah logout
})->name('logout');

// Rute halaman lainnya
Route::get('/produk', function () {
    return view('produk');
})->name('produk');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth');
Route::get('/owner/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard')->middleware('auth');
Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard')->middleware('auth');
