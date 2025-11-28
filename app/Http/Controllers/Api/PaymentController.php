<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }

    /**
     * Create Midtrans payment transaction
     */
    public function create(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Validate request
            $validated = $request->validate([
                'order_id' => 'required|string',
                'gross_amount' => 'required|numeric|min:10000',
                'payment_type' => 'nullable|string',
                'customer_details' => 'nullable|array',
                'item_details' => 'nullable|array',
            ]);

            // Build transaction parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $validated['order_id'],
                    'gross_amount' => $validated['gross_amount'],
                ],
                'customer_details' => $validated['customer_details'] ?? [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '08123456789',
                ],
                'item_details' => $validated['item_details'] ?? [
                    [
                        'id' => 'item1',
                        'price' => $validated['gross_amount'],
                        'quantity' => 1,
                        'name' => 'Product Purchase',
                    ],
                ],
            ];

            // Optional: Enable specific payment methods
            if (!empty($validated['payment_type'])) {
                $params['enabled_payments'] = [$validated['payment_type']];
            }

            // Get Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $validated['order_id'],
                'gross_amount' => $validated['gross_amount'],
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Payment creation error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Payment creation failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function notification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            \Log::info('Midtrans Notification: ' . json_encode([
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]));

            // Handle different transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Payment success
                    // Update order status in database
                } else if ($fraudStatus == 'challenge') {
                    // Payment in review
                }
            } else if ($transactionStatus == 'settlement') {
                // Payment success
                // Update order status in database
            } else if ($transactionStatus == 'pending') {
                // Payment pending
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {
                // Payment failed/cancelled
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            \Log::error('Notification error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);

            return response()->json([
                'order_id' => $orderId,
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
                'payment_type' => $status->payment_type,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Status check error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}