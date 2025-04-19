@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="container">
    <div class="card p-4">
        <h2>Tambah Barang</h2>

        <!-- Menampilkan pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Menampilkan pesan error -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form tambah barang -->
        <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required autofocus>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" required>
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok') }}" required>
                @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            

            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
        </form>
    </div>
</div>
@endsection
