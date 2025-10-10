<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort');
        $kategori = $request->input('kategori');

        // Ambil semua barang beserta voucher yang masih berlaku
        $semuaProdukQuery = Barang::with(['vouchers' => function ($q) {
            $q->where('aktif', true)
              ->where(function ($q2) {
                  $q2->whereNull('masa_berlaku')
                     ->orWhere('masa_berlaku', '>=', Carbon::now());
              })
              ->where(function ($q3) {
                  $q3->whereNull('batas_penggunaan')
                     ->orWhereColumn('jumlah_digunakan', '<', 'batas_penggunaan');
              });
        }]);

        // Filter pencarian
        if ($search) {
            $semuaProdukQuery->where('nama', 'like', "%{$search}%");
        }

        // Filter kategori
        if ($kategori) {
            $semuaProdukQuery->where('kategori', $kategori);
        }

        // Urutan
        switch ($sort) {
            case 'price_asc':
                $semuaProdukQuery->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $semuaProdukQuery->orderBy('harga', 'desc');
                break;
            case 'latest':
            default:
                $semuaProdukQuery->latest();
                break;
        }

        $semuaProduk = $semuaProdukQuery->paginate(12)->withQueryString();

        return view('dashboard', compact('semuaProduk'));
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
    $kategori = $request->input('kategori'); // Tambahan kategori

    // Produk terbaru (tetap sama, filter search + kategori jika ada)
    // Tambahkan ->with('vouchers') agar voucher bisa dicek di Blade
$produkTerbaru = Barang::query()
    ->with('vouchers') // tambah relasi voucher
    ->when($search, function($query, $search) {
        $query->where('nama', 'like', "%{$search}%");
    })
    ->when($kategori, function($query, $kategori) {
        $query->where('kategori', $kategori);
    })
    ->latest()
    ->take(8)
    ->get();

// Semua produk: query builder
$semuaProdukQuery = Barang::with('vouchers'); // tambah relasi voucher

// Search
if ($search) {
    $semuaProdukQuery->where('nama', 'like', "%{$search}%");
}

// Filter kategori
if ($kategori) {
    $semuaProdukQuery->where('kategori', $kategori);
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
        $semuaProdukQuery->latest();
}

// Pagination + pertahankan query string (search, sort, kategori)
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
