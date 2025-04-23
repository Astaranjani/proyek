<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // Menampilkan semua barang dengan paginasi
    public function index()
    {
        $barangs = Barang::latest()->paginate(10); // Paginate 10 data per halaman
        return view('admin.barang.index', compact('barangs'));
    }

    // Menampilkan form tambah barang
    public function create()
    {
        return view('admin.barang.create');
    }

    // Menyimpan data barang baru
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

    // Menampilkan form edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    // Update data barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok'  => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $barang = Barang::findOrFail($id);
        $data = $request->only(['nama', 'harga', 'stok', 'deskripsi']);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('gambar-barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
