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
use App\Http\Controllers\PaymentController;

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
    Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Barang
    Route::resource('barang', BarangController::class);

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::put('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakPDF'])->name('transaksi.cetak');
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/transaksi/download', [TransaksiController::class, 'download'])->name('transaksi.download');

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

    // Pembayaran (Admin)
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
    Route::post('/pembayaran/callback', [PembayaranController::class, 'callback'])->name('pembayaran.callback');
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
    // Dashboard & Profil
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Produk
    Route::get('/produk/{barang}', [HomeController::class, 'detail'])->name('produk.detail');

    // Keranjang & Checkout
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::post('/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
    Route::post('/keranjang/tambah-dan-bayar', [KeranjangController::class, 'tambahDanBayar'])->name('keranjang.tambah-dan-bayar');

    // Pembayaran (User)
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
    Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');
    Route::post('/pembayaran/beli-sekarang/proses', [PembayaranController::class, 'beliSekarangProses'])->name('pembayaran.beliSekarang.proses');
    Route::post('/cod', [PembayaranController::class, 'cod'])->name('pembayaran.cod');

    // Riwayat Pesanan
    Route::get('/riwayat-pesanan', [RiwayatController::class, 'index'])->name('riwayat.pesanan');

    // Chat (User)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});

// ===========================
// MIDTRANS CALLBACK (Global)
// ===========================
Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback']);
