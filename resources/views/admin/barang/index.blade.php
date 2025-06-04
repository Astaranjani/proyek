@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>
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
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
<style>
  body {
    font-family: 'Inter', sans-serif;
  }

  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
  }
</style>


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
                  <li>
                      <a href="{{ route('admin.dashboard') }}" 
                      class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                          <div class="w-5 h-5 flex items-center justify-center mr-3">
                              <i class="ri-dashboard-line"></i>
                          </div>
                          <span>Dashboard</span>
                      </a>
                  </li>
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
              </ul>
          </div>

          <!-- Report Section -->
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
          <div class="text-xl font-semibold">Data Barang</div>
          <div class="flex items-center space-x-4">
              <a href="{{ route('admin.barang.create') }}" class="bg-primary text-white px-4 py-2 rounded-md">+ Tambah Barang</a>
          </div>
      </header>

      <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
          @if(session('success'))
              <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">
                  {{ session('success') }}
              </div>
          @endif

          <div class="overflow-x-auto bg-white rounded-lg shadow">
              <table class="min-w-full text-sm text-gray-700">
                  <thead class="bg-gray-100 text-xs font-semibold uppercase text-gray-600">
                      <tr>
                          <th class="px-6 py-3 text-left">No</th>
                          <th class="px-6 py-3 text-left">Gambar</th>
                          <th class="px-6 py-3 text-left">Nama</th>
                          <th class="px-6 py-3 text-left">Harga</th>
                          <th class="px-6 py-3 text-left">Stok</th>
                          <th class="px-6 py-3 text-left">Deskripsi</th>
                          <th class="px-6 py-3 text-left">Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($barangs as $index => $barang)
                          <tr class="border-b">
                              <td class="px-6 py-3">{{ $barangs->firstItem() + $index }}</td>
                              <td class="px-6 py-3">
                                  <img src="{{ $barang->gambar ? asset('storage/' . $barang->gambar) : asset('images/default.png') }}" class="h-16 w-16 object-cover rounded" />
                              </td>
                              <td class="px-6 py-3">{{ $barang->nama }}</td>
                              <td class="px-6 py-3">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                              <td class="px-6 py-3">{{ $barang->stok }}</td>
                              <td class="px-6 py-3">{{ Str::limit($barang->deskripsi, 50) }}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                  <div class="flex space-x-3 items-center">
                                      <a href="{{ route('admin.barang.edit', $barang->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Edit</a>
                                      <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Hapus</button>
                                      </form>
                                  </div>                                    
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="7" class="px-6 py-3 text-center text-gray-500">Belum ada data barang.</td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>

          <div class="mt-4">
              {{ $barangs->links() }}
          </div>
      </main>
  </div>
</div>

<script src="https://unpkg.com/alpinejs" defer></script>
@endsection