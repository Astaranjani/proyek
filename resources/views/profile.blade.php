<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - E-Mebel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #C4B8A8;
            --primary-hover: #b3a38e;
            --bg-color: #f8f9fa;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar styling adjustments if needed */
        .navbar-brand img {
            border-radius: 5px;
        }

        /* Card Styling */
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            background: white;
        }

        .card-header-custom {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Profile Image Section */
        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .upload-btn-wrapper {
            margin-top: 15px;
            text-align: center;
        }

        .btn-upload {
            background-color: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 50px;
            padding: 5px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-upload:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Form Styling */
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary-color);
            transform: scale(.85) translateY(-.5rem) translateX(.15rem);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(196, 184, 168, 0.25);
        }

        .btn-save {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 184, 168, 0.4);
        }

        /* Contact Section */
        .contact-card {
            border: none;
            border-radius: 12px;
            background: white;
            transition: transform 0.3s;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            color: inherit;
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background-color: #fdfbf7;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        footer {
            background-color: #212529;
            margin-top: auto;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('profile') }}" style="color: #C4B8A8;">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('riwayat.pesanan') }}">Riwayat Pesanan</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <a class="nav-link me-3 position-relative" href="{{ route('keranjang') }}">
                        <img src="{{ asset('images/keranjang.png') }}" alt="Keranjang" style="width: 30px;">
                        </a>
                    @auth
                    <form method="GET" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                            Logout
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <strong>Periksa kembali inputan Anda:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-custom text-center p-4 h-100">
                    <div class="card-body">
                        <h5 class="mb-4 text-muted fw-bold">FOTO PROFIL</h5>
                        
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PUT')

                            <div class="profile-img-container">
                                @if(Auth::user()->profile_image)
                                    <img id="preview" src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" alt="Profil" class="profile-img">
                                @else
                                    <img id="preview" src="{{ asset('images/icon profil.png') }}" alt="Profil" class="profile-img">
                                @endif
                            </div>

                            <div class="upload-btn-wrapper">
                                <label for="profile_image" class="btn-upload">
                                    <i class="fas fa-camera me-1"></i> Ganti Foto
                                </label>
                                <input type="file" id="profile_image" name="profile_image" style="display: none;" accept="image/*" onchange="previewImage(event)">
                                <div class="text-muted mt-2" style="font-size: 0.8rem;">Maksimal 2MB (JPG, PNG)</div>
                            </div>

                            <hr class="my-4">
                            
                            <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
                            <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                            <p class="text-muted small mt-1">Member sejak {{ Auth::user()->created_at->format('Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom h-100">
                    <div class="card-header-custom">
                        <i class="fas fa-user-edit me-2"></i> Edit Informasi Pribadi
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name', Auth::user()->name) }}" required>
                                    <label for="name">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email', Auth::user()->email) }}" required>
                                    <label for="email">Alamat Email</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="No. HP" value="{{ old('phone', Auth::user()->phone) }}">
                                    <label for="phone">No. Handphone</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Pilih Gender</option>
                                        <option value="Laki-laki" {{ old('gender', Auth::user()->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('gender', Auth::user()->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <label for="gender">Jenis Kelamin</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Alamat Lengkap" id="address" name="address" style="height: 100px">{{ old('address', Auth::user()->address) }}</textarea>
                                    <label for="address">Alamat Lengkap</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn-save shadow">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                        </form> </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 mb-3">
                <h4 class="fw-bold text-center" style="color: #555;">Butuh Bantuan?</h4>
                <p class="text-center text-muted">Tim kami siap membantu Anda setiap hari.</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <a href="https://wa.me/6281311394644?text=Halo%20admin,%20saya%20butuh%20bantuan" target="_blank" class="contact-card p-4 d-flex align-items-center shadow-sm">
                    <div class="icon-box me-3">
                        <i class="fab fa-whatsapp"></i> </div>
                    <div>
                        <h6 class="fw-bold mb-1">Chat WhatsApp</h6>
                        <p class="mb-0 text-muted small">+62 813-1139-4644</p>
                    </div>
                    <div class="ms-auto text-muted">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>

            <div class="col-md-6 mb-3">
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=emebel.properti@gmail.com&su=Butuh%20Bantuan" target="_blank" class="contact-card p-4 d-flex align-items-center shadow-sm">
                    <div class="icon-box me-3">
                        <i class="far fa-envelope"></i> </div>
                    <div>
                        <h6 class="fw-bold mb-1">Kirim Email</h6>
                        <p class="mb-0 text-muted small">emebel.properti@gmail.com</p>
                    </div>
                    <div class="ms-auto text-muted">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <small>&copy; {{ date('Y') }} E-Mebel. All Rights Reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>