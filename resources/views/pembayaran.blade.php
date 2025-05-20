<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Halaman Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
        body {
            background-color: #f1f3f5;
        }
        .card-custom {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        h2 {
            font-weight: bold;
            color: #343a40;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-primary {
            background-color: #0bc455;
            border: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0aa64b;
        }
        .alert {
            border-radius: 10px;
        }
        .text-end {
            text-align: right;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card card-custom">
                    <h2 class="mb-4 text-center">Pembayaran</h2>

                    <form id="payment-form" method="POST" action="{{ route('pembayaran.proses') }}">
                        @csrf
                        <input type="hidden" name="payment_result" id="payment-result">

                        <div class="mb-3">
                            <input type="text" class="form-control mb-2" value="{{ auth()->user()->name }}" readonly />
                            <input type="email" class="form-control mb-2" value="{{ auth()->user()->email }}" readonly />
                            <input type="text" class="form-control mb-2" value="{{ auth()->user()->phone }}" readonly />
                            <input type="text" class="form-control" value="{{ auth()->user()->address }}" readonly />
                        </div>

                        @php
                            $total = 0;
                        @endphp

                        @if(isset($barang))
                            @php
                                $total = $barang->harga;
                            @endphp

                            <div class="table-responsive mb-4">
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
                                        <tr>
                                            <td>{{ $barang->nama }}</td>
                                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                            <td>1</td>
                                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="table-secondary fw-bold">
                                            <td colspan="3" class="text-end">Total</td>
                                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @elseif(session()->has('cart') && count(session('cart')) > 0)
                            @php
                                $cart = session('cart');
                            @endphp
                            <div class="table-responsive mb-4">
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
                                        @php
                                            $total = 0; // reset total
                                        @endphp
                                        @foreach($selectedCartItems as $item)
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
                        @else
                            <div class="alert alert-warning text-center">
                                Tidak ada produk untuk dibayar.
                            </div>
                        @endif

                        @if(isset($snapToken))
                            <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode Pembayaran</h5>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-dark w-100 py-2" id="cod-button">COD</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary w-100 py-2" id="pay-button">Bayar Sekarang</button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const payButton = document.getElementById('pay-button');
        payButton?.addEventListener('click', function(e) {
            e.preventDefault();
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    document.getElementById('payment-result').value = JSON.stringify(result);
                    document.getElementById('payment-form').submit();
                },
                onPending: function(result) {
                    document.getElementById('payment-result').value = JSON.stringify(result);
                    document.getElementById('payment-form').submit();
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    console.error(result);
                }
            });
        });

        const codButton = document.getElementById('cod-button');
        codButton?.addEventListener('click', function () {
            const nomor = "6281311394644"; // Nomor admin (format internasional tanpa +)
            const pesan = encodeURIComponent("Halo admin, saya ingin pesan dengan metode COD.");
            const url = `https://wa.me/${nomor}?text=${pesan}`;
            window.open(url, '_blank');
        });
    </script>
</body>
</html>
