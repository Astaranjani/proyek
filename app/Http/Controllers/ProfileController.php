<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan halaman profil (Blade)
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return view('profile', compact('user'));
    }

    /**
     * Update profil pengguna
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone' => ['nullable','string','max:20'],
            'gender' => ['nullable','in:Laki-laki,Perempuan'],
            'address' => ['nullable','string','max:1000'],
            'profile_image' => ['nullable','image','mimes:jpeg,png,jpg','max:2048'], // 2048 KB = 2MB
        ]);

        // Tangani upload foto (hapus foto lama jika ada)
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $path = $file->store('profile_images', 'public'); // menyimpan -> 'profile_images/abcd.png'

            // Hapus file lama jika ada
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $validated['profile_image'] = $path;
        }

        // Update data user
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
