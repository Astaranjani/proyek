<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    // Mengubah status pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
    public function index(Request $request)
{
    $query = Order::with('user');

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $orders = $query->get();

    return view('admin.orders.index', compact('orders'));
}
}
