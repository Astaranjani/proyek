<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Emebel</title>
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

    <!-- Menu -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <div class="px-4 py-2">
            <p class="text-xs text-gray-400 font-medium mb-2">ADMIN MENU</p>
            <ul class="space-y-1">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                        <i class="ri-dashboard-line mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Produk -->
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

                <!-- Transaksi -->
                <li>
                    <a href="{{ route('admin.transaksi.index') }}"
                        class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('admin.transaksi.index') ? 'text-white bg-primary/10' : 'text-gray-400 hover:bg-white/5' }}">
                        <i class="ri-order-play-line mr-3"></i>
                        <span>Transaksi</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- REPORTS -->
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
                        <span>Laporan Barang</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout -->
        <div class="px-4 py-2 mt-auto">
            <form action="{{ route('logout') }}" method="GET">
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


    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between">
            <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="ri-search-line text-gray-400"></i>
            </div>
            <input
                type="text"
                class="bg-gray-50 border-none text-gray-900 text-sm rounded-lg block w-full pl-10 p-2.5"
                placeholder="Ketik untuk mencari produk..."
            />
            </div>
        
            <div class="flex items-center space-x-4">
            <div class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full cursor-pointer">
                <i class="ri-notification-3-line"></i>
            </div>
            <div class="flex items-center">
                <div class="mr-3 text-right">
                {{-- <div class="text-sm font-medium text-gray-900">Admin</div>
                <div class="text-xs text-gray-500">Administrator</div>
                </div> --}}
                {{-- <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden"> --}}
                {{-- <img
                    src="https://via.placeholder.com/100"
                    alt="Profile"
                    class="w-full h-full object-cover"
                /> --}}
                </div>
            </div>
            </div>
        </header>
        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Views Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="ri-eye-line text-primary"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Rp 3.456K</h3>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">Total Tampilan</p>
                        <span class="text-xs font-medium text-green-500 flex items-center">
                            0,43% <i class="ri-arrow-up-line ml-1"></i>
                        </span>
                    </div>
                </div>
                
                <!-- Profit Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="ri-shopping-cart-line text-primary"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Rp 45,2K</h3>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">Pemasukan</p>
                        <span class="text-xs font-medium text-green-500 flex items-center">
                            4,35% <i class="ri-arrow-up-line ml-1"></i>
                        </span>
                    </div>
                </div>
                
                <!-- Product Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="ri-archive-line text-primary"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $jumlahBarang }} Barang</h3>
                    <p class="text-md text-gray-700 mt-1">Total Stok: {{ $totalStok }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-sm text-gray-500">Statistik Barang</p>
                        <span class="text-xs font-medium text-green-500 flex items-center">
                            2,59% <i class="ri-arrow-up-line ml-1"></i>
                        </span>
                    </div>
                </div>
                           
                
                <!-- Users Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="ri-user-line text-primary"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $jumlahPengguna }}</h3>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">Total Pengguna</p>
                        <span class="text-xs font-medium text-green-500 flex items-center">
                            0,95% <i class="ri-arrow-up-line ml-1"></i>
                        </span>
                    </div>
                </div>                              
            </div>
            
            <!-- Charts Section -->
            {{-- Chart Section --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                {{-- Tombol Filter Chart Dinamis --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold">Statistik Pemasukan</h2>
                    <div class="flex space-x-4">
                        <button onclick="showChart('hari')" class="px-4 py-1 text-sm rounded-full bg-gray-100 text-primary">Hari</button>
                        <button onclick="showChart('minggu')" class="px-4 py-1 text-sm rounded-full hover:bg-gray-100">Minggu</button>
                        <button onclick="showChart('bulan')" class="px-4 py-1 text-sm rounded-full hover:bg-gray-100">Bulan</button>
                    </div>
                </div>

                {{-- Chart Container --}}
                <div class="mt-4">
                    <canvas id="chartPemasukanPerHari" class="chart-view"></canvas>
                    <canvas id="chartPemasukanPerMinggu" class="chart-view hidden"></canvas>
                    <canvas id="chartPemasukan" class="chart-view hidden"></canvas>
                </div>

                <script>
                    function showChart(chartId) {
                        document.querySelectorAll('.chart-view').forEach(el => el.classList.add('hidden'));
                        document.getElementById('chartPemasukanPerHari').classList.toggle('hidden', chartId !== 'hari');
                        document.getElementById('chartPemasukanPerMinggu').classList.toggle('hidden', chartId !== 'minggu');
                        document.getElementById('chartPemasukan').classList.toggle('hidden', chartId !== 'bulan');
                    }

                    // Chart Hari
                    const chartHari = new Chart(document.getElementById('chartPemasukanPerHari'), {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach ($pemasukan_hari as $key => $value)
                                    '{{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->isoFormat('D MMM Y') }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: "Pemasukan per Hari",
                                backgroundColor: 'rgb(75, 192, 192)',
                                borderColor: 'rgb(75, 192, 192)',
                                data: [
                                    @foreach ($pemasukan_hari as $value)
                                        {{ $value }},
                                    @endforeach
                                ],
                                fill: false,
                                tension: 0.1
                            }]
                        }
                    });

                    // Chart Minggu
                    const chartMinggu = new Chart(document.getElementById('chartPemasukanPerMinggu'), {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach ($pemasukan_minggu as $key => $value)
                                    '{{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->isoFormat('D MMM Y') }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: "Pemasukan per Minggu",
                                backgroundColor: 'rgb(54, 162, 235)',
                                borderColor: 'rgb(54, 162, 235)',
                                data: [
                                    @foreach ($pemasukan_minggu as $value)
                                        {{ $value }},
                                    @endforeach
                                ],
                                fill: false,
                                tension: 0.1
                            }]
                        }
                    });

                    // Chart Bulan
                    const chartBulan = new Chart(document.getElementById('chartPemasukan'), {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach ($pemasukan_bulan as $key => $value)
                                    '{{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->isoFormat('D MMM Y') }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: "Pemasukan per Bulan",
                                backgroundColor: 'rgb(255, 99, 132)',
                                borderColor: 'rgb(255, 99, 132)',
                                data: [
                                    @foreach ($pemasukan_bulan as $value)
                                        {{ $value }},
                                    @endforeach
                                ],
                                fill: false,
                                tension: 0.1
                            }]
                        }
                    });
                </script>

                {{-- Form Filter Tanggal --}}
                <form method="GET" action="{{ route('admin.dashboard') }}" class="mt-6 flex items-center space-x-2">
                    <label for="start_date" class="text-sm">Dari:</label>
                    <input type="date" id="start_date" name="start_date" class="border rounded px-2 py-1 text-sm" value="{{ request('start_date') }}">

                    <label for="end_date" class="text-sm">Sampai:</label>
                    <input type="date" id="end_date" name="end_date" class="border rounded px-2 py-1 text-sm" value="{{ request('end_date') }}">

                    <button type="submit" class="px-3 py-1 bg-primary text-white text-sm rounded">Tampilkan</button>
                </form>

                {{-- Chart Hasil Filter Tanggal --}}
                @if (count($customData))
                    <div class="mt-6">
                        <h4 class="text-base font-semibold mb-2">Pemasukan dari {{ request('start_date') }} sampai {{ request('end_date') }}</h4>
                        <canvas id="chartCustom"></canvas>
                        <script>
                            const chartCustom = new Chart(document.getElementById('chartCustom'), {
                                type: 'bar',
                                data: {
                                    labels: [
                                        @foreach ($customData as $tanggal => $value)
                                            '{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMM Y') }}',
                                        @endforeach
                                    ],
                                    datasets: [{
                                        label: 'Pemasukan',
                                        data: [
                                            @foreach ($customData as $value)
                                                {{ $value }},
                                            @endforeach
                                        ],
                                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                        borderColor: 'rgba(153, 102, 255, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { display: true }
                                    }
                                }
                            });
                        </script>
                    </div>
                @endif
            </div>
            <br>
        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Visitors Analytics -->
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-semibold">Analitik Pengunjung</h3>
                    <div class="relative">
                        <button class="flex items-center text-sm text-gray-500 hover:text-gray-700">
                            Bulanan <i class="ri-arrow-down-s-line ml-1"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-32 text-sm">Cirebon</div>
                        <div class="flex-1">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm">65%</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 text-sm">Indramayu</div>
                        <div class="flex-1">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm">42%</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 text-sm">Majalengka</div>
                        <div class="flex-1">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm">25%</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 text-sm">Kuningan</div>
                        <div class="flex-1">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm">15%</div>
                    </div>
                </div>
            </div>

            <!-- Region Labels -->
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-semibold">Label Wilayah</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="ri-map-pin-line text-primary"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Cirebon</p>
                            <p class="text-xs text-gray-500">1.250 Pengguna</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="ri-map-pin-line text-primary"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Indramayu</p>
                            <p class="text-xs text-gray-500">850 Pengguna</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="ri-map-pin-line text-primary"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Majalengka</p>
                            <p class="text-xs text-gray-500">670 Pengguna</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="ri-map-pin-line text-primary"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Kuningan</p>
                            <p class="text-xs text-gray-500">420 Pengguna</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main Chart
            const mainChart = echarts.init(document.getElementById('mainChart'));
            const mainOption = {
                animation: false,
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(255, 255, 255, 0.8)',
                    borderColor: '#e5e7eb',
                    textStyle: {
                        color: '#1f2937'
                    }
                },
                grid: {
                    left: '0',
                    right: '0',
                    top: '10',
                    bottom: '0',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'],
                    axisLine: {
                        lineStyle: {
                            color: '#e5e7eb'
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        color: '#6b7280'
                    }
                },
                yAxis: {
                    type: 'value',
                    axisLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#f3f4f6'
                        }
                    },
                    axisLabel: {
                        color: '#6b7280'
                    }
                },
                series: [
                    {
                        name: 'Total Pendapatan',
                        type: 'line',
                        smooth: true,
                        symbol: 'none',
                        lineStyle: {
                            width: 3,
                            color: 'rgba(87, 181, 231, 1)'
                        },
                        areaStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0, color: 'rgba(87, 181, 231, 0.2)'
                                }, {
                                    offset: 1, color: 'rgba(87, 181, 231, 0.01)'
                                }]
                            }
                        },
                        data: [30, 25, 35, 40, 25, 30, 35, 40, 35, 25, 30, 35]
                    },
                    {
                        name: 'Total Penjualan',
                        type: 'line',
                        smooth: true,
                        symbol: 'none',
                        lineStyle: {
                            width: 3,
                            color: 'rgba(141, 211, 199, 1)'
                        },
                        areaStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0, color: 'rgba(141, 211, 199, 0.2)'
                                }, {
                                    offset: 1, color: 'rgba(141, 211, 199, 0.01)'
                                }]
                            }
                        },
                        data: [20, 15, 25, 30, 20, 25, 30, 35, 30, 20, 25, 30]
                    }
                ]
            };
            mainChart.setOption(mainOption);

            // Weekly Chart
            const weeklyChart = echarts.init(document.getElementById('weeklyChart'));
            const weeklyOption = {
                animation: false,
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(255, 255, 255, 0.8)',
                    borderColor: '#e5e7eb',
                    textStyle: {
                        color: '#1f2937'
                    }
                },
                grid: {
                    left: '0',
                    right: '0',
                    top: '10',
                    bottom: '0',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
                    axisLine: {
                        lineStyle: {
                            color: '#e5e7eb'
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        color: '#6b7280'
                    }
                },
                yAxis: {
                    type: 'value',
                    axisLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#f3f4f6'
                        }
                    },
                    axisLabel: {
                        color: '#6b7280'
                    }
                },
                series: [
                    {
                        name: 'Penjualan',
                        type: 'bar',
                        barWidth: '12',
                        itemStyle: {
                            color: 'rgba(87, 181, 231, 1)',
                            borderRadius: [4, 4, 0, 0]
                        },
                        data: [45, 52, 38, 65, 25, 58, 70]
                    },
                    {
                        name: 'Pendapatan',
                        type: 'bar',
                        barWidth: '12',
                        itemStyle: {
                            color: 'rgba(141, 211, 199, 1)',
                            borderRadius: [4, 4, 0, 0]
                        },
                        data: [55, 42, 48, 55, 35, 48, 60]
                    }
                ]
            };
            weeklyChart.setOption(weeklyOption);

            // Resize charts when window resizes
            window.addEventListener('resize', function() {
                mainChart.resize();
                weeklyChart.resize();
            });
        });
    </script>
</body>
</html>