<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'address' => 'nullable|string|max:255',
        ]);

        // Simpan ke DB
        dd($validated);


        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

}