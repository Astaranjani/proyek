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
    
    {{-- Header --}}
    <header class="text-center my-0">
        {{-- Isi header jika ada --}}
    </header>

    {{-- Promo --}}
    <h1 class="text-center my-3">Produk Terbaru</h1>
    <section class="container mt-4">
        <div class="row justify-content-start flex-nowrap overflow-auto">
    
            @foreach ($produkTerbaru as $barang)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top" alt="{{ $barang->nama }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $barang->nama }}</h5>
                        <p class="card-text text-primary">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">{{ Str::limit($barang->deskripsi, 50) }}</p>
                        <a href="{{ route('produk.detail', $barang->id) }}" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </section>
    
    {{-- Produk Dinamis dari DB --}}
    <h1 class="text-center mb-4 mt-5">Produk Kami</h1>
    <section class="container mt-4">
        <div class="row justify-content-center">
            <!-- Produk dinamis -->
            @foreach ($semuaProduk as $barang)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" 
                            class="card-img-top" 
                            alt="{{ $barang->nama }}" 
                            style="height: 180px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title mb-1" style="font-size: 1rem;">{{ $barang->nama }}</h5>
                        <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">{{ Str::limit($barang->deskripsi, 50) }}</p>
                        <a href="{{ route('produk.detail', $barang->id) }}" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    

        {{-- Footer --}}
        <footer class="bg-dark text-white text-center py-3 mt-4">
            &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
