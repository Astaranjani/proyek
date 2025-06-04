@extends('layouts.app')

@section('title', 'Transaksi')

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

  <!-- Main Content -->
  <div class="flex-1 flex flex-col overflow-hidden">
      <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between">
          <div class="text-xl font-semibold">Daftar Transaksi</div>
      </header>

      <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">ID Transaksi</th>
                    <th class="px-6 py-3 text-left">Nama Pelanggan</th>
                    <th class="px-6 py-3 text-left">Nama Barang</th>
                    <th class="px-6 py-3 text-left">Total Pembayaran</th>
                    <th class="px-6 py-3 text-left">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left">Tanggal Transaksi</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transaksi as $t)
                    <tr id="transaction-{{ $t->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4" data-label="ID Transaksi">{{ $t->id }}</td>
                        <td class="px-6 py-4 font-medium" data-label="Nama Pelanggan">{{ $t->user->nama ?? $t->nama_user }}</td>
                        <td class="px-6 py-4" data-label="Nama Barang">{{ $t->barang->nama ?? $t->nama_barang }}</td>
                        <td class="px-6 py-4" data-label="Total Pembayaran">Rp{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-medium" data-label="Status Pembayaran">
                            <span class="{{ $t->status_pembayaran == 'Lunas' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $t->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4" data-label="Tanggal Transaksi">{{ date('d M Y', strtotime($t->created_at)) }}</td>
                        <td class="px-6 py-4" data-label="Aksi">
                            <form method="POST" action="{{ route('admin.transaksi.destroy', $t->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                        class="px-3 py-1.5 text-sm font-medium rounded-md border border-red-500 text-red-500 hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 bg-gray-100 text-right font-bold">
            Total Pemasukan: Rp{{ number_format($totalSemuaPembayaran, 0, ',', '.') }}
        </div>
        <a href="/admin/transaksi/download/" class="inline-block px-4 py-2 bg-primary text-white rounded-md font-bold hover:bg-primary/90 transition-colors">
            Unduh PDF
        </a>
    </div>
</main>
