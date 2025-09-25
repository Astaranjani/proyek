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

    public function riwayatPesanan()
    {
        // logika untuk menampilkan riwayat pesanan
        return view('Riwayat Pesanan'); // ganti sesuai nama view kamu
    }

  public function dashboard(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort');

    // Produk terbaru (tetap sama, filter search jika ada)
    $produkTerbaru = Barang::query()
        ->when($search, function($query, $search) {
            $query->where('nama', 'like', "%{$search}%");
        })
        ->latest()
        ->take(8)
        ->get();

    // Semua produk: query builder
    $semuaProdukQuery = Barang::query();

    // Search
    if ($search) {
        $semuaProdukQuery->where('nama', 'like', "%{$search}%");
    }

    // Sort
    switch($sort) {
        case 'price_asc':
            $semuaProdukQuery->orderBy('harga', 'asc');
            break;
        case 'price_desc':
            $semuaProdukQuery->orderBy('harga', 'desc');
            break;
        default:
            $semuaProdukQuery->latest(); // default urut terbaru
    }

    // Pagination + pertahankan query string (search & sort)
    $semuaProduk = $semuaProdukQuery->paginate(12)->withQueryString();

    return view('dashboard', compact('produkTerbaru', 'semuaProduk'));
}



    public function searchProduk(Request $request)
    {
        $search = $request->input('query');

        $produk = Barang::when($search, function($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->latest()->take(10)->get();

        // Kembalikan data dalam format JSON
        return response()->json($produk);
    }
}
