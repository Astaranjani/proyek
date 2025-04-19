<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class PelangganController extends Controller
{
    public function index()
    {
    $barangs = Barang::latest()->get(); // <- ini harus ada
    return view('dashboard', compact('barangs')); // <- pastikan file view-nya adalah dashboard.blade.php
    }
}
