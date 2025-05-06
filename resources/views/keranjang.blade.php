<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Saya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    {{-- Navbar --}}
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
                <li class="nav-item"><a class="nav-link" href="{{ route('keranjang') }}">Lihat Keranjang</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pesan</a></li>
                @auth
                <li class="nav-item">
                    <form method="GET" action="{{ route('logout') }}">
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
        <section class="keranjang-wrapper bg-light py-4">
            <div class="container">
                <h4 class="mb-3 fw-bold">Keranjang Saya</h4>
                @php $cart = session('cart', []); @endphp
                @foreach ($cart as $id => $item)
                <div class="d-flex align-items-center border-bottom py-3 justify-content-between">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3">
                        <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" width="100" class="me-3">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold">{{ $item['nama'] }}</div>
                            <div>Rp. {{ number_format($item['harga'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('keranjang.hapus') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini dari keranjang?');">
                        @csrf
                        <input type="hidden" name="barang_id" value="{{ $id }}">
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
                @endforeach

                <div class="text-end mt-3">
                    <a href="{{ route('keranjang.checkout') }}" class="btn btn-success">
                        Checkout ({{ count($cart) }})
                    </a>
                </div>
            </div>
        </section>
</body>
</html>

