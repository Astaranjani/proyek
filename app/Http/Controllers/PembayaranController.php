<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Models\Barang;

class PembayaranController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

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

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => (string) Str::uuid(),
                'gross_amount' => $total,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Error creating Snap token: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat token Snap!');
        }

        return view('pembayaran', compact('snapToken'));
    }

    public function proses(Request $request)
{
    Log::info('proses method dipanggil');

    $cart = session()->get('cart', []);
    $user_id = auth()->user()->id;

    if (empty($cart)) {
        Log::warning('Keranjang kosong saat proses pembayaran');
        return redirect()->route('dashboard')->with('error', 'Keranjang kosong.');
    }

    $paymentResult = json_decode($request->payment_result, true);
    Log::info('Hasil pembayaran Midtrans:', $paymentResult);

    foreach ($cart as $item) {
        Transaksi::create([
            'user_id' => $user_id,
            'barang_id' => $item['barang_id'],
            'total_pembayaran' => $item['harga'] * $item['jumlah'],
            'status_pembayaran' => 'Lunas',
            'kode_transaksi' => $paymentResult['transaction_id'] ?? 'manual',
        ]);
    }

    session()->forget('cart');

    return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil!');
}


    public function beliSekarang(Request $request)
    {
        $barang = Barang::findOrFail($request->product_id);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $barang->harga,
            ],
            'item_details' => [[
                'id' => $barang->id,
                'price' => $barang->harga,
                'quantity' => 1,
                'name' => $barang->nama,
            ]],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Error creating Snap token: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat token Snap!');
        }

       return view('pembayaran', [
        'snapToken' => $snapToken,
        'barang' => $barang,
    ]);
}
public function bayar(Request $request)
{
    // Validasi dan proses pembayaran, misal menggunakan payment gateway atau manual

    // Simulasi pembayaran sukses
    // ... logika menyimpan transaksi ke database

    // Hapus keranjang dari session
    session()->forget('cart');

    return redirect()->route('pembayaran.sukses')->with('success', 'Pembayaran berhasil!');
}

}
