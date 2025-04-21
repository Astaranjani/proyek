@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Barang</h2>
        <a href="{{ route('admin.barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
    </div>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel barang --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="width: 120px;">
                            @if ($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default.png') }}" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $barang->nama }}</td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>{{ $barang->stok }}</td>
                        <td>{{ Str::limit($barang->deskripsi, 50) }}</td>
                        <td>
                            {{-- Tombol Aksi --}}
                            <a href="#" class="btn btn-sm btn-warning disabled">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger disabled">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
