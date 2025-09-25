@extends('layouts.app')

@section('title', 'Manual Transaksi')

@section('content')

<script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#4318FF", secondary: "#4FD1C5" },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
          },
        },
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
          -webkit-appearance: none;
          margin: 0;
      }
      body {
          font-family: 'Inter', sans-serif;
      }
    </style>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<div x-data="{ sidebarOpen: false }" class="flex flex-col md:flex-row min-h-screen bg-gray-50">

<!-- Mobile Header -->
<div class="flex items-center justify-between p-4 bg-white shadow md:hidden">
    <h1 class="text-xl font-bold text-primary">EMebel</h1>
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-700 focus:outline-none">
        <i class="ri-menu-line text-2xl"></i>
    </button>
</div>

<aside :class="sidebarOpen ? 'block' : 'hidden'" class="md:block w-full md:w-64 bg-[#111827] text-white flex flex-col z-20 md:z-auto fixed md:relative md:top-0 md:left-0 top-0 left-0 h-full md:h-auto overflow-y-auto md:overflow-visible">

    <div class="p-6 flex items-center">
        <div class="w-8 h-8 flex items-center justify-center bg-primary rounded-md mr-2">
            <i class="ri-dashboard-line text-white"></i>
        </div>
        <h1 class="text-xl font-bold">EMebel</h1>
    </div>

    <!-- Menu -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <div class="px-4 py-2">
            <p class="text-xs text-gray-400 font-medium mb-2">ADMIN MENU</p>
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                    class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                        <div class="w-5 h-5 flex items-center justify-center mr-3">
                            <i class="ri-dashboard-line"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Produk (submenu) -->
                <li x-data="{ open: {{ request()->routeIs('admin.barang.index') || request()->routeIs('admin.barang.create') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex justify-between items-center w-full px-4 py-2 text-white hover:bg-white/5 rounded-md">
                        <div class="flex items-center">
                            <i class="ri-product-hunt-line mr-3"></i>
                            <span>Produk</span>
                        </div>
                        <i class="ri-arrow-down-s-line" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <ul x-show="open" x-cloak class="ml-6 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('admin.barang.index') }}" 
                            class="flex items-center px-4 py-1 rounded-md {{ request()->routeIs('admin.barang.index') ? 'text-white bg-primary/10' : 'text-gray-300 hover:bg-white/5' }}">
                                <span>Data Barang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.barang.create') }}" 
                            class="flex items-center px-4 py-1 rounded-md {{ request()->routeIs('admin.barang.create') ? 'text-white bg-primary/10' : 'text-gray-300 hover:bg-white/5' }}">
                                <span>Tambah Barang</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Transaksi Menu -->
                <li>
                    <a href="{{ route('admin.transaksi.index') }}"
                        class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.transaksi.index') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                        <i class="ri-order-play-line mr-3"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
            <li>
    <a href="{{ route('admin.manual') }}" 
       class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.manual') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
        <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-edit-box-line"></i>
        </div>
        <span>Manual Transaksi</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.chat') }}" 
   class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.chat') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
    <div class="w-5 h-5 flex items-center justify-center mr-3">
        <i class="ri-chat-1-line"></i>
    </div>
    <span>Chat</span>
</a>
</li>
</ul>
</div>


        <!-- Log Out Section -->
        <div class="px-4 py-2 mt-auto">
            <form action="/logout" method="GET">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 text-gray-400 hover:bg-white/5 rounded-md w-full">
                    <div class="w-5 h-5 flex items-center justify-center mr-3">
                        <i class="ri-logout-circle-r-line"></i>
                    </div>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </div>
</aside>

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-6 bg-white rounded-lg shadow-md max-w-2xl">
            <h2 class="text-2xl font-bold mb-4">Input Manual Transaksi</h2>

            <form action="{{ route('admin.manual.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
 
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Nama Barang</label>
                    <input type="text" name="nama_barang" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Total Pembayaran (Rp)</label>
                    <input type="number" name="total_pembayaran" required min="0" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

               <div>
                    <label class="block text-gray-700 font-medium mb-2">Status Pembayaran</label>
                   <input type="text" name="status_pembayaran" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
               </div>

                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-button hover:bg-primary/90 transition-all">
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </main>
</div>

</body>
</html>