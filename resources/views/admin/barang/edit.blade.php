@extends('layouts.app')

@section('title', 'Edit Barang')

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
<div class="flex h-screen bg-gray-50">

<aside class="w-64 bg-[#111827] text-white flex flex-col">
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
            </ul>
        </div>

        <!-- Report Section -->
        <div class="px-4 py-2">
            <p class="text-xs text-gray-400 font-medium mb-2">REPORTS</p>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-white/5 rounded-md">
                        <div class="w-5 h-5 flex items-center justify-center mr-3">
                            <i class="ri-bar-chart-line"></i>
                        </div>
                        <span>Grafik</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-white/5 rounded-md">
                        <div class="w-5 h-5 flex items-center justify-center mr-3">
                            <i class="ri-file-line"></i>
                        </div>
                        <span>Laporan</span>
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

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between">
            <div class="text-xl font-semibold">Edit Barang</div>
        </header>

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

            <div class="bg-white p-6 rounded shadow-md">
                <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nama" class="block font-semibold mb-1">Nama Barang</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $barang->nama) }}" class="form-input w-full border rounded px-3 py-2 @error('nama') border-red-500 @enderror" required>
                        @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="block font-semibold mb-1">Harga</label>
                        <input type="number" id="harga" name="harga" value="{{ old('harga', $barang->harga) }}" class="form-input w-full border rounded px-3 py-2 @error('harga') border-red-500 @enderror" required>
                        @error('harga')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="stok" class="block font-semibold mb-1">Stok</label>
                        <input type="number" id="stok" name="stok" value="{{ old('stok', $barang->stok) }}" class="form-input w-full border rounded px-3 py-2 @error('stok') border-red-500 @enderror" required>
                        @error('stok')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block font-semibold mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-textarea w-full border rounded px-3 py-2 @error('deskripsi') border-red-500 @enderror" rows="4">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
  <label for="kategori" class="block font-semibold mb-1">Kategori</label>
  <select id="kategori" name="kategori"
          class="form-select w-full border rounded px-3 py-2 @error('kategori') border-red-500 @enderror"
          required>
    <option value="">-- Pilih Kategori --</option>

    @foreach($kategori as $k)
  <option value="{{ $k['nama_kategori'] }}">
    {{ $k['nama_kategori'] }}
  </option>
@endforeach



    {{-- fallback kalau data kategori kosong --}}
    @if($kategori->isEmpty())
      <option value="Kursi">Kursi</option>
      <option value="Meja">Meja</option>
      <option value="Lemari">Lemari</option>
      <option value="Sofa">Sofa</option>
      <option value="Kasur">Kasur</option>
    @endif
  </select>
  @error('kategori')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
  @enderror
</div>

                    <div class="mb-4">
                        <label for="gambar" class="block font-semibold mb-1">Gambar (Opsional)</label>
                        @if ($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" class="mb-2 h-24 w-24 object-cover rounded">
                        @endif
                        <input type="file" name="gambar" id="gambar" class="form-input w-full border rounded px-3 py-2 @error('gambar') border-red-500 @enderror">
                        @error('gambar')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.barang.index') }}" class="px-4 py-2 bg-gray-200 rounded mr-2">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-primary text-blue rounded">Update</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
