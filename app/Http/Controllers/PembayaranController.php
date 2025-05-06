<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
class PembayaranController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Hitung total
        $total = 0;
        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 1;
            $total += $item['harga'] * $jumlah;
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');  // Menggunakan config Midtrans
        Config::$clientKey = config('services.midtrans.client_key');  // Menggunakan config Midtrans
        Config::$isProduction = config('services.midtrans.is_production'); // Sesuaikan dengan mode production atau sandbox
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),  // Menghasilkan ID order yang unik
                'gross_amount' => $total,  // Total jumlah yang harus dibayar
            ],
            'customer_details' => [
                'first_name' => 'Pelanggan',
                'email' => 'pelanggan@example.com',
            ],
        ];

        // Buat Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);  // Membuat token untuk pembayaran
        } catch (\Exception $e) {
            Log::error('Error creating Snap token: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat token Snap!');
        }
        

        // Kirim ke view
        return view('pembayaran', compact('snapToken'));
    }
    public function proses(Request $request)
    {
        // Proses setelah pembayaran berhasil (logika simpan database bisa ditambahkan di sini)
        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil!');
    }
}
