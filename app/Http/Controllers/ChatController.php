<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Menampilkan halaman chat
    public function index()
    {
        // Bisa diisi data chat dari database nanti
        return view('chat');
    }

    // Menyimpan pesan baru
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Simpan pesan ke database (misal model Chat)
        // Chat::create([
        //     'user_id' => auth()->id(),
        //     'message' => $request->message,
        // ]);

        return redirect()->back()->with('success', 'Pesan terkirim!');
    }
}
