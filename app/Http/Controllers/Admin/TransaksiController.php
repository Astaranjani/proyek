<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua data transaksi beserta relasinya (user dan barang)
        $transaksi = Transaksi::with(['user', 'barang'])->get();
        $totalSemuaPembayaran = $transaksi->sum('total_harga');
        // Kirim data ke view
        return view('admin.transaksi.index', compact('transaksi'));
    }
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
  public function download()
{
    $transaksi = Transaksi::all();
   $totalSemuaPembayaran = $transaksi->sum('total_harga');
 // pastikan ini dikirim

    $pdf = Pdf::loadView('admin.transaksi.pdf', [
        'transaksi' => $transaksi,
        'totalSemuaPembayaran' => $totalSemuaPembayaran
    ]);

    return $pdf->download('data-transaksi.pdf');
}

}