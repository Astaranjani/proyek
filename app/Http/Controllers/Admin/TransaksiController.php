<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
{
    // Ambil SEMUA transaksi LUNAS
    $transaksi = Transaksi::with(['user', 'barang'])
        ->where('status_pembayaran', 'Lunas')
        ->latest()
        ->get(); // 🔥 GET, BUKAN PAGINATE

    // TOTAL GLOBAL (SAMA DENGAN DASHBOARD)
    $totalSemuaPembayaran = Transaksi::where('status_pembayaran', 'Lunas')
        ->sum('total_harga');

    return view('admin.transaksi.index', compact(
        'transaksi',
        'totalSemuaPembayaran'
    ));
}

   public function destroy($id)
{
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->delete();

    return redirect()->route('admin.transaksi.index');
}


    public function download()
    {
        $transaksi = Transaksi::all();
        $totalSemuaPembayaran = $transaksi->sum('total_harga');

        $pdf = Pdf::loadView('admin.transaksi.pdf', [
            'transaksi' => $transaksi,
            'totalSemuaPembayaran' => $totalSemuaPembayaran
        ]);

        return $pdf->download('data-transaksi.pdf');
    }
}
