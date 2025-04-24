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
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('produk') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pesan</a></li>                    
                @auth
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link" style="padding: 0; border: none; background: none;">
                            Logout
                        </button>
                    </form>
                </li>
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('images/icon profil.png') }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li class="dropdown-item text-center fw-semibold">{{ Auth::user()->name }}</li>
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </nav>
       

        {{-- Promo --}}
        <h1 class="text-center my-3">Produk Terbaru</h1>
        <section class="container mt-4">
            <div class="row justify-content-center">
                @foreach ($barangs as $barang)
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
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

                

       

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
