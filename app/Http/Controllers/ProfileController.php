<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class ProfileController extends Controller
{
    // Menampilkan halaman form update profil
    public function edit()
    {
        return view('profile.edit'); // Pastikan file Blade ini ada
    }

    // Menyimpan data hasil update profil
    public function update(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string|max:10',
            'address' => 'required|string|max:255',
        ]);

        // Simpan ke database (contoh: user login)
        $user = Auth::user(); // Ambil pengguna yang diautentikasi
        $user->fill($validated); // Mengisi atribut dengan data yang divalidasi
        $user->save(); // Simpan perubahan ke database
        
        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}