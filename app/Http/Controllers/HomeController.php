<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class HomeController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest()->get(); // Ambil semua barang
        return view('dashboard', compact('barangs'));
    }
}

// class HomeController extends Controller
// {
//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         $this->middleware('auth');
//     }

//     /**
//      * Show the application dashboard.
//      *
//      * @return \Illuminate\Contracts\Support\Renderable
//      */
//     public function index()
//     {
//         return view('dashboard');
//     }
// }
