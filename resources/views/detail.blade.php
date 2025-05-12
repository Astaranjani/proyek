<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $barang->nama }}</title>
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

    {{-- Konten --}}
    <section class="product-wrapper">
        <div class="container">
            <div class="row align-items-center">
                {{-- Gambar Produk --}}
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama }}" class="product-image w-100">
                </div>

                {{-- Info Produk --}}
                <div class="col-md-6 product-info">
                    <h2 style="font-size: 2.1rem;">{{ $barang->nama }}</h2>
                    <div class="price mb-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
                    <div class="rating mb-3">
                        <i class="bi bi-star-fill"></i> 4
                    </div>
                    <p><strong>Kategori</strong> : {{ $barang->kategori }}</p>
                    <p><strong>Stok</strong> : {{ $barang->stok }}</p>
                    <p><strong>Merek</strong> : {{ $barang->merek }}</p>
                    <p class="mt-3">{!! nl2br(e($barang->deskripsi)) !!}</p>

                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                        @csrf
                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-custom" type="submit">+ Keranjang</button>
                            <button class="btn btn-dark" type="button" onclick="toggleCheckout()">Beli Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Keranjang --}}
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
    {{-- Pembayaran --}}
    <div id="checkout-section" class="mt-5" style="display: none;">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-bag-check-fill me-2"></i>Checkout</h5>
            </div>
            <div class="card-body">
                {{-- Alamat Pengiriman --}}
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Alamat Pengiriman</h6>
                    <p class="mb-1">Putri Ayu Fadhilah</p>
                    <p class="mb-1">0817-4976-9912</p>
                    <p class="mb-0">Jalan Gardu Listrik Kepandean Indramayu</p>
                    <span class="badge bg-secondary mt-1">Perempuan</span>
                </div>
    
                {{-- Produk Dipesan --}}
                <div class="d-flex align-items-center border p-3 rounded mb-4">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" width="100" class="rounded me-3">
                    <div>
                        <h6 class="mb-1">{{ $barang->nama }}</h6>
                        <span class="text-muted">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                    </div>
                    </div>
        
                    {{-- Metode Pembayaran --}}
                    <div class="mb-4">
                        <h6 class="fw-bold"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode Pembayaran</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-dark w-50">COD (Cash On Delivery)</button>
                            <button type="button" class="btn btn-outline-dark w-50">Transfer</button>
                </div>
            </div>

            {{-- Rincian Pembayaran --}}
            <div class="mb-4">
                <h6 class="fw-bold"><i class="bi bi-receipt-cutoff me-2 text-warning"></i>Rincian Pembayaran</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Ongkos Kirim</span>
                        <span>Rp 20.000</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Proteksi Kerusakan</span>
                        <span>Rp 3.000</span>
                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-cart-check-fill me-2"></i>Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function toggleCheckout() {
                const checkout = document.getElementById('checkout-section');
                checkout.style.display = checkout.style.display === 'none' ? 'block' : 'none';
        checkout.scrollIntoView({ behavior: 'smooth' });
    }
</script>


    {{-- Footer --}}
    <footer class="text-center mt-5 mb-3 text-muted">
        &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>