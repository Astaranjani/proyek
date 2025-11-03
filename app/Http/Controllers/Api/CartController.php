<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Exception;

class CartController extends Controller
{
    /**
     * 🔹 Ambil semua item keranjang user
     */
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $cartItems = Cart::with('barang')
            ->where('user_id', $userId)
            ->get();

        $formattedCart = $cartItems->map(function ($item) {
            if (!$item->barang) {
                return null; // skip jika barang sudah dihapus
            }
            return [
                'id' => $item->id,
                'barang_id' => $item->barang->id,
                'nama' => $item->barang->nama,
                'harga' => $item->barang->harga,
                'gambar' => $item->barang->gambar,
                'quantity' => $item->quantity,
                'subtotal' => $item->barang->harga * $item->quantity,
            ];
        })->filter(); // hilangkan null data

        return response()->json([
            'data' => $formattedCart,
            'total' => $formattedCart->sum('subtotal'),
        ]);
    }

    /**
     * 🔹 Tambah barang ke keranjang
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        try {
            $barang = Barang::findOrFail($request->barang_id);

            $cart = Cart::where('user_id', $userId)
                ->where('barang_id', $barang->id)
                ->first();

            if ($cart) {
                $cart->increment('quantity', $request->quantity);
            } else {
                $cart = Cart::create([
                    'user_id' => $userId,
                    'barang_id' => $barang->id,
                    'quantity' => $request->quantity,
                ]);
            }

            return response()->json([
                'message' => 'Barang berhasil ditambahkan ke keranjang!',
                'data' => $cart,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan barang ke keranjang.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Update jumlah barang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Barang tidak ditemukan di keranjang.'], 404);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'message' => 'Jumlah barang berhasil diperbarui.',
            'data' => $cart,
        ]);
    }

    /**
     * 🔹 Hapus barang dari keranjang
     */
    public function destroy($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Barang tidak ditemukan di keranjang.'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Barang berhasil dihapus dari keranjang.']);
    }

    /**
     * 🔹 Hapus semua item di keranjang (clear all)
     */
    public function clear()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        Cart::where('user_id', $userId)->delete();

        return response()->json(['message' => 'Semua barang di keranjang telah dihapus.']);
    }
}
