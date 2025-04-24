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

        {{-- Header --}}
        <header class="text-center my-0">
            <input type="text" class="form-control w-50 mx-auto" placeholder="Search">
            <h1 class="mt-3">Dream house</h1>
            <p>Temukan mebel berkualitas dengan desain elegan untuk hunian yang lebih nyaman</p>
        </header>

        {{-- Promo --}}
        <h1 class="text-center my-3">Produk Terbaru</h1>
        <section class="container my-5">
            <div class="row justify-content-start flex-nowrap overflow-auto">
                <!-- Produk 1 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/lemari.jpeg') }}" class="img-fluid rounded shadow-sm" alt="Lemari Minimalis">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Lemari Minimalis</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 2.500.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Lemari dengan desain minimalis, cocok untuk ruang tamu.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 2 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/meja makan.jpg') }}" class="img-fluid rounded shadow-sm" alt="Meja Makan">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Meja Makan</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 1.200.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Meja makan kayu jati yang elegan dan pasti kualitas mantap.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 3 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/rak buku.jpg') }}" class="img-fluid rounded shadow-sm" alt="Rak Buku">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Rak Buku</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 800.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Rak buku minimalis cocok untuk ruang belajar membuat belajar menjadi nyaman.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 4 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/lampu hias.jpg') }}" class="img-fluid rounded shadow-sm" alt="Lampu Hias">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Lampu Hias</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 350.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Lampu hias unik yang memberi suasana hangat pada ruangan.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 5 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/kursi santai.jpg') }}" class="img-fluid rounded shadow-sm" alt="Kursi Santai">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Kursi Santai</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 1.000.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Kursi yang sangat santai nyaman untuk relaksasi di ruang tamu.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- Produk Dinamis dari DB --}}
        <h1 class="text-center mb-4 mt-5">Top Products</h1>
        <section class="container my-5">
            <div class="row justify-content-start flex-nowrap overflow-auto">
                <!-- Produk 1 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/meja rias.jpg') }}" class="img-fluid rounded shadow-sm" alt="Meja rias">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Meja rias</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 800.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Meja rias cocok untuk di taruh di kamar membuat kamar jadi elegan.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 2 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/sofa.jpg') }}" class="img-fluid rounded shadow-sm" alt="Sofa santai">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Sofa santai</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 1.000.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Sofa santai membuat kegiatan santaimu menjadi nyaman dengan sofa ini.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 3 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/kasur.jpg') }}" class="img-fluid rounded shadow-sm" alt="Kasur">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Kasur Queen</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 2.000.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Membuat tidurmu menjadi nyenyak dengan kasur yang cantik ini.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 4 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/lampu hias.jpg') }}" class="img-fluid rounded shadow-sm" alt="Lampu Hias">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Lampu Hias</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 350.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Lampu hias unik yang memberi suasana hangat pada ruangan.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Produk 5 -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('images/kursi santai.jpg') }}" class="img-fluid rounded shadow-sm" alt="Kursi Santai">
                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">Kursi Santai</h5>
                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">Rp 1.000.000</p>
                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">Kursi yang sangat santai nyaman untuk relaksasi di ruang tamu.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
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
