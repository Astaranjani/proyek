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

                        <div class="text-center mt-4">
                            <button class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
                        </div>

                        <form action="{{ route('pembayaran.proses') }}" method="POST" class="mt-4" id="payment-form" style="display: none;">
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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();
    
            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                }
            });
        });
    </script>
</body>
</html>
