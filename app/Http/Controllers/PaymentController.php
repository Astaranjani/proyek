<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function midtransCallback(Request $request, MidtransService $midtransService)
    {
        if ($midtransService->isSignatureKeyVerified()) {
            $order = $midtransService->getOrder();

            if ($midtransService->getStatus() == 'success') {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                ]);

                $lastPayment = $order->payments()->latest()->first();
                $lastPayment->update([
                    'status' => 'PAID',
                    'paid_at' => now(),
                ]);
            }

            if ($midtransService->getStatus() == 'pending') {
                // lakukan sesuatu jika pembayaran masih pending, seperti mengirim notifikasi ke customer
                // bahwa pembayaran masih pending dan harap selesai pembayarannya
            }

            if ($midtransService->getStatus() == 'expire') {
                // lakukan sesuatu jika pembayaran expired, seperti mengirim notifikasi ke customer
                // bahwa pembayaran expired dan harap melakukan pembayaran ulang
            }

            if ($midtransService->getStatus() == 'cancel') {
                // lakukan sesuatu jika pembayaran dibatalkan
            }

            if ($midtransService->getStatus() == 'failed') {
                // lakukan sesuatu jika pembayaran gagal
            }

            return response()
                ->json([
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
