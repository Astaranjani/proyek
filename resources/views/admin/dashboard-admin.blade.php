@extends('layouts.app')

@section('title', 'Dashboard Admin - E-Mebel')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">E-Mebel Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin"
            aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.barang.create') }}">Tambah Barang</a>
                </li>
            </ul>
            <form method="POST" action="{{ route('logout') }}" class="d-flex">
                @csrf
                <button class="btn btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Selamat Datang di Dashboard Admin</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Produk</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalProduk ?? '-' }}</h5>
                    <p class="card-text">Jumlah semua produk yang tersedia.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Transaksi</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalTransaksi ?? '-' }}</h5>
                    <p class="card-text">Jumlah transaksi yang telah dilakukan.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Pelanggan</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalPelanggan ?? '-' }}</h5>
                    <p class="card-text">Jumlah pelanggan yang terdaftar.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
