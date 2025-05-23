<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;

class RiwayatController extends Controller
{
      public function index()
    {
        // Ambil data transaksi milik user yang sedang login
        $orders = Transaksi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        $orders = Transaksi::with(['user', 'barang'])->where('user_id', Auth::id())->get();

        return view('riwayat', compact('orders'));
    }
}
