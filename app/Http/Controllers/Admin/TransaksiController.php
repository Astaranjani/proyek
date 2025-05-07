<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use PDF;

class TransaksiController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        // Ambil semua data transaksi
        $transaksi = Transaksi::with('pelanggan')->orderBy('created_at', 'desc')->get();

        // Kirim data ke view
        return view('admin.transaksi.index', compact('transaksi'));
    }

    // Konfirmasi Transaksi
    public function konfirmasi($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 'Lunas';
        $transaksi->save();

        return redirect()->route('admin.transaksi.index')
                         ->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    // Cetak PDF Transaksi
    public function cetakPDF($id)
{
    $transaksi = Transaksi::findOrFail($id);

    // Buat view khusus untuk cetak PDF
    // $pdf = PDF::loadView('admin.transaksi.cetak', compact('transaksi'));

    // Download dengan nama file yang sesuai
    // return $pdf->download('transaksi_' . $transaksi->id . '.pdf');
}
}
