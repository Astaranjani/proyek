<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Carbon\Carbon;

class ProductController extends Controller
{
    // ============================
    // GET LIST PRODUK
    // ============================
    public function index()
    {
        $products = Barang::with('vouchers')->get();

        // mapping setiap barang menjadi format yang sudah berisi discount efektif
        $mapped = $products->map(function ($barang) {
            return $this->mapProductWithEffectiveDiscount($barang);
        });

        return response()->json([
            'data' => $mapped
        ]);
    }

    // ============================
    // GET DETAIL PRODUK
    // ============================
    public function show($id)
    {
        $barang = Barang::with('vouchers')->findOrFail($id);

        return response()->json([
            'data' => $this->mapProductWithEffectiveDiscount($barang)
        ]);
    }

    // ============================
    // HELPER DISKON EFEKTIF
    // ============================
    protected function mapProductWithEffectiveDiscount($barang)
    {
        $now = Carbon::now();

        // diskon bawaan produk
        $baseDiscount = (int) ($barang->discount ?? 0);

        $voucherAktif = $barang->vouchers
            ? $barang->vouchers->filter(function ($v) use ($now) {
                return $v->aktif
                    && (!$v->tanggal_mulai || $now->gte(Carbon::parse($v->tanggal_mulai)))
                    && (!$v->tanggal_berakhir || $now->lte(Carbon::parse($v->tanggal_berakhir)))
                    && (!$v->batas_penggunaan || $v->jumlah_digunakan < $v->batas_penggunaan);
            })->sortByDesc('diskon')->first()
            : null;

        if ($voucherAktif) {
            $effectiveDiscount = (int) $voucherAktif->diskon;
            $hasActiveVoucher = true;
        } else {
            $effectiveDiscount = $baseDiscount;
            $hasActiveVoucher = false;
        }

        return [
            'id'       => $barang->id,
            'nama'     => $barang->nama,
            'harga'    => $barang->harga,
            'stok'     => $barang->stok,
            'kategori' => $barang->kategori,
            'gambar'   => $barang->gambar,
            'deskripsi'=> $barang->deskripsi,

            // mobile hanya baca ini
            'discount'          => $effectiveDiscount,
            'has_active_voucher'=> $hasActiveVoucher,
        ];
    }
}
