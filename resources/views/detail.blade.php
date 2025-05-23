<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $barang->nama }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 4px #0000000d;
        }
        .navbar-custom .navbar-brand img {
            height: 40px;
        }
        .navbar-custom .nav-link {
            color: #333;
            font-weight: 500;
        }
        .navbar-custom .nav-link:hover {
            color: #deeb26;
        }
        .product-image {
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.05) rotate(1deg);
            box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.3);
        }
        .btn-custom {
            background-color: #69a5ff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0a58ca;
            transform: translateY(-2px);
        }
        .btn-primary {
            background-color: #198754;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #157347;
            transform: translateY(-2px);
        }
        .product-info {
            animation: fadeInUp 0.8s ease;
        }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom px-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto"> <!-- Pindahkan ke tengah dengan mx-auto -->
            <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('riwayat.pesanan') }}">Riwayat Pesanan</a></li>
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
    
<!-- Product Detail -->
<section class="product-wrapper py-5">
    <div class="container">
        <div class="row align-items-center animate__animated animate__fadeIn">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama }}" class="product-image w-100 shadow-sm">
            </div>

            <div class="col-md-6 product-info">
                <h2 style="font-size: 2.1rem;">{{ $barang->nama }}</h2>
                <div class="price mb-2 text-success fw-bold">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
                <div class="rating mb-3 text-warning">
                    <i class="bi bi-star-fill"></i> 4
                </div>
                <p><strong>Kategori:</strong> {{ $barang->kategori }}</p>
                <p><strong>Stok:</strong> {{ $barang->stok }}</p>
                <p><strong>Merek:</strong> {{ $barang->merek }}</p>
                <p class="mt-3">{!! nl2br(e($barang->deskripsi)) !!}</p>

                <div class="d-flex gap-2 mt-4">
                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                        @csrf
                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                        <button class="btn btn-custom animate__animated animate__bounceIn" type="submit">+ Keranjang</button>
                    </form>

                    <form action="{{ route('beli.sekarang') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $barang->id }}">
                        <button type="submit" class="btn btn-primary animate__animated animate__bounceIn">Beli Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
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
