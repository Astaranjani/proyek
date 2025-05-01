<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // contoh logika update
        // $user = auth()->user();
        // $user->update($request->all());

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function edit()
{
    $user = Auth::user(); // pakai Facade Auth
 // ambil user yang login

    return view('profile', compact('user')); // sesuaikan dengan nama view kamu
}

}
