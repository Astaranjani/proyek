{{-- resources/views/chat.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat Admin')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/css/bootstrap-icons.min.css">

@section('content')
<div class="flex h-screen bg-gray-100">
    {{-- Sidebar daftar pelanggan --}}
    <div class="w-1/4 bg-white border-r border-gray-300 flex flex-col">
        <div class="p-4 border-b border-gray-300">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="bi bi-people-fill text-blue-500"></i>
                Daftar Pelanggan
            </h2>
        </div>
        <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
            <i class="bi bi-person-x text-5xl mb-3"></i>
            <p class="font-medium">Belum ada pelanggan yang chat</p>
            <p class="text-sm">Daftar pelanggan akan muncul di sini</p>
        </div>
    </div>

    {{-- Area chat --}}
    <div class="flex-1 flex flex-col">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-300 p-4 flex justify-between items-center shadow">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" 
                     alt="Logo" 
                     class="rounded-full object-contain" 
                     style="max-height: 40px;">
                <h2 class="text-lg font-semibold">Toko Mebel Online</h2>
            </div>
            <a href="{{ url()->previous() }}" 
               class="text-red-500 hover:text-red-700 font-medium">
                Tutup ✖
            </a>
        </div>

        {{-- Chat kosong --}}
        <div id="chatMessages" 
             class="flex-1 flex flex-col items-center justify-center p-6 bg-gray-50 text-gray-500">
            <i class="bi bi-chat-dots text-6xl mb-4"></i>
            <p class="text-lg font-semibold">Belum ada chat yang dipilih</p>
            <p class="text-sm">Silakan tunggu hingga ada pelanggan yang menghubungi Anda</p>
        </div>
    </div>
</div>
@endsection
