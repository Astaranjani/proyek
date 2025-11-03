<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Proses pembayaran untuk sebuah pesanan.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Cegah pembayaran ganda
        if ($order->payment_status === 'dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah dibayar sebelumnya.'
            ], 400);
        }

        // Buat record pembayaran
        $payment = Payment::create([
            'payment_code' => 'PAY-' . strtoupper(Str::random(8)),
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => 'berhasil', // Bisa diubah ke 'pending' kalau pakai gateway
        ]);

        // Update status order
        $order->update([
            'payment_status' => 'dibayar',
            'status' => 'diproses'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses.',
            'payment' => $payment,
            'order' => $order
        ]);
    }

    /**
     * Cek status pembayaran berdasarkan Order ID
     */
    public function checkStatus($orderId)
    {
        $order = Order::with('payments')->where('id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'order_status' => $order->status,
            'payments' => $order->payments
        ]);
    }
}
