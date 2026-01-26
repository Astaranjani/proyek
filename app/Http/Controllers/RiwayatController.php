<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Barang;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil transaksi + relasi barang dan voucher
        $orders = Transaksi::with(['barang.vouchers', 'user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                $barang = $order->barang;

                if (!$barang) {
                    // Kalau data barang hilang atau dihapus
                    $order->nama_barang = $order->nama_barang ?? '-';
                    $order->harga_asli = $order->harga_asli ?? 0;
                    $order->diskon = $order->diskon ?? 0;
                    $order->harga_akhir = $order->harga_akhir ?? $order->total_harga;
                    $order->jumlah = $order->jumlah ?? 1;
                    $order->ongkir = $order->ongkir ?? 0;
                    $order->subtotal = ($order->total_harga ?? 0) - ($order->ongkir ?? 0);
                    $order->total_bayar = $order->total_harga ?? 0;
                    return $order;
                }

                // Harga asli barang
                $harga = $barang->harga ?? 0;

                // Cek voucher aktif (sinkron dengan dashboard & pembayaran)
                $voucherAktif = $barang->vouchers
                    ->filter(fn($v) =>
                        $v->aktif &&
                        (!$v->masa_berlaku || now()->lte(Carbon::parse($v->masa_berlaku))) &&
                        (!$v->batas_penggunaan || $v->jumlah_digunakan < $v->batas_penggunaan)
                    )
                    ->first();

                $diskonPersen = $voucherAktif?->diskon ?? 0;

                // Hitung harga akhir produk per unit
                $hargaAkhir = $voucherAktif
                    ? $harga * (1 - $diskonPersen / 100)
                    : $harga;

                $jumlah = $order->jumlah ?? 1;
                $subtotal = $hargaAkhir * $jumlah;

                // Gunakan total dari database jika ada, atau subtotal yang dihitung ditambah ongkir
                $totalBayar = $order->total_harga ?? ($subtotal + ($order->ongkir ?? 0));

                // Tambahkan ke objek untuk ditampilkan di view
                $order->nama_barang = $order->nama_barang ?? $barang->nama;
                $order->harga_asli = $harga;
                $order->diskon = $diskonPersen;
                $order->harga_akhir = $hargaAkhir;
                $order->jumlah = $jumlah;
                $order->ongkir = $order->ongkir ?? 0;
                $order->subtotal = $subtotal;
                $order->total_bayar = $totalBayar;

                return $order;
            });

        return view('riwayat', compact('orders'));
    }
}
