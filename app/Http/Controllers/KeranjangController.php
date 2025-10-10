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
        $barang = Barang::with('vouchers')->findOrFail($request->barang_id);

        // Cek stok barang
        if ($barang->stok <= 0) {
            return redirect()->back()->with('error', 'Stok barang habis, tidak bisa ditambahkan ke keranjang.');
        }

        $cart = session()->get('cart', []);

        // Hitung diskon (snapshot untuk konsistensi)
        $harga_asli = $barang->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;
        if ($barang->vouchers->count() > 0) {
            $voucher = $barang->vouchers->first();
            $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
            $diskon_persen = $voucher->diskon;
        }

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
                'harga'     => $harga_asli,  // Harga asli untuk line-through
                'harga_diskon' => $harga_diskon,  // Harga setelah diskon
                'diskon_persen' => $diskon_persen,  // Persen diskon (0 jika tidak ada)
                'gambar'    => $barang->gambar,
                'jumlah'    => 1,
                'stok'      => $barang->stok,
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

        if (empty($request->produk) || empty($cart)) {
            return redirect()->back()->with('error', 'Tidak ada item terpilih untuk checkout!');
        }

        // Validasi stok untuk item terpilih
        foreach ($request->produk as $id) {
            if (!isset($cart[$id])) continue;
            $barang = Barang::find($id);
            $item = $cart[$id];
            if (!$barang || $barang->stok < $item['jumlah']) {
                return redirect()->back()->with('error', "Stok barang '{$item['nama']}' tidak mencukupi (stok: {$barang->stok}, dibutuhkan: {$item['jumlah']}).");
            }
        }

        // Hitung total hanya untuk item terpilih (dengan diskon)
        $totalJumlah = 0;
        $totalHarga = 0;
        $selectedCart = [];  // Hanya item terpilih untuk view pembayaran

        foreach ($request->produk as $id) {
            if (isset($cart[$id])) {
                $item = $cart[$id];
                $selectedCart[$id] = $item;
                $totalJumlah += $item['jumlah'];
                $totalHarga += $item['jumlah'] * $item['harga_diskon'];  // Pakai harga diskon!
            }
        }

        // Hapus hanya item terpilih dari cart (sisanya tetap)
        foreach ($request->produk as $id) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
            }
        }
        session()->put('cart', $cart);

        return view('pembayaran', compact('selectedCart', 'totalJumlah', 'totalHarga'));
    }

    public function beliSekarang(Request $request)
    {
        $barang = Barang::with('vouchers')->findOrFail($request->product_id);

        // Cek stok dulu
        if ($barang->stok <= 0) {
            return redirect()->back()->with('error', 'Stok barang habis, tidak bisa dibeli.');
        }

        // Hitung diskon
        $harga_asli = $barang->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;
        if ($barang->vouchers->count() > 0) {
            $voucher = $barang->vouchers->first();
            $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
            $diskon_persen = $voucher->diskon;
        }

        $cart = [];
        $cart[$barang->id] = [
            'barang_id' => $barang->id,
            'nama'      => $barang->nama,
            'harga'     => $harga_asli,
            'harga_diskon' => $harga_diskon,
            'diskon_persen' => $diskon_persen,
            'gambar'    => $barang->gambar,
            'jumlah'    => 1,
            'stok'      => $barang->stok,
        ];

        session(['cart' => $cart]);

        return redirect()->route('pembayaran');
    }

    public function pembayaranSukses()
    {
        // Hapus isi keranjang dari session (jika diperlukan, sesuaikan)
        session()->forget('cart');
    }

    public function tambahKeranjang(Request $request, $id)
    {
        $produk = Barang::with('vouchers')->findOrFail($id);

        // Cek stok
        if ($produk->stok <= 0) {
            return redirect()->back()->with('error', 'Stok produk habis.');
        }

        $cart = session()->get('cart', []);

        // Hitung diskon
        $harga_asli = $produk->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;
        if ($produk->vouchers->count() > 0) {
            $voucher = $produk->vouchers->first();
            $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
            $diskon_persen = $voucher->diskon;
        }

        if (isset($cart[$id])) {
            if ($cart[$id]['jumlah'] + 1 > $produk->stok) {
                return redirect()->back()->with('error', 'Jumlah barang di keranjang melebihi stok tersedia.');
            }
            $cart[$id]['jumlah'] += 1;
        } else {
            $cart[$id] = [
                'nama'   => $produk->nama,
                'harga'  => $harga_asli,
                'harga_diskon' => $harga_diskon,
                'diskon_persen' => $diskon_persen,
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
    $barang = Barang::with('vouchers')->find($id);

    if ($barang) {
        // Hitung ulang diskon jika voucher masih berlaku
        $harga_asli = $barang->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;

        if ($barang->vouchers->count() > 0) {
            $voucher = $barang->vouchers
                ->filter(fn($v) =>
                    $v->aktif &&
                    (!$v->masa_berlaku || \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($v->masa_berlaku))) &&
                    (!$v->batas_penggunaan || $v->jumlah_digunakan < $v->batas_penggunaan)
                )
                ->first();

            if ($voucher) {
                $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
                $diskon_persen = $voucher->diskon;
            }
        }

        // Update jumlah
        if ($request->action === 'increase') {
            if ($cart[$id]['jumlah'] + 1 > $barang->stok) {
                return back()->with('error', 'Jumlah barang tidak boleh melebihi stok.');
            }
            $cart[$id]['jumlah']++;
        } elseif ($request->action === 'decrease') {
            $cart[$id]['jumlah'] = max(1, $cart[$id]['jumlah'] - 1);
        }

        // Update harga_diskon & diskon_persen
        $cart[$id]['harga_diskon'] = $harga_diskon;
        $cart[$id]['diskon_persen'] = $diskon_persen;
        session()->put('cart', $cart);
    }
}


        return back();
    }
}
