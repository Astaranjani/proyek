<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- ✅ Bootstrap 5 CDN hanya untuk halaman ini -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('produk') }}">Products</a></li>
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

    <div class="profile">
        <div class="header-bar">Update Profil</div>
        <div class="profile-form">
            <div class="image-upload">
                <img src="{{ asset('images/upload-icon.png') }}" alt="Upload" class="upload-icon">
            </div>
            <form action="{{ route('profile') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="No. Handphone" required>
                <input type="text" name="gender" placeholder="Jenis Kelamin" required>
                <input type="text" name="address" placeholder="Alamat" required>
                <button type="submit" class="save-btn">Save</button>
            </form>
        </div>

        <div class="header-bar">Riwayat Pesanan</div>
        <div class="order-status">
            <!-- Status Belum Bayar -->
            <div class="status">
                <img src="{{ asset('images/dompet.png') }}" alt="Belum Bayar" class="status-image">
                <p>Belum Bayar</p>
            </div>

            <!-- Status Dikirim -->
            <div class="status">
                <img src="{{  asset('images/bus kurir.png') }}" alt="Belum Bayar" class="status-image">
                <p>Dikirim</p>
            </div>

            <!-- Status Selesai -->
            <div class="status">
                <img src="={{ asset('images/selesai.png') }}" alt="Selesai" class="status-image">
                <p>Selesai</p>
            </div>
        </div>

        <div class="header-bar">Kontak Bantuan</div>
        <div class="contact-info">
            <p>📞 0859-6485-5724</p>
            <p>📧 emebel@gmail.com</p>
        </div>
    </div>

    <!-- ✅ Bootstrap JS bundle (opsional, hanya jika pakai komponen JS Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
