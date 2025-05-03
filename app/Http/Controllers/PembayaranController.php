<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran');
    }

    public function proses(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'metode' => 'required|in:,cod,bayar,',
        ]);

        // Simpan data transaksi ke database jika sudah punya model Transaksi

        // Kosongkan keranjang
        session()->forget('cart');

        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil! Pesanan sedang diproses.');
    }
}
