<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Payment;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $items = [];
        $totalHarga = 0;

        foreach ($request->barang_id as $id => $jumlah) {
            $barang = Barang::with('vouchers')->findOrFail($id);

            // cek ada voucher atau tidak
            if ($barang->vouchers->count() > 0) {
                $voucher = $barang->vouchers->first();
                $hargaAkhir = $barang->harga * (1 - $voucher->diskon / 100);
            } else {
                $hargaAkhir = $barang->harga;
            }

            $subtotal = $hargaAkhir * $jumlah;

            // data item untuk Midtrans
            $items[] = [
                'id'       => $barang->id,
                'price'    => $hargaAkhir,
                'quantity' => $jumlah,
                'name'     => $barang->nama,
            ];

            $totalHarga += $subtotal;
        }

        // data transaksi ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $totalHarga, // harga total setelah diskon
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email'      => auth()->user()->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('pembayaran', compact('snapToken', 'items', 'totalHarga'));
    }
}
