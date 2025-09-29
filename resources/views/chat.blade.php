{{-- resources/views/chat.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat Customer Service')

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">

@section('content')
<div class="flex h-screen bg-gray-100">
    <div class="flex-1 flex flex-col max-w-2xl mx-auto w-full shadow-lg border rounded-lg overflow-hidden">

        {{-- Header --}}
        <div class="bg-primary text-white p-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" 
                     alt="Logo" 
                     class="rounded-full object-cover border border-white shadow"
                     style="height: 45px; width: 45px;">
                <div>
                    <h2 class="text-lg font-semibold">Toko Mebel Online</h2>
                    <p class="text-sm text-gray-200">Customer Service</p>
                </div>
            </div>
            <a href="{{ url()->previous() }}" 
               class="text-white hover:text-gray-200 font-medium text-lg">
                <i class="bi bi-x-circle-fill"></i>
            </a>
        </div>

        {{-- Chat Messages --}}
        <div id="chatMessages" 
             class="flex-1 flex flex-col justify-end overflow-y-auto p-4 space-y-3 bg-gray-50">

            {{-- Pesan awal dari CS --}}
            <div class="flex items-start gap-2">
                <div class="bg-gray-200 p-3 rounded-2xl max-w-xs shadow">
                    <p>Halo 👋, selamat datang di <b>E-Mebel</b>.<br>
                       Ada yang bisa kami bantu?</p>
                </div>
            </div>

        </div>

        {{-- Input Message --}}
        <div class="bg-white border-t border-gray-300 p-3 flex items-center gap-2">
            <div class="relative flex-1">
                <input id="messageInput" 
                       type="text" 
                       placeholder="Tulis pesan..." 
                       class="w-full border border-gray-300 rounded-full px-4 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-primary">
                
                {{-- Tombol kirim (ikon) --}}
                <button id="sendButton" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-primary hover:text-indigo-700">
                    <i class="bi bi-cursor-fill text-xl"></i>
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

{{-- Script Chat --}}
@push('scripts')
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    const socket = io("http://localhost:3000"); // Hubungkan ke server WebSocket
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    // Tambahkan pesan ke chat
    function addMessage(text, sender = 'customer') {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('flex', sender === 'customer' ? 'justify-end' : 'items-start', 'gap-2');

        messageDiv.innerHTML = `
            <div class="${sender === 'customer' 
                ? 'bg-primary text-white' 
                : 'bg-gray-200 text-black'} 
                p-3 rounded-2xl max-w-xs shadow text-sm leading-relaxed">
                ${text}
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

<<<<<<< HEAD
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
=======
    // Kirim pesan ke server
    sendButton.addEventListener('click', () => {
        const text = messageInput.value.trim();
        if (text === '') return;

        addMessage(text, 'customer'); // tampilkan pesan di sisi user
        socket.emit('chatMessage', text); // kirim ke server
        messageInput.value = '';
>>>>>>> 9f85850690eb7d4153c67133167936168a2b612b
    });
});


    // Tekan Enter untuk kirim
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendButton.click();
        }
    });

    // Terima pesan dari server
    socket.on('chatMessage', (msg) => {
        addMessage(msg, 'cs');
    });
</script>

<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
    const socket = io("http://localhost:3000");

    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    // Tambahkan pesan ke chat
    function addMessage(text, sender = 'customer') {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('flex', sender === 'customer' ? 'justify-end' : 'items-start', 'gap-2');

        messageDiv.innerHTML = `
            <div class="${sender === 'customer' 
                ? 'bg-primary text-white' 
                : 'bg-gray-200 text-black'} 
                p-3 rounded-2xl max-w-xs shadow text-sm leading-relaxed">
                ${text}
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Kirim pesan ke server
    sendButton.addEventListener('click', () => {
        const text = messageInput.value.trim();
        if (text === '') return;

        addMessage(text, 'customer'); // tampil di layar
        socket.emit("chat message", text); // kirim ke server
        messageInput.value = '';
    });

    // Tekan Enter untuk kirim
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendButton.click();
        }
    });

    // Terima pesan dari server
    socket.on("chat message", (msg) => {
        addMessage(msg, 'cs');
    });
</script>

@endpush
