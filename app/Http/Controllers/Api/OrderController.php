<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Checkout: memindahkan data dari cart ke order & order items
     */
    public function checkout(Request $request)
    {
        $userId = Auth::id();

        // Ambil semua item dari cart user
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong!']);
        }

        DB::beginTransaction();
        try {
            $totalPrice = $cartItems->sum('total');

            // Buat order baru
            $order = Order::create([
                'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                'total_price' => $totalPrice,
                'status' => 'menunggu',
                'payment_status' => 'belum dibayar'
            ]);

            // Simpan order items berdasarkan isi cart
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $item->barang_id,
                    'quantity' => $item->quantity,
                    'price' => $item->barang->harga,
                    'subtotal' => $item->total,
                ]);
            }

            // Hapus cart setelah checkout
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil!',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan semua order user
     */
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::with('items.barang', 'payments')
            ->whereHas('items', function ($q) use ($userId) {
                $q->whereHas('barang', function ($q2) use ($userId) {
                    $q2->where('user_id', $userId);
                });
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
