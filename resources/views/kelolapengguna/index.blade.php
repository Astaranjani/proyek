<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Pengguna - Admin Emebel</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#4318FF", secondary: "#4FD1C5" },
            borderRadius: {
              none: "0px", sm: "4px", DEFAULT: "8px", md: "12px", lg: "16px", xl: "20px", "2xl": "24px", "3xl": "32px", full: "9999px", button: "8px",
            },
          },
        },
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
      body { font-family: 'Inter', sans-serif; }
      /* Custom scrollbar for table if needed */
      .table-container::-webkit-scrollbar { height: 8px; }
      .table-container::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div x-data="{ sidebarOpen: false }" class="flex flex-col md:flex-row min-h-screen">

    <div class="flex items-center justify-between p-4 bg-white shadow md:hidden z-30 relative">
        <h1 class="text-xl font-bold text-primary">EMebel</h1>
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-700 focus:outline-none">
            <i class="ri-menu-line text-2xl"></i>
        </button>
    </div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="transform md:translate-x-0 transition-transform duration-300 ease-in-out fixed md:relative w-64 bg-[#111827] text-white flex flex-col h-full z-40 inset-y-0 left-0 md:h-auto overflow-y-auto">
        
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
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                            <div class="w-5 h-5 flex items-center justify-center mr-3"><i class="ri-dashboard-line"></i></div>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('kelolapengguna.index') }}" 
                           class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('kelolapengguna.*') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                            <div class="w-5 h-5 flex items-center justify-center mr-3">
                                <i class="ri-user-settings-line"></i>
                            </div>
                            <span>Kelola Pengguna</span>
                        </a>
                    </li>

                    <li x-data="{ open: false }">
                        <button @click="open = !open" class="flex justify-between items-center w-full px-4 py-2 text-gray-400 hover:bg-white/5 rounded-md">
                            <div class="flex items-center">
                                <i class="ri-product-hunt-line mr-3"></i>
                                <span>Produk</span>
                            </div>
                            <i class="ri-arrow-down-s-line" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <ul x-show="open" x-cloak class="ml-6 mt-2 space-y-1">
                            <li><a href="{{ route('admin.barang.index') }}" class="block px-4 py-1 text-gray-400 hover:text-white">Data Barang</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.transaksi.index') }}" class="flex items-center px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                            <i class="ri-order-play-line mr-3"></i> <span>Transaksi</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.manual') }}" class="flex items-center px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                            <div class="w-5 h-5 flex items-center justify-center mr-3"><i class="ri-edit-box-line"></i></div>
                            <span>Manual Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.chat') }}" class="flex items-center px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                            <div class="w-5 h-5 flex items-center justify-center mr-3"><i class="ri-chat-1-line"></i></div>
                            <span>Chat</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="px-4 py-2 mt-auto mb-4">
                <form action="{{ route('logout') }}" method="GET">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 rounded-md text-gray-400 hover:bg-white/5">
                        <i class="ri-logout-circle-r-line mr-3"></i> <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>

    <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
        
        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="ri-close-line cursor-pointer" onclick="this.parentElement.parentElement.style.display='none';"></i>
                </span>
            </div>
        @endif

        {{-- ================================================== --}}
        {{-- LOGIKA 1: TAMPILAN TABEL (INDEX)                   --}}
        {{-- ================================================== --}}
        @if($halaman == 'index')
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Daftar Pengguna</h2>
                        <p class="text-sm text-gray-500">Kelola akun admin dan user.</p>
                    </div>
                    <a href="{{ route('kelolapengguna.create') }}" class="mt-4 md:mt-0 bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition-colors">
                        <i class="ri-add-line mr-2"></i> Tambah User
                    </a>
                </div>

                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-200 text-sm">
                                <th class="py-3 px-4 font-medium">No</th>
                                <th class="py-3 px-4 font-medium">Nama Lengkap</th>
                                <th class="py-3 px-4 font-medium">Email</th>
                                <th class="py-3 px-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @forelse($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('kelolapengguna.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-600 bg-yellow-50 p-2 rounded-md transition-colors">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('kelolapengguna.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600 bg-red-50 p-2 rounded-md transition-colors">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">Tidak ada data pengguna.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            </div>

        {{-- ================================================== --}}
        {{-- LOGIKA 2: FORM TAMBAH (CREATE)                     --}}
        {{-- ================================================== --}}
        @elseif($halaman == 'create')
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Tambah User Baru</h2>
                    <a href="{{ route('kelolapengguna.index') }}" class="text-sm text-gray-500 hover:text-primary">
                        <i class="ri-arrow-left-line mr-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('kelolapengguna.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="contoh@email.com" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary hover:bg-blue-700 text-white font-medium py-2.5 rounded-md transition-colors">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>

        {{-- ================================================== --}}
        {{-- LOGIKA 3: FORM EDIT (EDIT)                         --}}
        {{-- ================================================== --}}
        @elseif($halaman == 'edit')
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Edit User</h2>
                    <a href="{{ route('kelolapengguna.index') }}" class="text-sm text-gray-500 hover:text-primary">
                        <i class="ri-arrow-left-line mr-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('kelolapengguna.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Kosongkan jika tidak ingin mengganti">
                        <p class="text-xs text-gray-500 mt-1">*Hanya isi jika ingin mengubah password.</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-secondary hover:bg-teal-500 text-white font-medium py-2.5 rounded-md transition-colors">
                            Update Perubahan
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </main>
</div>

</body>
</html>