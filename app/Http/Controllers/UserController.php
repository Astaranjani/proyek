<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        // View tetap di folder 'kelolapengguna'
        return view('kelolapengguna.index', [
            'halaman' => 'index', 
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('kelolapengguna.index', [
            'halaman' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // PERBAIKAN: Redirect ke 'kelolapengguna.index'
        return redirect()->route('kelolapengguna.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('kelolapengguna.index', [
            'halaman' => 'edit',
            'user' => $user
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8',
        ]);

        $user = User::findOrFail($id);
        $data = ['name' => $request->name, 'email' => $request->email];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);

        // PERBAIKAN: Redirect ke 'kelolapengguna.index'
        return redirect()->route('kelolapengguna.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        
        // PERBAIKAN: Redirect ke 'kelolapengguna.index'
        return redirect()->route('kelolapengguna.index')->with('success', 'User berhasil dihapus!');
    }
}