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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RiwayatController;
use App\Models\Order;
use App\Http\Controllers\ManualTransaksiController;
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
    Route::get('/admin/transaksi/download', [TransaksiController::class, 'download'])->name('admin.transaksi.download');
    Route::get('/transaksi/download', [TransaksiController::class, 'download'])->name('transaksi.download'); // ✅ Tambahkan ini
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('transaksi/download', [TransaksiController::class, 'download'])->name('transaksi.download');
});
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
Route::post('/beli-sekarang', [KeranjangController::class, 'beliSekarang'])->name('beli.sekarang');
Route::get('/pembayaran', [KeranjangController::class, 'checkout'])->name('pembayaran');
Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');
Route::post('/beli-sekarang', [PembayaranController::class, 'beliSekarang'])->name('beli.sekarang');

Route::post('/proses-beli-sekarang', [YourController::class, 'prosesBeliSekarang'])->name('prosesBeliSekarang');

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
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');

Route::post('/pembayaran', [PembayaranController::class, 'bayar'])->name('pembayaran.bayar');
Route::get('/pembayaran-sukses', function () {
    return view('pembayaran-sukses');
})->name('pembayaran.sukses');
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran'); // ✅ BENAR
Route::post('/cod', [PembayaranController::class, 'cod'])->name('pembayaran.cod');
Route::post('/cod', [PembayaranController::class, 'cod'])->name('pembayaran.cod');
Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::get('/pembayaran/sukses', [KeranjangController::class, 'pembayaranSukses'])->name('pembayaran.sukses');
Route::get('/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran');
Route::post('/pembayaran', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::middleware('auth')->group(function () {
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');

});


// ===========================
// PROFIL ROUTES
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// ===========================
// MIDTRANS CALLBACK
// ===========================
Route::post('/payment/midtrans-callback', [App\Http\Controllers\PaymentController::class, 'midtransCallback']);


// Route untuk Riwayat Pesanan
Route::get('/riwayat-pesanan', [HomeController::class, 'riwayatPesanan'])->name('Riwayat Pesanan');


Route::delete('/admin/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('admin.transaksi.destroy');
Route::get('/admin/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('admin.transaksi.destroy');
// Route::get('/riwayat-pesanan', [HomeController::class, 'riwayatPesanan']);

// ===========================
// RIWAYAT PESANAN USER
// ===========================
Route::get('/riwayat-pesanan', [RiwayatController::class, 'index'])->name('riwayat.pesanan');

// ===========================
// TRANSAKSI ADMIN
// ===========================
Route::get('/admin/pembayaran', [PembayaranController::class, 'adminIndex'])->name('admin.transaksi.index');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // **Tambahkan route ini untuk download PDF**
    Route::get('transaksi/download', [TransaksiController::class, 'download'])->name('transaksi.download');
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
Route::prefix('admin')->group(function () {
    Route::get('/transaksi/download', [TransaksiController::class, 'download'])->name('transaksi.download');
});
});
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/manual', function () {
        return view('admin.manual');
    })->name('manual');
    Route::post('/manual', [ManualTransaksiController::class, 'store'])->name('manual.store');

    Route::post('/admin/manual-transaksi/store', [ManualTransaksiController::class, 'store'])->name('admin.manual.store');

});