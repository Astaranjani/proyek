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
                    <a href="{{ route('pembayaran') }}" class="btn btn-success">
                        Checkout ({{ count($cart) }})
                    </a>
                </div>
            </div>
        </section>
</body>
</html>

