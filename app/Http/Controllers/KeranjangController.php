<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class KeranjangController extends Controller
{
    public function tambah(Request $request)
    {
        // Ambil barang berdasarkan ID
        $barang = Barang::findOrFail($request->barang_id);

        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        // Tambahkan atau timpa barang berdasarkan ID
        $cart[$barang->id] = [
            'nama' => $barang->nama,
            'harga' => $barang->harga,
            'gambar' => $barang->gambar,
        ];

        // Simpan kembali ke session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang!');
    }
    public function hapus(Request $request)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$request->barang_id])) {
        unset($cart[$request->barang_id]);
        session()->put('cart', $cart);
    }

    return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
}
public function checkout()
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Keranjang masih kosong!');
    }

    return view('keranjang.checkout', compact('cart'));
}

}
