<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Menampilkan halaman form update profil
    public function edit()
    {
        return view('profile.edit');
    }

    // Menyimpan data hasil update profil
    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:15',
        'gender' => 'required|string|max:10',
        'address' => 'required|string',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->gender = $request->gender;
    $user->address = $request->address;

    if ($request->hasFile('profile_image')) {
        // Menangani upload gambar profil
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $imagePath;
    }

    $user->save();

    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
}

}
