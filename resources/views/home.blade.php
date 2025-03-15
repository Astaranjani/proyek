<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mebel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Background Image -->
    <div class="background">
        <!-- <img src="{{ asset('images/bg1.jpg') }}" alt="Hero Image"> -->
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo E-Mebel">
        </div>
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/produk') }}">Produk</a></li>
            <li><a href="{{ url('/about') }}">About</a></li>
            <li><a href="{{ url('/kontak') }}">Kontak</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Selamat Datang di E-Mebel</h1>
            <p>Jelajahi koleksi mebel terbaik untuk rumah dan kantor Anda.</p>
            <div class="buttons">
                <a href="{{ url('/produk') }}" class="btn">Lihat Produk</a>
                <a href="{{ url('/kontak') }}" class="btn">Kontak Kami</a>
            </div>
                        
            @guest
            <a href="{{ url('login') }}" class="btn login" onclick="openLoginPopup()">Login</a>
            @endguest
            {{-- @auth
            <a class="btn login" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
             {{ __('Logout') }}
         </a>

         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
             @csrf
         </form>
         @endauth --}}

            <!-- Tombol Login -->
            <!-- Jika sudah login, tampilkan tombol Logout -->
            @auth
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn logout">Logout</button>
            </form>
        @endauth
        </div>
    </header>

    {{-- <!-- Popup Login -->
    <div id="loginPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeLoginPopup()">&times;</span>
            <h2>Login</h2>
            <form>
                <input type="text" placeholder="Username" required>
                <input type="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div> --}}

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <p class="footer-subtitle">NO CREDIT CARD REQUIRED</p>
                <h2>Mulai gunakan E-Mebel hari ini.</h2>
                <div class="footer-subscribe">
                    <input type="email" placeholder="Masukkan Email Anda">
                    <button type="submit">
                        <img src="{{ asset('images/send-icon.png') }}" alt="Kirim">
                    </button>
                </div>
            </div>

            <div class="footer-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel Logo" class="footer-logo">
                <p>Pilih furnitur yang tepat untuk meningkatkan kenyamanan ruang Anda.</p>
            </div>

            <div class="footer-right">
                <div class="footer-links">
                    <div>
                        <a href="#">Tentang Kami</a>
                        <a href="#">Karir</a>
                        <a href="#">Dokumentasi</a>
                    </div>
                    <div>
                        <a href="#">Syarat dan Ketentuan</a>
                        <a href="#">Kebijakan Privasi</a>
                        <a href="#">Kebijakan Cookie</a>
                    </div>
                </div>
                <div class="footer-contact">
                    <p>Hubungi kami!</p>
                    <a href="mailto:info@e-mebel.com">info@e-mebel.com</a>
                    <div class="footer-social">
                        <a href="#"><img src="{{ asset('images/facebook.png') }}" alt="Facebook"></a>
                        <a href="#"><img src="{{ asset('images/twitter.png') }}" alt="Twitter"></a>
                        <a href="#"><img src="{{ asset('images/linkedin.png') }}" alt="LinkedIn"></a>
                        <a href="#"><img src="{{ asset('images/instagram.png') }}" alt="Instagram"></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Tambahkan Script di Akhir Body -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function openLoginPopup() {
                document.getElementById("loginPopup").style.display = "flex";
            }

            function closeLoginPopup() {
                document.getElementById("loginPopup").style.display = "none";
            }

            // Pastikan fungsi tersedia di global scope
            window.openLoginPopup = openLoginPopup;
            window.closeLoginPopup = closeLoginPopup;
        });
            document.addEventListener("DOMContentLoaded", function () {
            const navbar = document.querySelector(".navbar");

            document.addEventListener("mousemove", function (event) {
                if (event.clientY < 50) {
                    navbar.classList.add("show"); // Munculkan navbar jika mouse di dekat atas
                } else {
                    navbar.classList.remove("show"); // Sembunyikan navbar jika menjauh
                }
            });
        });
    </script>

    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
