<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::resource('barang', BarangController::class);

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::put('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakPDF'])->name('transaksi.cetak');
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/transaksi/download', [TransaksiController::class, 'download'])->name('transaksi.download');

    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.barang');
    Route::get('/laporan/barang-pdf', [LaporanController::class, 'laporanBarangPDF'])->name('laporan.barang_pdf');

    Route::get('/manual', fn() => view('admin.manual'))->name('manual');
    Route::post('/manual', [ManualTransaksiController::class, 'store'])->name('manual.store');
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
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/produk/{barang}', [HomeController::class, 'detail'])->name('produk.detail');

    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::post('/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');

    Route::post('/keranjang/tambah-dan-bayar', [KeranjangController::class, 'tambahDanBayar'])->name('keranjang.tambah-dan-bayar');
    Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');
    Route::post('/pembayaran/beli-sekarang/proses', [PembayaranController::class, 'beliSekarangProses'])->name('pembayaran.beliSekarang.proses');

    Route::get('/riwayat-pesanan', [RiwayatController::class, 'index'])->name('riwayat.pesanan');

    // ===========================
    // CHAT ROUTES
    // ===========================
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat');
});
});

// ===========================
// PEMBAYARAN ROUTES
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
    Route::post('/cod', [PembayaranController::class, 'cod'])->name('pembayaran.cod');
    Route::get('/pembayaran-sukses', fn() => view('pembayaran-sukses'))->name('pembayaran.sukses');
});

// ===========================
// MIDTRANS CALLBACK
// ===========================
Route::post('/payment/midtrans-callback', [App\Http\Controllers\PaymentController::class, 'midtransCallback']);

