<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Riwayat Transaksi</h1>

    @if($orders->isEmpty())
        <div class="alert alert-warning text-center">
            Belum ada transaksi yang dilakukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Harga Barang</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Ongkir</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->nama_barang }}</td>
                            <td>Rp {{ number_format($order->harga_asli ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $order->jumlah }}</td>
                            <td>Rp {{ number_format($order->subtotal ?? ($order->total_harga - ($order->ongkir ?? 0)), 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($order->ongkir ?? 0, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($order->total_bayar ?? $order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php $status = strtolower($order->status_pembayaran ?? '') @endphp
                                @if($status === 'lunas' || $status === 'settlement' || $status === 'capture' || $status === 'success')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status_pembayaran }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
