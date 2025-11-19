<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage; // Wajib ada untuk handle file

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user')); // Sesuaikan nama view jika beda
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'gender'  => 'nullable|string|in:Laki-laki,Perempuan',
            'address' => 'nullable|string|max:255',
            // Validasi foto: Boleh kosong, harus gambar, max 2MB
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'name.required' => 'Nama wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'profile_image.image' => 'File harus berupa gambar.',
            'profile_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // --- LOGIKA UPLOAD FOTO ---
        if ($request->hasFile('profile_image')) {
            // 1. Hapus foto lama jika ada
            if ($user->profile_image && Storage::exists('profile_images/' . $user->profile_image)) {
                Storage::delete('profile_images/' . $user->profile_image);
            }

            // 2. Simpan foto baru dengan nama unik (hash)
            $filename = $request->file('profile_image')->hashName();
            $request->file('profile_image')->storeAs('profile_images', $filename);

            // 3. Masukkan nama file ke array data yang akan diupdate
            $validated['profile_image'] = $filename;
        }
        // --------------------------

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}