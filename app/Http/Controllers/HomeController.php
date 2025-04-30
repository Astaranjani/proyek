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
    $barangs = Barang::latest()->take(8)->get(); // Ambil 8 produk terbaru
    return view('dashboard', compact('barangs'));
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

// class HomeController extends Controller
// {
//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         $this->middleware('auth');
//     }

//     /**
//      * Show the application dashboard.
//      *
//      * @return \Illuminate\Contracts\Support\Renderable
//      */
//     public function index()
//     {
//         return view('dashboard');
//     }
// }
