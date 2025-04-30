<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <title>{{ $barang->nama }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
{{-- Deskripsi --}}
@extends('layouts.app')

@section('title', $barang->nama)

@section('content')
<section class="product-wrapper">
    <div class="container">
        <div class="row align-items-center">
            {{-- Gambar Produk --}}
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama }}" class="product-image w-100">
            </div>

            {{-- Info Produk --}}
            <div class="col-md-6 product-info">
                <h2>{{ $barang->nama }}</h2>
                <div class="price mb-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
                <div class="rating mb-3">
                    <i class="bi bi-star-fill"></i> 4
                </div>
                <p><strong>Kategori</strong> : {{ $barang->kategori }}</p>
                <p><strong>Stok</strong> : {{ $barang->stok }}</p>
                <p><strong>Merek</strong> : {{ $barang->merek }}</p>
                <p class="mt-3">{!! nl2br(e($barang->deskripsi)) !!}</p>

                <form action="{{ route('keranjang.tambah') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-custom" type="submit">+ Keranjang</button>
                        <button class="btn btn-dark" type="button">Beli Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Keranjang --}}
<section class="keranjang-wrapper bg-light py-4">
    <div class="container">
        <h4 class="mb-3 fw-bold">Keranjang Saya</h4>
        @php $cart = session('cart', []); @endphp
        @foreach ($cart as $id => $item)
    <div class="d-flex align-items-center border-bottom py-3 justify-content-between">
        <div class="d-flex align-items-center">
            <input type="checkbox" class="form-check-input me-3">
            <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" width="100" class="me-3">
            <div>
                <div class="fw-bold">{{ $item['nama'] }}</div>
                <div>Rp. {{ number_format($item['harga'], 0, ',', '.') }}</div>
            </div>
        </div>
        {{-- Tombol Hapus --}}
        <form action="{{ route('keranjang.hapus') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini dari keranjang?');">
            @csrf
            <input type="hidden" name="barang_id" value="{{ $id }}">
            <button class="btn btn-danger btn-sm">Hapus</button>
        </form>
    </div>
    <div class="text-end mt-3">
        <a href="{{ route('keranjang.checkout') }}" class="btn btn-success">
            Checkout ({{ count($cart) }})
        </a>
    </div>    
@endforeach

{{-- Footer --}}
<footer>
    &copy; {{ date('Y') }} E-Mebel. All Rights Reserved.
</footer>

</body>
</html>
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

