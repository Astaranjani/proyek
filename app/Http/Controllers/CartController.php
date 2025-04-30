<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class CartController extends Controller
{
    public function tambah(Request $request)
    {
        $barang = Barang::findOrFail($request->barang_id);

        $cart = session()->get('cart', []);
        $cart[$barang->id] = [
            "nama" => $barang->nama,
            "harga" => $barang->harga,
            "gambar" => $barang->gambar
        ];

        session()->put('cart', $cart);
        return redirect()->route('keranjang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('keranjang.index', compact('cart'));
    }
}
