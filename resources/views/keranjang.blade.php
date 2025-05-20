<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Saya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .selected-item {
            background-color: #f0f8ff;
            border-left: 4px solid #4285f4;
        }
        .select-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 15px;
            transition: all 0.3s;
        }
        .select-btn.selected {
            background-color: #4285f4;
            color: white;
            border-color: #4285f4;
        }
        .checkout-btn {
            padding: 10px 30px;
            font-size: 1.1em;
            font-weight: bold;
        }
        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .item-name {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .item-price {
            color: #28a745;
            font-weight: bold;
        }
    </style>
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
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('Riwayat Pesanan') }}">Riwayat Pesanan</a></li>
            </ul>
            <a class="nav-link" href="{{ route('keranjang') }}">
                <img src="{{ asset('images/keranjang.png') }}" alt="Keranjang" style="width: 37px; height: 23px;">
            </a>
            @auth
            <div class="nav-item">
                <form method="GET" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link" style="padding: 0; border: none; background: none;">
                        <img src="{{ asset('images/logout.png') }}" alt="Logout" style="width: 20px; height: 20px;">
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <section class="keranjang-wrapper bg-light py-4">
        <div class="container">
            <h4 class="mb-3 fw-bold">Keranjang Saya</h4>
            @php 
            $cart = session('cart', []); 
            @endphp

            @if (count($cart) === 0)
                <div class="alert alert-info">Keranjang Anda kosong.</div>
            @else
                <form action="{{ route('pembayaran') }}" method="GET" id="checkout-form">
                    @csrf
                    <div id="selected-items-container">
                        <!-- Items yang dipilih akan disimpan di sini -->
                    </div>

                    @foreach ($cart as $id => $item)
                    <div class="d-flex align-items-center border-bottom py-3 justify-content-between item-container" data-item-id="{{ $id }}">
                        <div class="d-flex align-items-center">
                            <div class="select-btn" onclick="toggleSelectItem(this, '{{ $id }}')">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" class="item-image me-3">
                            <div>
                                <div class="item-name">{{ $item['nama'] }}</div>
                                <div class="item-price">Rp. {{ number_format($item['harga'], 0, ',', '.') }}</div>
                            </div>
                        </div>

                        {{-- Tombol hapus ditaruh di luar form utama --}}
                        <div>
                           <form action="{{ route('keranjang.hapus') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini dari keranjang?');">
                        @csrf
                        <input type="hidden" name="barang_id" value="{{ $id }}">
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
                    </div>
               
           @endforeach

                    <div class="text-end mt-3">
                        <button type="button" id="checkout-button" class="btn btn-success checkout-btn" disabled onclick="submitCheckout()">
                            Checkout (<span id="selected-count">0</span> Item)
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </section>

    <script>
        let selectedItems = [];
        
        function toggleSelectItem(button, itemId) {
            const itemContainer = button.closest('.item-container');
            const isSelected = button.classList.contains('selected');
            
            if (isSelected) {
                button.classList.remove('selected');
                itemContainer.classList.remove('selected-item');
                selectedItems = selectedItems.filter(id => id !== itemId);
            } else {
                button.classList.add('selected');
                itemContainer.classList.add('selected-item');
                selectedItems.push(itemId);
            }
            
            updateCheckoutButton();
        }

        function updateCheckoutButton() {
            const checkoutButton = document.getElementById('checkout-button');
            const selectedCount = document.getElementById('selected-count');
            
            selectedCount.textContent = selectedItems.length;
            checkoutButton.disabled = selectedItems.length === 0;

            const container = document.getElementById('selected-items-container');
            container.innerHTML = '';

            selectedItems.forEach(itemId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'produk[]';
                input.value = itemId;
                container.appendChild(input);
            });
        }

        function submitCheckout() {
            if (selectedItems.length > 0) {
                document.getElementById('checkout-form').submit();
            }
        }
    </script>
</body>
</html>