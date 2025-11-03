<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil user yang sedang login (API)
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json($user);
    }

    /**
     * Memperbarui profil user (API)
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'gender.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'address.max' => 'Alamat maksimal 255 karakter.',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => $user
        ]);
    }
}
