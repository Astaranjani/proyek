<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
{
    if (auth()->user()->role !== 'user') {
        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
    }

    $produkTerbaru = Barang::latest()->take(6)->get(); // Ambil 6 produk terbaru
    $semuaProduk = Barang::latest()->get(); // Ambil semua produk

    return view('dashboard', compact('produkTerbaru', 'semuaProduk'));
}


    public function produk()
    {
        $barangs = Barang::all();
        return view('produk', compact('barangs'));
    }

    public function detail($id)
    {
        $barang = Barang::findOrFail($id);
        return view('detail', compact('barang'));
    }
}