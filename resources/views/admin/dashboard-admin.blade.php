@extends('layouts.app')

@section('title', 'Dashboard Admin - E-Mebel')

@section('content')
<style>
    .sidebar {
        height: 100vh;
        background-color: #343a40;
        padding-top: 20px;
        position: fixed;
        width: 220px;
    }

    .sidebar a {
        color: white;
        padding: 12px 20px;
        display: block;
        text-decoration: none;
    }

    .sidebar a:hover,
    .sidebar .active {
        background-color: #a8aaab;
        color: #fff;
    }

    .content {
        margin-left: 220px;
        padding: 20px;
    }

    .navbar-custom {
        margin-left: 220px;
        background-color: #212529;
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .btn {
        color: white;
    }

    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            height: auto;
            width: 100%;
        }

        .content,
        .navbar-custom {
            margin-left: 0;
        }
    }
</style>

<div class="sidebar">
    <h4 class="text-center text-white">E-Mebel Admin</h4>
    <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
    <a href="{{ route('admin.barang.create') }}">Tambah Barang</a>
    <a href="{{ route('admin.barang.index') }}">Data Barang</a>
    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</div>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand">Dashboard Admin</span>
    </div>
</nav>

<div class="content">
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
