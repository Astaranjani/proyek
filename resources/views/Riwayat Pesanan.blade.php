<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Mebel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel Logo" height="40">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto"> <!-- Pindahkan ke tengah dengan mx-auto -->
            <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('Riwayat Pesanan') }}">Riwayat Pesanan</a></li>
        </ul>
                <a class="nav-link" href="{{ route('keranjang') }}">
                    <img src="{{ asset('images/keranjang.png') }}" alt="Keranjang" style="width: 37px; height: 23px;">
                </a>
                @auth
                <class="nav-item">
                    <form method="GET" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link" style="padding: 0; border: none; background: none;">
                        <img src="{{ asset('images/logout.png') }}" alt="Logout" style="width: 20px; height: 20px;">
                    </button>
                    </form>
                @endauth
        </div>
    </nav>
    
        {{-- Promo --}}
        <style>
            .order-card {
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 20px;
                margin: 15px 0;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                background-color: #f9f9f9;
            }
    
            .order-card .order-image {
                max-width: 120px;
                max-height: 120px;
                object-fit: cover;
            }
    
            .order-card h5 {
                font-size: 1.2rem;
                font-weight: bold;
            }
    
            .order-card p {
                font-size: 1rem;
            }
    
            .order-status {
                padding: 5px 10px;
                border-radius: 20px;
                color: white;
                font-weight: bold;
            }
    
            .belum-bayar { background-color:  hsl(60, 45%, 86%); }
            .dikirim { background-color: hsl(60, 45%, 86%); }
            .selesai { background-color: hsl(60, 45%, 86%); }
    
            .order-action-btn {
                margin-top: 10px;
            }
    
            .order-action-btn button {
                padding: 10px 20px;
                border: none;
                font-weight: bold;
                border-radius: 5px;
                cursor: pointer;
            }
    
            .bayar-btn { background-color: #000000; color: white; }
            .rating-btn { background-color: #000000; color: white; }
            .pesan-lagi-btn { background-color: #000000; color: white; }
        </style>
    </head>
    <body>
    
        <div class="container mt-5">
            <h1 class="text-center mb-4">Riwayat Pesanan</h1>
    
            <!-- Belum Bayar -->
            <h1>Belum Bayar</h1>
            <div class="order-card">
                <div class="row">
                    <div class="col-md-3">
                        <img src="https://via.placeholder.com/120" class="order-image" alt="Set Mezzanine Beed">
                    </div>
                    <div class="col-md-9">
                        <h5>Set Mezzanine Beed</h5>
                        <p>Rp 3.000.000</p>
                        <p class="order-status selesai">Selesai</p>
                        <div class="order-action-btn">
                            <button class="rating-btn">Rating Produk</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dikirim -->
            <h1>Dikirim</h1>
            <div class="order-card">
                <div class="row">
                    <div class="col-md-3">
                        <img src="https://via.placeholder.com/120" class="order-image" alt="Bigdream Springbed">
                    </div>
                    <div class="col-md-9">
                        <h5>Bigdream Springbed</h5>
                        <p>Rp 3.000.000</p>
                        <p class="order-status dikirim">Sedang Dikirim</p>
                    </div>
                </div>
            </div>
    
            <!-- Pesanan Selesai -->
            <h1>Selesai</h1>
            <div class="order-card">
                <div class="row">
                    <div class="col-md-3">
                        <img src="https://via.placeholder.com/120" class="order-image" alt="Set Mezzanine Beed">
                    </div>
                    <div class="col-md-9">
                        <h5>Set Mezzanine Beed</h5>
                        <p>Rp 3.000.000</p>
                        <p class="order-status selesai">Selesai</p>
                        <div class="order-action-btn">
                            <button class="rating-btn">Rating Produk</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 p-3" style="border: 1px solid #ccc; border-radius: 10px; background-color: #fff;">
            <h6 class="fw-bold mb-3">Rating Produk</h6>
            <div class="d-flex align-items-center mb-2">
                <img src="https://via.placeholder.com/80" class="me-3" style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                    <p class="mb-1 fw-semibold">Set Mezzanine Beed</p>
                    <p class="mb-1">Rp. 3.000.000</p>
                </div>
            </div>
            <div class="mb-2">
                <span class="fw-semibold">Kualitas Produk:</span><br>
                <span style="color: gold; font-size: 20px;">&#9733; &#9733; &#9733; &#9733; &#9733;</span>
            </div>
            <button class="btn btn-sm" style="background-color: #b9afa5; color: white;">Kirim</button>
        </div>
    </div>
</div>
</div>
</div>

        
    
        {{-- <h1 class="text-center mb-4">Top Products</h1>
        <section class="container">
            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="card">
                        <h5>{{ $barang->nama }}</h5>
                        <p>Rp {{ number_format($barang->harga) }}</p>
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" width="100">
                        @endif
                        <p>{{ $barang->deskripsi }}</p>
                    </div>
                @endforeach

            </div>
        </section> --}}

        {{-- Footer --}}
        <footer class="bg-dark text-white text-center py-3 mt-4">
            &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
