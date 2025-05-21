<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $selectedItems = $request->input('produk', []);
        $cart = session()->get('cart', []);

        // Pastikan selectedItems berisi string (karena key di cart kemungkinan string)
        $selectedItems = array_map('strval', $selectedItems);

        // Filter hanya item yang dipilih berdasarkan key (barang_id)
        $selectedCartItems = array_intersect_key($cart, array_flip($selectedItems));

        if (empty($selectedCartItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout!');
        }

        $total = 0;
        $item_details = [];

        foreach ($selectedCartItems as $id => $item) {
            $jumlah = $item['jumlah'] ?? 1;
            $total += $item['harga'] * $jumlah;

            $item_details[] = [
                'id' => $item['barang_id'],
                'price' => $item['harga'],
                'quantity' => $jumlah,
                'name' => $item['nama'],
            ];
        }

        session(['selected_items' => $selectedItems]);

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

        return view('pembayaran', [
        'snapToken' => $snapToken,
        'selectedCartItems' => $selectedCartItems,
        'total' => $total,
]);

    }

    public function show()
    {
        $user = Auth::user();
        return view('pembayaran', compact('user'));
    }

    public function proses(Request $request)
    {
        Log::info('proses method dipanggil');

        $cart = session()->get('cart', []);
        $selectedItems = session()->get('selected_items', []);
        $selectedItems = array_map('strval', $selectedItems);

        $user_id = auth()->user()->id;

        $selectedCartItems = array_intersect_key($cart, array_flip($selectedItems));

        if (empty($selectedCartItems)) {
            Log::warning('Tidak ada item yang dipilih saat proses pembayaran');
            return redirect()->route('dashboard')->with('error', 'Tidak ada item yang dipilih.');
        }

        $paymentResult = json_decode($request->payment_result, true);
        Log::info('Hasil pembayaran Midtrans:', $paymentResult);

        foreach ($selectedCartItems as $item) {
            Transaksi::create([
                'user_id' => $user_id,
                'barang_id' => $item['barang_id'],
                'total_harga' => $item['harga'] * ($item['jumlah'] ?? 1),
                'status_pembayaran' => 'Lunas',
                'kode_transaksi' => $paymentResult['transaction_id'] ?? 'manual',
            ]);
        }

        // Hapus hanya item yang sudah dibayar
        foreach ($selectedItems as $itemId) {
            unset($cart[$itemId]);
        }
        session(['cart' => $cart]);
        session()->forget('selected_items');

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
        $selectedItems = $request->input('produk', []);
        $cart = session()->get('cart', []);
        $selectedItems = array_map('strval', $selectedItems);

        $selectedCartItems = array_intersect_key($cart, array_flip($selectedItems));

        if (empty($selectedCartItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout!');
        }

        $user_id = auth()->user()->id;

        foreach ($selectedCartItems as $item) {
            Transaksi::create([
                'user_id' => $user_id,
                'barang_id' => $item['barang_id'],
                'total_harga' => $item['harga'] * ($item['jumlah'] ?? 1),
                'status_pembayaran' => 'Lunas',
                'kode_transaksi' => 'manual-' . Str::random(10),
            ]);
        }

        // Hapus hanya item yang sudah dibayar
        foreach ($selectedItems as $itemId) {
            unset($cart[$itemId]);
        }
        session(['cart' => $cart]);

        return redirect()->route('pembayaran.sukses')->with('success', 'Pembayaran berhasil!');
    }

    public function riwayat()
    {
        $userId = auth()->id();

        $pesananselesai = Transaksi::where('user_id', $userId)
                        ->where('status', 'selesai')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('riwayat', compact('pesananselesai'));
    }
}