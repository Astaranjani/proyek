<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;


class KeranjangController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('keranjang', compact('cart'));
    }

    public function tambah(Request $request)
    {
        $barang = Barang::findOrFail($request->barang_id);

        $cart = session()->get('cart', []);

        // Cek apakah barang sudah ada di keranjang
        if (isset($cart[$barang->id])) {
            $cart[$barang->id]['jumlah'] += 1; // Tambah jumlah
        } else {
            $cart[$barang->id] = [
                'barang_id' => $barang->id, // <- Tambahkan ini
                'nama' => $barang->nama,
                'harga' => $barang->harga,
                'gambar' => $barang->gambar,
                'jumlah' => 1, // Tambahkan jumlah
            ];
        }

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

        // Hitung total jumlah item secara aman
        $totalJumlah = 0;
        $totalHarga = 0;

        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 0;
            $harga = $item['harga'] ?? 0;

            $totalJumlah += $jumlah;
            $totalHarga += $jumlah * $harga;
        }

        return view('pembayaran', compact('cart', 'totalJumlah', 'totalHarga'));
        return view('pembayaran', compact('snapToken', 'cart', 'total'));
    }
   public function beliSekarang(Request $request)
{
    $barang = Barang::findOrFail($request->product_id);

    $cart = [];
    $cart[$barang->id] = [
        'barang_id' => $barang->id,
        'nama' => $barang->nama,
        'harga' => $barang->harga,
        'gambar' => $barang->gambar,
        'jumlah' => 1,
    ];

    session(['cart' => $cart]);

    // Tambahkan ini untuk debug
    return redirect()->route('pembayaran');
}
}
