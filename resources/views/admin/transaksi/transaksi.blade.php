{{-- /*************  ✨ Windsurf Command ⭐  *************/ --}}
<table class="w-full table-auto">
    <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Order ID</th>
            <th class="px-4 py-2">Tanggal Transaksi</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaksis as $index => $transaksi)
        <tr class="text-center border-b">
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $transaksi->order_id }}</td>
            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d-m-Y') }}</td>
            <td class="px-4 py-2">{{ ucfirst($transaksi->status) }}</td>
            <td class="px-4 py-2">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- /*******  18861909-fb60-4d0b-b007-1e5f0767b45e  *******/ --}}