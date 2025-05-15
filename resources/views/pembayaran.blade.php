<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Midtrans Snap -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
        body {
            background-color: #f1f3f5;
        }

        .card-custom {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        h2 {
            font-weight: bold;
            color: #343a40;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .alert {
            border-radius: 10px;
        }

        .text-end {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card card-custom">
                    <h2 class="mb-4 text-center">Pembayaran</h2>

                    <div class="mb-4">
                        <h5 class="fw-bold"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Alamat Pengiriman</h5>
                        <p class="mb-1">Putri Ayu Fadhilah</p>
                        <p class="mb-1">0817-4976-9912</p>
                        <p class="mb-0">Jalan Gardu Listrik Kepandean Indramayu</p>
                        <span class="badge bg-secondary mt-1">Perempuan</span>
                    </div>
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
                                            $jumlah = $item['jumlah'] ?? 1;
                                            $subtotal = $item['harga'] * $jumlah;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $item['nama'] }}</td>
                                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td>{{ $jumlah }}</td>
                                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="3" class="text-end">Total</td>
                                        <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                         <div class="mb-4">
                            <h5 class="fw-bold"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode Pembayaran</h5>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-dark w-100 py-2" id="cod-button">COD</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-dark w-100 py-2" id="pay-button">Bayar Sekarang</button>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('pembayaran') }}" method="POST" class="mt-4" id="payment-form" style="display: none;">
                            @csrf
                            <input type="hidden" name="snap_token" id="snap_token">
                            <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                        </form>
                    @else
                        <div class="alert alert-warning text-center">
                            Keranjang kosong. Silakan tambahkan produk terlebih dahulu.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Script -->
        <!-- Midtrans Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        // Tombol COD langsung redirect ke dashboard
        const codButton = document.querySelector('#cod-button');
        codButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '{{ route('dashboard') }}';
        });

        // Tombol Bayar Sekarang menggunakan Midtrans Snap
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route('dashboard') }}';
                },
                onPending: function(result) {
                    console.log(result);
                },
                onError: function(result) {
                    console.log(result);
                }
            });
        });
    </script>
</body>
</html>