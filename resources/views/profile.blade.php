<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
    <div class="profile px-4 py-5">
        <div class="header-bar mb-3">Update Profil</div>
        <div class="profile-form">
            <div class="image-upload text-center mb-3">
                @if(Auth::user()->profile_image)
                    <img id="preview" src="{{ asset('images/icon profil.png' . Auth::user()->profile_image) }}" alt="Profil" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <img id="preview" src="{{ asset('images/upload-icon.png') }}" alt="Upload" class="upload-icon rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                @endif
            </div>

            <form method="POST" action="{{ route('profile') }}">
                @csrf
                @method('PUT')

                <div class="mb-3 text-center">
                    <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)">
                </div>

                <input type="text" name="name" placeholder="Nama Lengkap" required value="{{ old('name', Auth::user()->name) }}">
                <input type="email" name="email" placeholder="Email" required value="{{ old('email', Auth::user()->email) }}">
                <input type="text" name="phone" placeholder="No. Handphone" required value="{{ old('phone', Auth::user()->phone) }}">
                <input type="text" name="gender" placeholder="Jenis Kelamin" required value="{{ old('gender', Auth::user()->gender) }}">
                <input type="text" name="address" placeholder="Alamat" required value="{{ old('address', Auth::user()->address) }}">
                <button type="submit" class="save-btn btn btn-primary mt-2">Save</button>
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
                <img src="{{  asset('images/bus kurir.png') }}" alt="Dikirim" class="status-image">
                <p>Dikirim</p>
            </div>

            <!-- Status Selesai -->
            <div class="status">
                <img src="{{ asset('images/selesai.png') }}" alt="Selesai" class="status-image">
                <p>Selesai</p>
            </div>
        </div>

        <div class="header-bar mt-5">Kontak Bantuan</div>
        <div class="contact-info">
            <p>📞 0859-6485-5724</p>
            <p>📧 emebel@gmail.com</p>
        </div>
    </div>

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
