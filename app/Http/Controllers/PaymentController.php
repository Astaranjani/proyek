<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    public function midtransCallback(Request $request, MidtransService $midtransService)
    {
        // Pastikan signature key dari Midtrans valid
        if ($midtransService->isSignatureKeyVerified()) {
            $order = $midtransService->getOrder();

            if ($midtransService->getStatus() == 'success') {
                // Simpan transaksi ke database
                Transaksi::create([
                    'user_id'           => $order->user_id, // pastikan ini ID user yang benar
                    'barang_id'         => $order->barang_id, // pastikan ini ID barang yang benar
                    'total_harga'       => $order->gross_amount,
                    'status_pembayaran' => 'Lunas',
                ]);

                // Update status pembayaran di order
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil diproses',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}
