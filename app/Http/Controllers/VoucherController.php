<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
            'total_harga' => 'required|numeric',
        ]);

        $voucher = Voucher::where('kode', $request->kode)->first();

        if (!$voucher) {
            return back()->with('error', 'Kode voucher tidak ditemukan.');
        }

        $today = now()->toDateString();

        if ($today < $voucher->tanggal_mulai || $today > $voucher->tanggal_selesai) {
            return back()->with('error', 'Voucher sudah tidak berlaku.');
        }

        if ($voucher->dipakai >= $voucher->batas) {
            return back()->with('error', 'Voucher sudah habis digunakan.');
        }

        $diskon = $request->total_harga * ($voucher->diskon / 100);
        $total = $request->total_harga - $diskon;

        // simpan ke session
        session([
            'voucher_id' => $voucher->id,
            'diskon' => $diskon,
            'total_bayar' => $total,
        ]);

        return back()->with('success', "Voucher berhasil dipakai! Diskon: Rp " . number_format($diskon, 0, ',', '.'));
    }
}
