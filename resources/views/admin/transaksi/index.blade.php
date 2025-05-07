<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Transaksi - Admin</title>

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
    <!-- Sidebar -->
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
</head>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Transaksi</h1>
    </div>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full">
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
            <tbody class="divide-y divide-gray-200" id="transactionTable">
                <tr class="hover:bg-gray-50" id="transaction-001">
                    <td class="px-6 py-4">001</td>
                    <td class="px-6 py-4 font-medium">Khoerul Paroid</td>
                    <td class="px-6 py-4">Lemari</td>
                    <td class="px-6 py-4">Rp1.500.000</td>
                    <td class="px-6 py-4 font-medium">Lunas</td>
                    <td class="px-6 py-4">07 Mei 2025</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <button class="px-3 py-1.5 text-sm font-medium rounded-md border border-red-500 text-red-500 hover:bg-red-50 transition-colors delete-btn" 
                                    data-id="001">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50" id="transaction-002">
                    <td class="px-6 py-4">002</td>
                    <td class="px-6 py-4 font-medium">Putri Ayu Fadhilah</td>
                    <td class="px-6 py-4">Sofa</td>
                    <td class="px-6 py-4">Rp850.000</td>
                    <td class="px-6 py-4 font-medium">Belum Lunas</td>
                    <td class="px-6 py-4">06 Mei 2025</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <button class="px-3 py-1.5 text-sm font-medium rounded-md border border-red-500 text-red-500 hover:bg-red-50 transition-colors delete-btn" 
                                    data-id="002">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <tbody class="divide-y divide-gray-200" id="transactionTable">
                    <tr class="hover:bg-gray-50" id="transaction-001">
                        <td class="px-6 py-4">003</td>
                        <td class="px-6 py-4 font-medium">Hamzah Pratama</td>
                        <td class="px-6 py-4">Lemari</td>
                        <td class="px-6 py-4">Rp1.500.000</td>
                        <td class="px-6 py-4 font-medium">Lunas</td>
                        <td class="px-6 py-4">07 Mei 2025</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button class="px-3 py-1.5 text-sm font-medium rounded-md border border-red-500 text-red-500 hover:bg-red-50 transition-colors delete-btn" 
                                        data-id="001">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-id');
            if (confirm('Yakin ingin menghapus transaksi ini?')) {
                // Call function to delete transaction
                deleteTransaction(transactionId);
            }
        });
    });

    // Function to handle transaction deletion
    function deleteTransaction(id) {
        // In a real application, you would make an AJAX call to your server here
        // For demonstration, we'll just remove the row from the table
        
        const row = document.getElementById(`transaction-${id}`);
        if (row) {
            row.remove();
            // Show success message
            alert(`Transaksi ${id} berhasil dihapus`);
            
            // In a real app, you might want to:
            // 1. Make an API call to your backend
            // fetch(`/api/transactions/${id}`, { method: 'DELETE' })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.success) {
            //             row.remove();
            //             alert('Transaksi berhasil dihapus');
            //         }
            //     })
            //     .catch(error => console.error('Error:', error));
        }
    }
});
</script>