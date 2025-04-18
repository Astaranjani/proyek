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
    <div id="app">
        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pesan</a></li>
                    @auth
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="display: inline; padding: 0; border: none; background: none;">
                                Logout
                            </button>
                        </form>
                    </li>
                    @endauth
                </ul>
            </div>
        </nav>

        {{-- Header --}}
        <header class="text-center my-4">
            <input type="text" class="form-control w-50 mx-auto" placeholder="Search">
            <h1 class="mt-3">Dream house</h1>
            <p>Temukan mebel berkualitas dengan desain elegan untuk hunian yang lebih nyaman</p>
        </header>

        {{-- Promo --}}
        <section class="container mt-5">
            <h2 class="text-center mb-4">Produk Terbaru</h2>
            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top" alt="{{ $barang->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="{{ $barang->nama }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $barang->nama }}</h5>
                                <p class="card-text">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                <p class="card-text">{{ $barang->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Produk Dinamis dari DB --}}
        <h1 class="text-center mb-4">Top Products</h1>
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
        </section>

        {{-- Footer --}}
        <footer class="bg-dark text-white text-center py-3 mt-4">
            &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
