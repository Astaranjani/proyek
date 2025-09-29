<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

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

         $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo', // atau "gpt-4o-mini" biar lebih cepat
            'messages' => [
                ['role' => 'system', 'content' => 'Kamu adalah chatbot ramah di website Laravel.'],
                ['role' => 'user', 'content' => $request->message],
            ],
        ]);

        $botReply = $response->choices[0]->message->content ?? "Maaf, saya tidak bisa merespons.";


        return response()->json([
            'reply' => $botReply
        ]);
    }
}


