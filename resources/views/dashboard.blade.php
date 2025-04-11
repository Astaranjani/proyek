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
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="E-Mebel Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pesan</a></li>
                </ul>
            </div>
        </nav>

        <header class="text-center my-4">
            <input type="text" class="form-control w-50 mx-auto" placeholder="Search">
            <h1 class="mt-3">Dream house</h1>
            <p>Temukan mebel berkualitas dengan desain elegan untuk hunian yang lebih nyaman</p>
        </header>

        <section class="container text-center">
            <div class="item">
                <div class="col-lg-6">
                    <img src="{{ asset('images/sofa.png') }}" class="img-fluid" alt="Furniture" style="width: 1000px; height: auto;">
                </div>
                <div class="col-lg-6">
                    <h2>Ligno Set Sale</h2>
                    <p>Up to 50% Off</p>
                    <button class="btn btn-dark">Shopping Now</button>
                </div>
            </div>
        </section>
        <h1 class="text-center">Top Products</h1>
        <section class="container">
            <div class="item">
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/sofa 2.png') }}" class="img-fluid" alt="Sofa" style="width: 1000px; height: auto;">
                    <p>Sofa Minimalis <br> Rp. 900.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/Kursi.png') }}" class="img-fluid" alt="Bed" style="width: 1000px; height: auto;">
                    <p>Bigdream Springbed <br> Rp. 3.000.000</p>
                </div>
            </div>
        </section>

        <h1 class="text-center">Furniture kantoran</h1>
        <section class="container">
            <div class="item">
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/sofa 2.png') }}" class="img-fluid" alt="Sofa" style="width: 1000px; height: auto;">
                    <p>Sofa Minimalis <br> Rp. 900.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/Kursi.png') }}" class="img-fluid" alt="Bed" style="width: 1000px; height: auto;">
                    <p>Bigdream Springbed <br> Rp. 3.000.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/sofa 2.png') }}" class="img-fluid" alt="Sofa" style="width: 1000px; height: auto;">
                    <p>Sofa Minimalis <br> Rp. 900.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/Kursi.png') }}" class="img-fluid" alt="Bed" style="width: 1000px; height: auto;">
                    <p>Bigdream Springbed <br> Rp. 3.000.000</p>
                </div>
            </div>
            <div class="item">
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/sofa 2.png') }}" class="img-fluid" alt="Sofa" style="width: 1000px; height: auto;">
                    <p>Sofa Minimalis <br> Rp. 900.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/Kursi.png') }}" class="img-fluid" alt="Bed" style="width: 1000px; height: auto;">
                    <p>Bigdream Springbed <br> Rp. 3.000.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/sofa 2.png') }}" class="img-fluid" alt="Sofa" style="width: 1000px; height: auto;">
                    <p>Sofa Minimalis <br> Rp. 900.000</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/Kursi.png') }}" class="img-fluid" alt="Bed" style="width: 1000px; height: auto;">
                    <p>Bigdream Springbed <br> Rp. 3.000.000</p>
                </div>
            </div>
        </section>

        <footer class="bg-dark text-white text-center py-3 mt-4">
            &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
