<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua data transaksi beserta relasinya (user dan barang)
        $transaksi = Transaksi::with(['user', 'barang'])->get();
        
        // Kirim data ke view
        return view('admin.transaksi.index', compact('transaksi'));
    }
}
