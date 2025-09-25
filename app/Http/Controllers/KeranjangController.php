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

        // 🔹 Cek stok barang
        if ($barang->stok <= 0) {
            return redirect()->back()->with('error', 'Stok barang habis, tidak bisa ditambahkan ke keranjang.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$barang->id])) {
            // Cek apakah jumlah di keranjang melebihi stok
            if ($cart[$barang->id]['jumlah'] + 1 > $barang->stok) {
                return redirect()->back()->with('error', 'Jumlah barang di keranjang melebihi stok tersedia.');
            }
            $cart[$barang->id]['jumlah'] += 1;
        } else {
            $cart[$barang->id] = [
                'barang_id' => $barang->id,
                'nama'      => $barang->nama,
                'harga'     => $barang->harga,
                'gambar'    => $barang->gambar,
                'jumlah'    => 1,
                'stok'      => $barang->stok, // simpan stok juga
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang!');
    }

    public function hapus(Request $request)
    {
        $barangId = $request->input('barang_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$barangId])) {
            unset($cart[$barangId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // 🔹 Validasi stok sebelum checkout
        foreach ($request->produk as $id) {
            $barang = Barang::find($id);
            if (!$barang || $barang->stok <= 0) {
                return redirect()->back()->with('error', 'Ada barang yang stoknya habis, checkout dibatalkan.');
            }
        }

        // Hitung total jumlah item secara aman
        $totalJumlah = 0;
        $totalHarga  = 0;

        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 0;
            $harga  = $item['harga'] ?? 0;

            $totalJumlah += $jumlah;
            $totalHarga  += $jumlah * $harga;
        }

        // Hapus isi keranjang
        session()->forget('cart');

        return view('pembayaran', compact('cart', 'totalJumlah', 'totalHarga'));
    }

    public function beliSekarang(Request $request)
    {
        $barang = Barang::findOrFail($request->product_id);

        // 🔹 Cek stok dulu
        if ($barang->stok <= 0) {
            return redirect()->back()->with('error', 'Stok barang habis, tidak bisa dibeli.');
        }

        $cart = [];
        $cart[$barang->id] = [
            'barang_id' => $barang->id,
            'nama'      => $barang->nama,
            'harga'     => $barang->harga,
            'gambar'    => $barang->gambar,
            'jumlah'    => 1,
            'stok'      => $barang->stok,
        ];

        session(['cart' => $cart]);

        return redirect()->route('pembayaran');
    }

    public function pembayaranSukses()
    {
        // Hapus isi keranjang dari session
        session()->forget('cart');
    }

    public function tambahKeranjang(Request $request, $id)
    {
        $produk = Barang::findOrFail($id);

        // 🔹 Cek stok
        if ($produk->stok <= 0) {
            return redirect()->back()->with('error', 'Stok produk habis.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['jumlah'] + 1 > $produk->stok) {
                return redirect()->back()->with('error', 'Jumlah barang di keranjang melebihi stok tersedia.');
            }
            $cart[$id]['jumlah'] += 1;
        } else {
            $cart[$id] = [
                'nama'   => $produk->nama,
                'harga'  => $produk->harga,
                'gambar' => $produk->gambar,
                'jumlah' => 1,
                'stok'   => $produk->stok,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id   = $request->barang_id;

        if (isset($cart[$id])) {
            $barang = Barang::find($id);

            if ($request->action === 'increase') {
                // 🔹 Cek stok
                if ($barang && $cart[$id]['jumlah'] + 1 > $barang->stok) {
                    return back()->with('error', 'Jumlah barang tidak boleh melebihi stok.');
                }
                $cart[$id]['jumlah'] = ($cart[$id]['jumlah'] ?? 1) + 1;
            } elseif ($request->action === 'decrease') {
                $cart[$id]['jumlah'] = max(1, ($cart[$id]['jumlah'] ?? 1) - 1);
            }
            session()->put('cart', $cart);
        }

        return back();
    }
}
