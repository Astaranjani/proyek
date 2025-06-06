<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Jumlah data
        $jumlahPengguna = User::count();
        $jumlahBarang = Barang::count();
        $totalStok = Barang::sum('stok');

        // Total pemasukan bulan ini
        $pemasukan = DB::table('transaksis')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');

        // Total pemasukan bulan sebelumnya
        $pemasukanSebelumnya = DB::table('transaksis')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_harga');

        // Total semua pemasukan
        $totalSemuaPembayaran = DB::table('transaksis')->sum('total_harga');

        // Chart: Pemasukan per hari sepanjang tahun
        $pemasukanBulan = DB::table('transaksis')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        // Chart: Pemasukan 7 hari terakhir
        $pemasukanMinggu = DB::table('transaksis')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        // Chart: Pemasukan 30 hari terakhir
        $pemasukanHari = DB::table('transaksis')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        // Chart: Data custom range dari form filter
        $customData = [];
        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $customData = DB::table('transaksis')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as total')
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->pluck('total', 'tanggal');
        }

        return view('admin.dashboard-admin', compact(
            'jumlahPengguna',
            'jumlahBarang',
            'totalStok',
            'pemasukan',
            'pemasukanSebelumnya',
            'totalSemuaPembayaran',
            'pemasukanBulan',
            'pemasukanMinggu',
            'pemasukanHari',
            'customData'
        ));
    }

    public function laporanBarang()
    {
        $barangs = Barang::all();
        return view('admin.laporan.barang', compact('barangs'));
    }
}
