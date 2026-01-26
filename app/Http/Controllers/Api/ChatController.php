controler

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // --- 1. HALAMAN UTAMA CHAT (Untuk User) ---
    public function index()
    {
        return view('chat'); 
    }

    // --- 2. LOGIKA CHATBOT (GEMINI AI) ---
    public function sendMessage(Request $request)
    {
        $userMessage = $request->message;
        
        $systemInstruction = "
            Peran: Kamu adalah CS Toko 'E-Mebel'.
            Info Toko: Jual mebel jati asli Cirebon. Buka 08.00-17.00.
            Tugas: Jawab pertanyaan pembeli dengan ramah, singkat, dan menggoda.
        ";

        $apiKey = env('GEMINI_API_KEY');
        $botReply = "Maaf kak, CS sedang istirahat sebentar.";

        try {
            // Gemini 2.5 Flash
            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    "contents" => [[
                        "parts" => [["text" => $systemInstruction . "\n\nUser bertanya: " . $userMessage]]
                    ]]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? $botReply;
            } else {
                Log::error("Gemini API Error: " . $response->body());
            }
            
        } catch (\Exception $e) {
            Log::error("Exception: " . $e->getMessage());
        }

        // Simpan ke Database
        try {
            Chat::create([
                'user_id' => Auth::id() ?? null,
                'message' => $userMessage,
                'reply'   => $botReply
            ]);
        } catch (\Exception $e) {
            Log::error("DB Error: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'reply' => $botReply
        ]);
    }

    // --- 3. HALAMAN ADMIN HISTORY ---
    public function showHistory()
    {
        $chats = Chat::with('user')->latest()->get();
        return view('admin_history', compact('chats'));
    }

    // --- 4. ADMIN MEMBALAS PESAN (Manual) ---
    public function adminReply(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'reply'   => 'required'
        ]);

        Chat::create([
            'user_id' => $request->user_id,
            'message' => '(Pesan Admin)', 
            'reply'   => $request->reply
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    // --- 5. API LOAD HISTORY (INI YANG HARUS DITAMBAHKAN) ---
    // Fungsinya: Mengambil data chat dari database agar muncul di layar user
    public function getMessages()
    {
        // Cek jika user belum login, kembalikan kosong (atau bisa pakai session ID untuk tamu)
        if (!Auth::check()) {
            return response()->json([]);
        }

        // Ambil 20 pesan terakhir milik user tersebut
        $chats = Chat::where('user_id', Auth::id())
                     ->latest()
                     ->take(20)
                     ->get()
                     ->reverse() // Dibalik agar urutannya benar (lama -> baru)
                     ->values();

        return response()->json($chats);
    }
    public function destroy($id)
    {
        $chat = Chat::find($id);
        if ($chat) {
            $chat->delete();
            return back()->with('success', 'Chat berhasil dihapus!');
        }
        return back()->with('error', 'Chat tidak ditemukan');
    }
}