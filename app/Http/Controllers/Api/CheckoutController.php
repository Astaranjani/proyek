<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    /**
     * Proses checkout dari cart ke order & order_items
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('barang')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang masih kosong.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Total semua harga
            $totalPrice = $cartItems->sum('total');

            // Buat order baru
            $order = Order::create([
                'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                'total_price' => $totalPrice,
                'status' => 'menunggu konfirmasi',
                'payment_status' => 'belum dibayar'
            ]);

            // Tambahkan ke order_items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $item->barang_id,
                    'quantity' => $item->quantity,
                    'price' => $item->barang->harga,
                    'subtotal' => $item->total,
                ]);
            }

            // Kosongkan cart setelah checkout
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil.',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Melihat semua order yang sudah dibuat oleh user login
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with('items.barang', 'payments')
            ->whereHas('items.barang', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
