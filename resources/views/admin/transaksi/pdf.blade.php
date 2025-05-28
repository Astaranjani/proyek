<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Laporan Daftar Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Barang</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->user->nama ?? $t->nama_user }}</td>
                    <td>{{ $t->barang->nama ?? $t->nama_barang }}</td>
                    <td>Rp{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $t->status_pembayaran }}</td>
                    <td>{{ date('d M Y', strtotime($t->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
    <tr>
        <td colspan="3"><strong>Total Semua Pembayaran</strong></td>
        <td colspan="3"><strong>Rp{{ number_format($totalSemuaPembayaran, 0, ',', '.') }}</strong></td>
    </tr>
</tfoot>
    </table>
</body>
</html>
