<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;

class DashboardAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Ambil data pengguna yang terdaftar
        $jumlahPengguna = User::count();

        // Ambil jumlah barang dan total stok
        $jumlahBarang = Barang::count();
        $totalStok = Barang::sum('stok');

        // Kirim ke view dashboard-admin
        return view('admin.dashboard-admin', compact('jumlahBarang', 'totalStok', 'jumlahPengguna'));
    }
    public function laporanBarang()
    {
        $barangs = Barang::all();
        return view('admin.laporan.barang', compact('barangs'));
    }
    
}
