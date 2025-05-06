<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Pembayaran</h2>

        @php
            $cart = session('cart', []);
            $total = 0;
        @endphp

        @if(count($cart) > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            @php
                                $subtotal = $item['harga'] * $item['jumlah'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $item['nama'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td>{{ $item['jumlah'] }}</td>
                                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Form Pembayaran --}}
            <form action="{{ route('pembayaran.proses') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Pengiriman</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-control" required></textarea>
                </div>
{{-- /*************  ✨ Windsurf Command 🌟  *************/ --}}
                <div class="mb-3">
                    <label for="metode" class="form-label">Metode Pembayaran</label>
                    <select name="metode" id="metode" class="form-select" required>
                        {{-- <option value="transfer">Transfer Bank</option> --}}
                        <option value="cod">Cash on Delivery (COD)</option>
                        <option value="bayar">Bayar</option>
                        {{-- <option value="qris">QRIS</option> --}}
                    </select>
                </div>
{{-- /*******  6d34bc7a-e234-4381-a35d-f160c9a192aa  *******/ --}}

                <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
            </form>
        @else
            <div class="alert alert-warning">
                Keranjang kosong. Silakan tambahkan produk terlebih dahulu.
            </div>
        @endif
    </div>
</body>
</html>
