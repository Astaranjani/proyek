<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'E-Mebel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Sticky footer dengan flexbox */
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1; /* Konten utama akan mengambil ruang tersisa */
        }

        /* Style promo card dan tombol (tetap seperti kode awal) */
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

        .bayar-btn, .rating-btn, .pesan-lagi-btn {
            background-color: #000000;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel Logo" height="40" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('Riwayat Pesanan') }}">Riwayat Pesanan</a></li>
            </ul>
            <a class="nav-link" href="{{ route('keranjang') }}">
                <img src="{{ asset('images/keranjang.png') }}" alt="Keranjang" style="width: 37px; height: 23px;" />
            </a>
            @auth
                <div class="nav-item">
                    <form method="GET" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link p-0 border-0 bg-none">
                            <img src="{{ asset('images/logout.png') }}" alt="Logout" style="width: 20px; height: 20px;" />
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="container mt-5">
        <h1 class="text-center mb-4">Riwayat Pesanan</h1>

        <!-- Pesanan Selesai -->
        <div class="text-center text-muted">
            <p>Belum ada transaksi yang dilakukan.</p>
        </div>

        {{-- 
        <h1 class="text-center mb-4">Top Products</h1>
        <section class="container">
            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="card">
                        <h5>{{ $barang->nama }}</h5>
                        <p>Rp {{ number_format($barang->harga) }}</p>
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" width="100" />
                        @endif
                        <p>{{ $barang->deskripsi }}</p>
                    </div>
                @endforeach
            </div>
        </section>
        --}}
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
