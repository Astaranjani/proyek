<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use Illuminate\Support\Str;  // Tambahkan ini untuk UUID

class PembayaranController extends Controller
{
    public function index()
    {

        $cart = session()->get('cart', []);
        // dd($cart);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Hitung total dan buat item_details
        $total = 0;
        $item_details = [];

        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 1;
            $total += $item['harga'] * $jumlah;

            $item_details[] = [
                'id' => $item['barang_id'],
                'price' => $item['harga'],
                'quantity' => $jumlah,
                'name' => $item['nama'],
            ];
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => Str::uuid(),  // Ganti uniqid() dengan UUID
                'gross_amount' => $total,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        // Buat Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Error creating Snap token: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat token Snap!');
        }

        // Kirim ke view
        return view('pembayaran', compact('snapToken'));
    }

    public function proses(Request $request)
    {
        $cart = session()->get('cart', []);
        $user_id = auth()->user()->id;

        if (empty($cart)) {
            return redirect()->route('dashboard')->with('error', 'Keranjang kosong, tidak ada transaksi yang disimpan.');
        }

        foreach ($cart as $item) {
            try {
                // Validasi jika key 'barang_id' tidak ditemukan
                if (!isset($item['barang_id'])) {
                    return redirect()->route('dashboard')->with('error', 'Data barang tidak lengkap di keranjang.');
                }

                // Simpan transaksi ke database
                Transaksi::create([
                    'user_id'           => $user_id,
                    'barang_id'         => $item['barang_id'],
                    'total_pembayaran'  => $item['harga'] * $item['jumlah'],
                    'status_pembayaran' => 'Belum Lunas',
                ]);
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
            }
        }

        // Hapus session cart setelah transaksi sukses
        session()->forget('cart');

        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil disimpan ke database!');
    }
}