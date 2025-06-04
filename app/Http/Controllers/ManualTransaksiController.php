<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class ManualTransaksiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'total_pembayaran' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|string|max:255',
        ]);

        Transaksi::create([
            'user_id' => auth()->id() ?? 1, // default user_id
            'nama_user' => $request->nama_pelanggan,
            'barang_id' => 1, // sementara jika tidak dipilih
            'nama_barang' => $request->nama_barang,
            'total_harga' => $request->total_pembayaran,
           'status_pembayaran' => ucfirst(strtolower($request->status_pembayaran)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    }
}
