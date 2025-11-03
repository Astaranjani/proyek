<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Voucher;

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
    $notifikasiStokHabis = null;
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

    // 🔹 Tambahkan ini supaya $barangs tersedia untuk form voucher
   $barangs = Barang::all();
    $vouchers = Voucher::with('barangs')->orderBy('created_at', 'desc')->get();

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
        'customData',
        'barangs', 
        'vouchers',
        'notifikasiStokHabis'// sekarang sudah terdefinisi
    ));
}


    public function laporanBarang()
    {
        $barangs = Barang::all();
        
        // 🔹 Cek barang yang stoknya habis
$barangStokHabis = Barang::where('stok', '<=', 0)->pluck('nama')->toArray();

// 🔹 Buat notifikasi jika ada stok habis
$notifikasiStokHabis = '';
if (!empty($barangStokHabis)) {
    $notifikasiStokHabis = 'Perhatian! Barang berikut stoknya habis: ' . implode(', ', $barangStokHabis);
}

        return view('admin.laporan.barang', compact('barangs'));
    }

   public function storeVoucher(Request $request)
{
    $request->validate([
        'kode' => 'required|unique:vouchers,kode',
        'diskon' => 'required|integer|min:1|max:100',
        'barang_ids' => 'required|array',
        'tanggal_mulai' => 'nullable|date',
        'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        'batas_penggunaan' => 'nullable|integer|min:1',
    ]);

    // 🔹 Tambahkan setelah $request->validate([...]);
$barangTidakTersedia = Barang::whereIn('id', $request->barang_ids)
    ->where('stok', '<=', 0)
    ->pluck('nama')
    ->toArray();

if (!empty($barangTidakTersedia)) {
    return redirect()->back()->withErrors([
        'barang_ids' => 'Tidak dapat membuat voucher untuk barang yang stoknya habis: ' . implode(', ', $barangTidakTersedia)
    ])->withInput();
}

    $voucher = Voucher::create([
        'kode' => $request->kode,
        'diskon' => $request->diskon,
        'aktif' => true,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_berakhir' => $request->tanggal_berakhir,
        'batas_penggunaan' => $request->batas_penggunaan,
    ]);

    $voucher->barangs()->sync($request->barang_ids);

    return redirect()->route('admin.dashboard')->with('success', 'Voucher berhasil ditambahkan!');
}

public function createVoucher()
{
    $barangs = Barang::all(); // ambil semua produk
    return view('admin.voucher.create', compact('barangs'));
}

// Edit voucher
public function editVoucher(Voucher $voucher)
{
    $barangs = Barang::all();
    $selectedBarang = $voucher->barangs->pluck('id')->toArray();
    return view('admin.voucher.edit', compact('voucher', 'barangs', 'selectedBarang'));
}

// Update voucher
public function updateVoucher(Request $request, Voucher $voucher)
{
    $request->validate([
        'kode' => 'required|unique:vouchers,kode,' . $voucher->id,
        'diskon' => 'required|integer|min:1|max:100',
        'barang_ids' => 'required|array',
        'tanggal_mulai' => 'nullable|date',
        'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        'batas_penggunaan' => 'nullable|integer|min:1',
    ]);

// 🔹 Tambahkan setelah $request->validate([...]);
$barangTidakTersedia = Barang::whereIn('id', $request->barang_ids)
    ->where('stok', '<=', 0)
    ->pluck('nama')
    ->toArray();

if (!empty($barangTidakTersedia)) {
    return redirect()->back()->withErrors([
        'barang_ids' => 'Tidak dapat memperbarui voucher untuk barang yang stoknya habis: ' . implode(', ', $barangTidakTersedia)
    ])->withInput();
}

    $voucher->update([
        'kode' => $request->kode,
        'diskon' => $request->diskon,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_berakhir' => $request->tanggal_berakhir,
        'batas_penggunaan' => $request->batas_penggunaan,
    ]);

    $voucher->barangs()->sync($request->barang_ids);

    return redirect()->route('admin.dashboard')->with('success', 'Voucher berhasil diperbarui!');
}
public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        // Hapus relasi ke tabel pivot voucher_barang
        DB::table('voucher_barang')->where('voucher_id', $voucher->id)->delete();

        // Hapus voucher
        $voucher->delete();

        return redirect()->back()->with('success', 'Voucher berhasil dihapus.');
    }


}
