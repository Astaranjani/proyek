<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Riwayat Pesanan - Admin</title>

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

    <!-- Alpine.js for submenu toggle -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
      /* Custom styling for table and form */
      .container { padding: 24px; font-family: sans-serif; }
      .title { font-size: 24px; font-weight: bold; margin-bottom: 16px; }
      .filter-form { display: flex; gap: 8px; margin-bottom: 16px; }
      .filter-select, .filter-button {
          padding: 6px 12px;
          border: 1px solid #ccc;
          border-radius: 4px;
      }
      .filter-button {
          background-color: #007bff;
          color: white;
          border: none;
          cursor: pointer;
      }
      .table-container {
          background: white;
          border: 1px solid #ddd;
          border-radius: 6px;
          overflow-x: auto;
      }
      table {
          width: 100%;
          border-collapse: collapse;
          font-size: 14px;
      }
      thead {
          background-color: #f5f5f5;
          text-transform: uppercase;
          font-size: 12px;
      }
      th, td {
          padding: 12px;
          border-top: 1px solid #eee;
          text-align: left;
      }
      .status-label {
          padding: 4px 8px;
          border-radius: 4px;
          color: white;
          font-size: 12px;
          display: inline-block;
      }
      .status-pending { background-color: #f59e0b; }
      .status-diproses { background-color: #3b82f6; }
      .status-dikirim { background-color: #8b5cf6; }
      .status-selesai { background-color: #10b981; }
      .status-select {
          padding: 4px 8px;
          font-size: 13px;
          border: 1px solid #ccc;
          border-radius: 4px;
      }
      .text-center { text-align: center; color: #666; padding: 16px; }
    </style>
</head>
<body>
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#111827] text-white flex flex-col" x-data="{ openProduk: {{ (request()->routeIs('admin.barang.index') || request()->routeIs('admin.barang.create')) ? 'true' : 'false' }} }">
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
                    <!-- Dashboard Menu -->
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
                    <li>
                        <button @click="openProduk = !openProduk" 
                                class="flex justify-between items-center w-full px-4 py-2 text-white hover:bg-white/5 rounded-md">
                            <div class="flex items-center">
                                <i class="ri-product-hunt-line mr-3"></i>
                                <span>Produk</span>
                            </div>
                            <i class="ri-arrow-down-s-line" :class="{ 'rotate-180': openProduk }"></i>
                        </button>
                        <ul x-show="openProduk" x-cloak class="ml-6 mt-2 space-y-1">
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
                        <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-white/5 rounded-md">
                            <div class="w-5 h-5 flex items-center justify-center mr-3">
                                <i class="ri-order-play-line"></i>
                            </div>
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
                        <a href="#"
                            class="flex items-center px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                            <i class="ri-bar-chart-line mr-3"></i>
                            <span>Grafik</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.barang') }}"
                           class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.laporan.barang') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                            <i class="ri-file-line mr-3"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Log Out Section -->
            <div class="px-4 py-2 mt-auto">
                <form action="/logout" method="POST">
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
    <main class="flex-1 overflow-auto p-6 container">
        <h1 class="title">Riwayat Pesanan</h1>

        <!-- Filter Status -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="filter-form">
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button class="filter-button" type="submit">Filter</button>
        </form>

        <!-- Tabel Riwayat Pesanan -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-label status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="status-select">
                                        <option disabled selected>Ubah</option>
                                        <option value="diproses">Diproses</option>
                                        <option value="dikirim">Dikirim</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
