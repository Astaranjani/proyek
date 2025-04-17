@extends('layouts.app')

@section('title', 'Dashboard Admin - E-Mebel')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Dashboard Admin</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link nav-link" style="display: inline; padding: 0; border: none; background: none;">
            Logout
        </button>
    </form>

    {{-- <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Produk</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalProduk }}</h5>
                    <p class="card-text">Jumlah semua produk yang tersedia.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Transaksi</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalTransaksi }}</h5>
                    <p class="card-text">Jumlah transaksi yang telah dilakukan.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Pelanggan</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalPelanggan }}</h5>
                    <p class="card-text">Jumlah pelanggan yang terdaftar.</p>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
