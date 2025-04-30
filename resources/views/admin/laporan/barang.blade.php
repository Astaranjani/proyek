<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

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
</head>
<body class="flex h-screen bg-gray-50">

<!-- Sidebar -->
<aside class="w-64 bg-[#111827] text-white flex flex-col">
    <div class="p-6 flex items-center">
        <div class="w-8 h-8 flex items-center justify-center bg-primary rounded-md mr-2">
            <i class="ri-dashboard-line text-white"></i>
        </div>
        <h1 class="text-xl font-bold">EMebel</h1>
    </div>

    <div class="flex-1 flex flex-col overflow-y-auto">
        <div class="px-4 py-2">
            <p class="text-xs text-gray-400 font-medium mb-2">ADMIN MENU</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                        <i class="ri-dashboard-line mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li x-data="{ open: false }" x-init="open = window.location.href.includes('barang')">
                    <button @click="open = !open" class="flex justify-between items-center w-full px-4 py-2 text-white hover:bg-white/5 rounded-md">
                        <div class="flex items-center">
                            <i class="ri-product-hunt-line mr-3"></i>
                            <span>Produk</span>
                        </div>
                        <i class="ri-arrow-down-s-line" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <ul x-show="open" x-cloak class="ml-6 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('admin.barang.index') }}" class="flex items-center px-4 py-1 text-gray-300 hover:bg-white/5 rounded-md {{ request()->routeIs('admin.barang.index') ? 'bg-primary/10 text-white' : '' }}">
                                <span>Data Barang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.barang.create') }}" class="flex items-center px-4 py-1 text-gray-300 hover:bg-white/5 rounded-md {{ request()->routeIs('admin.barang.create') ? 'bg-primary/10 text-white' : '' }}">
                                <span>Tambah Barang</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#"
                       class="flex items-center px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                        <i class="ri-order-play-line mr-3"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
            </ul>
        </div>

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

        <div class="px-4 py-2 mt-auto">
            <form action="/logout" method="GET">
                @csrf
                <button type="submit"
                        class="flex items-center w-full px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                    <i class="ri-logout-circle-r-line mr-3"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Konten -->
<div class="flex-1 p-6 overflow-auto">
    <h1 class="text-2xl font-bold mb-6">Laporan Data Barang</h1>

    <div class="overflow-x-auto bg-white p-6 rounded-lg shadow">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Tanggal Masuk</th>
                    <th class="px-4 py-2">Tanggal Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $index => $item)
                <tr class="text-center border-b">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $item->nama_barang }}</td>
                    <td class="px-4 py-2">{{ $item->stok }}</td>
                    <td class="px-4 py-2">{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '-' }}</td>
                    <td class="px-4 py-2">{{ $item->tanggal_keluar ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            <a href="{{ route('admin.laporan.barang_pdf') }}" class="inline-block bg-primary text-white px-4 py-2 rounded hover:bg-primary/80">
                Cetak PDF
            </a>
        </div>
    </div>
</div>

</body>
</html>
