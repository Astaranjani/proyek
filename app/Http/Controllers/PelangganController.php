<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class PelangganController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('pelanggan.dashboard', compact('barangs'));
    }
}
