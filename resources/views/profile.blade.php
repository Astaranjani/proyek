<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <style>
        .header-bar {
            background-color: #C4B8A8;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .contact-box {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 24px;
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        .contact-button {
            display: block;
            background-color: #C4B8A8;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: background-color 0.3s ease;
        }

        .contact-button:hover {
            background-color: #b3a38e;
        }

        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .save-btn {
            background-color: #C4B8A8;
            color: #fff;
            border: none;
        }

        .save-btn:hover {
            background-color: #b3a38e;
        }
    </style>
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
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('Riwayat Pesanan') }}">Riwayat Pesanan</a></li>
            </ul>
            <a class="nav-link" href="{{ route('keranjang') }}">
                <img src="{{ asset('images/keranjang.png') }}" alt="Keranjang" style="width: 37px; height: 23px;">
            </a>
            @auth
            <form method="GET" action="{{ route('logout') }}" class="ms-2">
                @csrf
                <button type="submit" class="btn btn-link nav-link p-0" style="border: none; background: none;">
                    <img src="{{ asset('images/logout.png') }}" alt="Logout" style="width: 20px; height: 20px;">
                </button>
            </form>
            @endauth
        </div>
    </nav>

    <div class="profile px-4 py-5">
        <div class="header-bar">Update Profil</div>
        <div class="profile-form">
            <div class="image-upload text-center mb-3">
                @if(Auth::user()->profile_image)
                    <img id="preview" src="{{ asset('images/icon profil.png' . Auth::user()->profile_image) }}" alt="Profil" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <img id="preview" src="{{ asset('images/icon profil.png') }}" alt="Profil" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                @endif
            </div>

            <form method="POST" action="{{ route('profile') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <input type="text" name="name" placeholder="Nama Lengkap" required value="{{ old('name', Auth::user()->name) }}">
                <input type="email" name="email" placeholder="Email" required value="{{ old('email', Auth::user()->email) }}">
                <input type="text" name="phone" placeholder="No. Handphone" required value="{{ old('phone', Auth::user()->phone) }}">
                <input type="text" name="gender" placeholder="Jenis Kelamin" required value="{{ old('gender', Auth::user()->gender) }}">
                <input type="text" name="address" placeholder="Alamat" required value="{{ old('address', Auth::user()->address) }}">
                <button type="submit" class="save-btn btn mt-2">Save</button>
            </form>
        </div>
    </div>

    <div class="header-bar mt-5">Kontak Bantuan</div>
    <div class="contact-box">
        <h2>Kontak Bantuan</h2>
        <p>Klik nomor di bawah untuk langsung chat via WhatsApp:</p>

        <a 
            href="https://wa.me/6281311394644?text=Halo%20admin,%20saya%20butuh%20bantuan"
            target="_blank"
            class="contact-button"
        >
            +62 813-1139-4644 (WhatsApp)
        </a>

        <a 
            href="https://mail.google.com/mail/?view=cm&fs=1&to=bantuan@example.com&su=Butuh%20Bantuan&body=Halo%20admin,%20saya%20memerlukan%20bantuan."
            target="_blank"
            class="contact-button"
        >
            emebel.prope.com (Email)
        </a>
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
