{{-- resources/views/chat.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat Customer Service')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">


@section('content')
<div class="flex h-screen bg-gray-100">
    <div class="flex-1 flex flex-col">

        {{-- Header --}}
        {{-- Header --}}
<div class="bg-white border-b border-gray-300 p-4 flex justify-between items-center shadow">
    <div class="flex items-center gap-2">
      <img src="{{ asset('images/logo.jpg') }}" 
             alt="Logo" 
             class="rounded-full object-contain" 
             style="max-height: 50px;">
        <h2 class="text-lg font-semibold">Toko Mebel Online</h2>
    </div>
    <a href="{{ url()->previous() }}" 
       class="text-red-500 hover:text-red-700 font-medium">
        Tutup ✖
    </a>
</div>


        {{-- Chat Messages --}}
        {{-- Chat Messages --}}
<div id="chatMessages" 
     class="flex-1 flex flex-col justify-end overflow-y-auto p-4 space-y-4 bg-gray-50">

    {{-- Pesan awal dari CS --}}
    <div class="flex">
        <div class="bg-gray-200 p-3 rounded-lg max-w-xs shadow">
            Halo 👋, selamat datang di E-Mebel.  
            Ada yang bisa kami bantu?
        </div>
    </div>

</div>

      
        {{-- Input Message --}}
<div class="bg-white border-t border-gray-300 p-3 flex items-center">
    <div class="relative flex-1">
        <input id="messageInput" 
            type="text" 
            placeholder="Tulis pesan..." 
            class="w-full border border-gray-300 rounded-full px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary">
        
        {{-- Tombol kirim (ikon) --}}
       <button id="sendButton" 
    class="absolute right-2 top-1/2 -translate-y-1/2 text-primary hover:text-indigo-700">
    <i class="bi bi-cursor-fill text-xl"></i>
</button>

    </div>
</div>


<script>
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    // Fungsi untuk menambahkan pesan ke chat
    function addMessage(text, sender = 'customer') {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('flex', sender === 'customer' ? 'justify-end' : '');
        
        messageDiv.innerHTML = `
            <div class="${sender === 'customer' 
                ? 'bg-primary text-white' 
                : 'bg-gray-200 text-black'} p-3 rounded-lg max-w-xs shadow">
                ${text}
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Event kirim pesan
sendButton.addEventListener('click', () => {
    const text = messageInput.value.trim();
    if (text === '') return;

    addMessage(text, 'customer');
    messageInput.value = '';

    // Kirim ke server Laravel
    fetch("{{ route('chat.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message: text })
    })
    .then(res => res.json())
    .then(data => {
        addMessage(data.reply, 'cs'); // balasan dari OpenAI
    })
    .catch(() => {
        addMessage("⚠️ Terjadi kesalahan. Coba lagi.", 'cs');
    });
});


    // Tekan Enter untuk kirim
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendButton.click();
        }
    });
</script>
@endsection
