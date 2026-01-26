<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - E-Mebel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #C4B8A8;
            --primary-hover: #b3a38e;
            --bg-color: #f8f9fa;
        }
        body { background-color: var(--bg-color); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; background: white; }
        .card-header-custom { background-color: var(--primary-color); color: white; padding: 15px 20px; font-weight: 600; font-size: 1.1rem; }
        .profile-img-container { position: relative; width: 150px; height: 150px; margin: 0 auto; }
        .profile-img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 4px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.15); }
        .btn-upload { background-color: white; border: 2px solid var(--primary-color); color: var(--primary-color); border-radius: 50px; padding: 5px 20px; font-size: 0.9rem; font-weight: 600; cursor: pointer; }
        .btn-save { background-color: var(--primary-color); border: none; color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; width: 100%; }
        .contact-card { border: none; border-radius: 12px; background: white; transition: transform 0.3s; height: 100%; text-decoration: none; color: inherit; display: block; }
        .icon-box { width: 50px; height: 50px; background-color: #fdfbf7; color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 10px; }
        footer { background-color: #212529; margin-top: auto; }
        .btn-upload:focus, .btn-save:focus, .contact-card:focus { outline: 3px solid rgba(196,184,168,0.25); outline-offset: 2px; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo E-Mebel" height="40" loading="lazy">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('profile') }}" style="color: #C4B8A8;">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('riwayat.pesanan') }}">Riwayat Pesanan</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <a class="nav-link me-3 position-relative" href="{{ route('keranjang') }}" aria-label="Keranjang Belanja">
                        <img src="{{ asset('images/keranjang.png') }}" alt="Ikon keranjang" style="width: 30px;" loading="lazy">
                    </a>
                    @auth
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" aria-label="Logout">Logout</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2" aria-hidden="true"></i> <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm" novalidate>
            @csrf

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card card-custom text-center p-4 h-100">
                        <div class="card-body">
                            <h5 class="mb-4 text-muted fw-bold">FOTO PROFIL</h5>

                            <div class="profile-img-container">
                                <img id="preview"
                                     src="{{ $user->profile_image ? Storage::disk('public')->url($user->profile_image) : asset('images/icon profil.png') }}"
                                     alt="Foto profil {{ $user->name }}"
                                     class="profile-img" loading="lazy">
                            </div>

                            <div class="upload-btn-wrapper mt-3">
                                <label for="profile_image" class="btn-upload" role="button" tabindex="0">
                                    <i class="fas fa-camera me-1" aria-hidden="true"></i> Ganti Foto
                                </label>
                                <input type="file" id="profile_image" name="profile_image" style="display: none;" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                                <div class="text-muted mt-2" style="font-size: 0.8rem;">Maksimal 2MB (JPG, PNG)</div>
                            </div>

                            <hr class="my-4">

                            <h5 class="fw-bold">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                            <p class="text-muted small mt-1">Member sejak {{ $user->created_at->format('j F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-custom h-100">
                        <div class="card-header-custom">
                            <i class="fas fa-user-edit me-2" aria-hidden="true"></i> Edit Informasi Pribadi
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name', $user->name) }}" required>
                                        <label for="name">Nama Lengkap</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email', $user->email) }}" required>
                                        <label for="email">Alamat Email</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="No. HP" value="{{ old('phone', $user->phone) }}">
                                        <label for="phone">No. Handphone</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="gender" name="gender" aria-label="Jenis Kelamin">
                                            <option value="">Pilih Gender</option>
                                            @foreach(['Laki-laki', 'Perempuan'] as $genderOption)
                                                <option value="{{ $genderOption }}" {{ old('gender', $user->gender) == $genderOption ? 'selected' : '' }}>{{ $genderOption }}</option>
                                            @endforeach
                                        </select>
                                        <label for="gender">Jenis Kelamin</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Alamat Lengkap" id="address" name="address" style="height: 100px">{{ old('address', $user->address) }}</textarea>
                                        <label for="address">Alamat Lengkap</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn-save shadow" id="saveBtn">
                                    <i class="fas fa-save me-2" aria-hidden="true"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row mt-5">
            <div class="col-12 mb-3">
                <h4 class="fw-bold text-center" style="color: #555;">Butuh Bantuan?</h4>
                <p class="text-center text-muted">Tim kami siap membantu Anda setiap hari.</p>
            </div>

            @php
                $contacts = [
                    ['link' => 'https://wa.me/6281311394644','icon' => 'fab fa-whatsapp','title' => 'Chat WhatsApp','text' => '+62 813-1139-4644'],
                    ['link' => 'mailto:emebel.properti@gmail.com','icon' => 'far fa-envelope','title' => 'Kirim Email','text' => 'emebel.properti@gmail.com'],
                ];
            @endphp

            <div class="row mt-3">
                @foreach($contacts as $contact)
                <div class="col-md-6 mb-3">
                    <a href="{{ $contact['link'] }}" target="_blank" class="contact-card p-4 d-flex align-items-center shadow-sm" rel="noopener noreferrer">
                        <div class="icon-box me-3"><i class="{{ $contact['icon'] }}" aria-hidden="true"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $contact['title'] }}</h6>
                            <p class="mb-0 text-muted small">{{ $contact['text'] }}</p>
                        </div>
                        <div class="ms-auto text-muted"><i class="fas fa-chevron-right" aria-hidden="true"></i></div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container"><small>&copy; {{ date('Y') }} E-Mebel. All Rights Reserved.</small></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>

    <script>
        function humanFileSize(bytes) {
            const thresh = 1024;
            if (Math.abs(bytes) < thresh) return bytes + ' B';
            const units = ['KB','MB','GB','TB'];
            let u = -1;
            do { bytes /= thresh; ++u; } while(Math.abs(bytes) >= thresh && u < units.length - 1);
            return bytes.toFixed(1)+' '+units[u];
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const maxBytes = 2 * 1024 * 1024;
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung. Gunakan JPG atau PNG.');
                event.target.value = '';
                return;
            }

            if (file.size > maxBytes) {
                alert('Ukuran file terlalu besar: ' + humanFileSize(file.size) + '. Maksimum 2MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview');
                output.src = reader.result;
                output.setAttribute('alt', 'Preview foto profil');
            }
            reader.readAsDataURL(file);
        }

        document.getElementById('profileForm').addEventListener('submit', function() {
            const btn = document.getElementById('saveBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
        });

        document.querySelectorAll('.btn-upload').forEach(function(label) {
            label.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    document.getElementById('profile_image').click();
                }
            });

            label.addEventListener('click', function() {
                document.getElementById('profile_image').click();
            });
        });
    </script>
</body>
</html>
