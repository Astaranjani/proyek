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
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('riwayat.pesanan') }}">Riwayat Pesanan</a></li>
            </ul>

            <a class="nav-link" href="{{ route('keranjang') }}">
                <img src="{{ asset('images/keranjang.png') }}" alt="Keranjang" style="width: 37px; height: 23px;">
            </a>

            @auth
            <a href="{{ route('logout') }}" 
               class="nav-link d-flex align-items-center ms-2" 
               title="Logout">
                <img src="{{ asset('images/logout.png') }}" 
                     alt="Logout" 
                     style="width: 20px; height: 20px;">
            </a>
            @endauth
        </div>
    </div>
</nav>

    {{-- Search Form --}}
    <section class="container mt-4">
        <form action="{{ url('dashboard') }}" method="GET" class="d-flex justify-content-center mb-3">
            <input type="text" name="search" class="form-control w-50" placeholder="Cari produk..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">Cari</button>
        </form>

        {{-- Filter Harga --}}
        @php $query = request()->query(); @endphp
        <div class="d-flex justify-content-center mb-3">
            <div class="btn-group" role="group">
                <a href="{{ route('dashboard', array_merge($query, ['sort' => 'price_asc'])) }}"
                   class="btn btn-outline-primary {{ request('sort') == 'price_asc' ? 'active' : '' }}">
                   Murah ke Mahal
                </a>
                <a href="{{ route('dashboard', array_merge($query, ['sort' => 'price_desc'])) }}"
                   class="btn btn-outline-primary {{ request('sort') == 'price_desc' ? 'active' : '' }}">
                   Mahal ke Murah
                </a>
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="container mt-4">
        <div class="row text-center mb-4">
            <div class="col-3">
                <a href="{{ route('dashboard', ['kategori' => 'Sofa']) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('images/armchair.png') }}" alt="Sofa" class="img-fluid" style="width: 80px;">
                    <p class="mt-2">Sofa</p>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('dashboard', ['kategori' => 'Kasur']) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('images/bed.png') }}" alt="Bed" class="img-fluid" style="width: 80px;">
                    <p class="mt-2">Kasur</p>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('dashboard', ['kategori' => 'Lemari']) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('images/cabinet-drawer.png') }}" alt="Cabinet" class="img-fluid" style="width: 80px;">
                    <p class="mt-2">Lemari</p>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('dashboard', ['kategori' => 'Meja']) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('images/coffee-table.png') }}" alt="Table" class="img-fluid" style="width: 80px;">
                    <p class="mt-2">Meja</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Produk Dinamis --}}
    <h1 class="text-center mb-4 mt-5">Produk Kami</h1>
    <section class="container mt-4">
        <div class="row justify-content-center">
            @forelse ($semuaProduk as $barang)
              @php
    $voucherAktif = $barang->vouchers
        ->filter(fn($v) =>
            $v->aktif &&
            (!$v->masa_berlaku || \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($v->masa_berlaku))) &&
            (!$v->batas_penggunaan || $v->jumlah_digunakan < $v->batas_penggunaan)
        )
        ->first();

    $hargaAwal = $barang->harga;

    // Diskon dihitung jika ada voucher aktif
    $diskonPersen = $voucherAktif ? $voucherAktif->diskon : 0;

    // Hitung harga setelah diskon
    $hargaAkhir = $hargaAwal - ($hargaAwal * $diskonPersen / 100);

    // Jika user nanti beli lebih dari 1, bisa dihitung kelipatannya
    // contoh: untuk preview di dashboard tetap satuan dulu
@endphp


                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 position-relative">

                        {{-- Label Diskon --}}
                        @if($voucherAktif)
                            <span class="position-absolute top-0 start-0 bg-danger text-white px-1 py-0"
                                  style="font-size: 0.65rem; border-bottom-right-radius: 4px;">
                                Diskon {{ $voucherAktif->diskon }}%
                            </span>
                        @endif

                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" 
                                 class="card-img-top img-fluid"
                                 alt="{{ $barang->nama }}" 
                                 style="height: 250px; object-fit: cover; border-radius: 8px;">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title mb-1" style="font-size: 1rem;">{{ $barang->nama }}</h5>

                            {{-- Harga --}}
                            @if($voucherAktif)
                                <p class="card-text text-muted mb-1" style="font-size: 0.8rem; text-decoration: line-through;">
                                    Rp {{ number_format($hargaAwal, 0, ',', '.') }}
                                </p>
                            @endif

                            <p class="card-text text-primary fw-bold mb-1" style="font-size: 0.9rem;">
                                Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                            </p>

                            <p class="card-text text-muted mb-2" style="font-size: 0.85rem;">
                                {{ Str::limit($barang->deskripsi, 50) }}
                            </p>
                            <a href="{{ route('produk.detail', $barang->id) }}" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Tidak ada produk yang ditemukan. Coba ubah pencarian atau filter.</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $semuaProduk->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
