<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // Menampilkan semua barang
    public function index()
    {
        $barangs = Barang::all();
        return view('admin.barang.index', compact('barangs'));
    }

    // Menampilkan form tambah barang
    public function create()
    {
        return view('admin.barang.create');
    }

    // Menyimpan data barang
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok'  => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $data = $request->only(['nama', 'harga', 'stok', 'deskripsi']);

            if ($request->hasFile('gambar')) {
                $data['gambar'] = $request->file('gambar')->store('gambar-barang', 'public');
            }

            Barang::create($data);

            return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage())->withInput();
        }
    }
}
