<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function laporanBarang()
    {
        $barangs = Barang::all();
        return view('admin.laporan.barang', compact('barangs'));
    }


    public function laporanBarangPDF()
    {
        $barangs = Barang::all();
        $pdf = Pdf::loadView('admin.laporan.barang', compact('barangs'));
        return $pdf->download('laporan.barang_pdf');
    }
}
