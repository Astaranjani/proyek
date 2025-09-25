<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $selectedItems = $request->input('produk', []);
        $cart = session()->get('cart', []);

        $selectedItems = array_map('strval', $selectedItems);
        $selectedCartItems = array_intersect_key($cart, array_flip($selectedItems));
        Log::info('Selected Cart Items at index:', ['items' => $selectedCartItems]);
        if (empty($selectedCartItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout!');
        }

        $total = 0;
        $item_details = [];

        foreach ($selectedCartItems as $id => $item) {
            $jumlah = $item['jumlah'] ?? 1;

            // Periksa stok barang
            $barang = Barang::find($item['barang_id']);
            if ($barang && $barang->stok < $jumlah) {
                return redirect()->back()->with('error', 'Stok untuk ' . $item['nama'] . ' tidak mencukupi!');
            }

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
        $user_id = Auth::id();

        $selectedCartItems = array_intersect_key($cart, array_flip($selectedItems));
        Log::info('Selected Cart Items at proses:', ['items' => $selectedCartItems]);

        $paymentResult = json_decode($request->payment_result, true);
        Log::info('Hasil pembayaran Midtrans:', $paymentResult);

        // Handle beli sekarang item if exists in session
        if (session()->has('beli_sekarang_item')) {
            $item = session('beli_sekarang_item');

            // Check stock before transaction
            $barang = Barang::find($item['barang_id']);
            if ($barang && $barang->stok < 1) {
                return redirect()->back()->with('error', 'Stok untuk ' . $item['nama_barang'] . ' tidak mencukupi!');
            }

            Transaksi::create([
                'user_id' => $item['user_id'],
                'nama_user' => $item['nama_user'],
                'nama_barang' => $item['nama_barang'],
                'barang_id' => $item['barang_id'],
                'total_harga' => $item['total_harga'],
                'status_pembayaran' => 'Lunas',
                'kode_transaksi' => $paymentResult['transaction_id'] ?? 'manual',
            ]);

            // Kurangi stok barang setelah transaksi sukses
            if ($barang) {
                $barang->stok -= 1;
                $barang->save();
            }

            session()->forget('beli_sekarang_item'); // Clear session after processing
        }

        if (empty($selectedCartItems)) {
            Log::warning('Tidak ada item yang dipilih saat proses pembayaran');
            return redirect()->route('dashboard')->with('error', 'Tidak ada item yang dipilih.');
        }

        foreach ($selectedCartItems as $item) {
            // Periksa stok barang sebelum membuat transaksi
            $barang = Barang::find($item['barang_id']);
            if ($barang && $barang->stok < ($item['jumlah'] ?? 1)) {
                return redirect()->back()->with('error', 'Stok untuk ' . $item['nama'] . ' tidak mencukupi!');
            }

            Transaksi::create([
                'user_id' => $user_id,
                'nama_user' => Auth::user()->name,
                'nama_barang' => $item['nama'],
                'barang_id' => $item['barang_id'],
                'total_harga' => $item['harga'] * ($item['jumlah'] ?? 1),
                'status_pembayaran' => 'Lunas',
                'kode_transaksi' => $paymentResult['transaction_id'] ?? 'manual',
            ]);

            // Kurangi stok barang
            if ($barang) {
                $barang->stok -= $item['jumlah'] ?? 1;
                $barang->save();
            }
        }

        // Hapus item yang sudah dibayar dari cart
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

        // Cek stok barang
        if ($barang->stok <= 0) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk pembelian ini!');
        }

        $user = Auth::user();

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
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Error creating Snap token: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat token Snap!');
        }

        // Simpan detail pembelian di session agar diproses setelah pembayaran sukses
        session(['beli_sekarang_item' => [
            'user_id' => $user->id,
            'nama_user' => $user->name,
            'barang_id' => $barang->id,
            'nama_barang' => $barang->nama,
            'total_harga' => $barang->harga,
        ]]);

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
        Log::info('Selected Cart Items at bayar:', ['items' => $selectedCartItems]);
        if (empty($selectedCartItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout!');
        }

        $user_id = Auth::id();

        foreach ($selectedCartItems as $item) {
            Transaksi::create([
                'user_id' => $user_id,
                'nama_user' => Auth::user()->name,
                'nama_barang' => $item['nama'],
                'barang_id' => $item['barang_id'],
                'total_harga' => $item['harga'] * ($item['jumlah'] ?? 1),
                'status_pembayaran' => 'Lunas',
                'kode_transaksi' => 'manual-' . Str::random(10),
            ]);
        }

        // Kurangi stok barang
        $barang = Barang::find($item['barang_id']);
        if ($barang) {
            $barang->stok -= $item['jumlah'] ?? 1;
            $barang->save();
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
        $userId = Auth::id();

        $pesananselesai = Transaksi::where('user_id', $userId)
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat', compact('pesananselesai'));
    }

    public function adminIndex()
    {
        $transaksi = Transaksi::with(['user', 'barang'])->get();
        $totalSemuaPembayaran = $transaksi->sum('total_harga');

        return view('admin.transaksi.index', compact('transaksi', 'totalSemuaPembayaran'));
    }

public function store(Request $request)
{
    $request->validate([
        'nama_pelanggan' => 'required|string|max:255',
        'nama_barang' => 'required|string|max:255',
        'total_pembayaran' => 'required|numeric|min:0',
        'status_pembayaran' => 'required|in:lunas,belum',
        'tanggal_transaksi' => 'required|date',
    ]);

    Transaksi::create([
        'user_id' => auth()->id() ?? 1, // atau bisa null/default
        'nama_user' => $request->nama_pelanggan,
        'barang_id' => 1, // sementara, jika tidak dipilih
        'nama_barang' => $request->nama_barang,
        'total_harga' => $request->total_pembayaran,
        'status_pembayaran' => $request->status_pembayaran,
        'created_at' => $request->tanggal_transaksi,
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
}

}

