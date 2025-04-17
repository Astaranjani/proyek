<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Cek apakah user yang login memiliki role admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Jika role adalah admin, tampilkan halaman dashboard admin
        return view('admin.dashboard-admin');
    }
}
