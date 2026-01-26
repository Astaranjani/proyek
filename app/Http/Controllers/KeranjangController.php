<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Voucher;
use App\Models\Promo;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class KeranjangController extends Controller
{
    public function index()
{
    $cart = session()->get('cart', []);

    if (session('promo')) {
    $promo = Promo::find(session('promo')['promo_id']);
    $now = now();

    if (!$promo) {
        $message = 'Promo tidak ditemukan.';
    } elseif (!$promo->aktif) {
        $message = 'Promo tidak aktif.';
    } elseif ($promo->tanggal_berakhir && $now->gt($promo->tanggal_berakhir)) {
        $message = 'Promo sudah kadaluarsa.';
    } elseif (
        $promo->batas_penggunaan !== null &&
        $promo->jumlah_digunakan >= $promo->batas_penggunaan
    ) {
        $message = 'Promo sudah habis digunakan.';
    } else {
        $message = null;
    }

    if ($message) {
        session()->forget(['promo', 'promo_code', 'promo_success']);
        session()->flash('promo_error', $message);
    }
}
    return view('keranjang', compact('cart'));
}


    public function tambah(Request $request)
    {
        $barang = Barang::with('vouchers')->findOrFail($request->barang_id);

        $cart = session()->get('cart', []);

        // Hitung diskon voucher per item (per unit, untuk tampilan)
        $harga_asli = $barang->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;
        if ($barang->vouchers->count() > 0) {
            $voucher = $barang->vouchers->first();
            $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
            $diskon_persen = $voucher->diskon;
        }

        if (isset($cart[$barang->id])) {
            $cart[$barang->id]['jumlah'] += 1;
        } else {
            $cart[$barang->id] = [
                'barang_id'      => $barang->id,
                'nama'           => $barang->nama,
                'harga'          => $harga_asli,
                'harga_diskon'   => $harga_diskon,
                'diskon_persen'  => $diskon_persen,
                'gambar'         => $barang->gambar,
                'jumlah'         => 1,
                'stok'           => $barang->stok,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang!');
    }

    public function hapus(Request $request)
    {
        $barangId = $request->input('barang_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$barangId])) {
            unset($cart[$barangId]);
            session()->put('cart', $cart);
        }

        // Hapus promo jika ada, karena item berubah
        session()->forget('promo');
        session()->forget('promo_code');

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    // Tambahkan di KeranjangController
    public function checkVoucherApi(Request $request)
    {
        $request->validate([
            'kode' => 'required|string'
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak terautentikasi.'
            ], 401);
        }

        $voucher = Voucher::with('barangs')->where('kode', $request->kode)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan.'
            ], 404);
        }

        if ($voucher->batas_penggunaan && $voucher->jumlah_digunakan >= $voucher->batas_penggunaan) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah mencapai batas penggunaan.'
            ], 400);
        }

        if (strtolower($voucher->status) !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak aktif'
            ], 400);
        }

        $today = now()->toDateString();
        if ($voucher->tanggal_mulai && $voucher->tanggal_mulai > $today) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher belum aktif'
            ], 400);
        }
        if ($voucher->tanggal_berakhir && $voucher->tanggal_berakhir < $today) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kadaluarsa'
            ], 400);
        }

        $barangIds = $voucher->barangs->pluck('id')->toArray();

        $cartItems = Cart::with('barang')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong.'
            ], 400);
        }

        $eligibleItems = [];
        $subtotalEligible = 0;

        foreach ($cartItems as $cartItem) {
            if (!in_array($cartItem->barang_id, $barangIds)) {
                continue;
            }

            $barang = $cartItem->barang;
            $hargaSatuan = $barang->harga; // gunakan harga asli sebagai basis diskon

            $eligibleItems[] = [
                'cart_id'   => $cartItem->id,
                'barang_id' => $cartItem->barang_id,
                'nama'      => $barang->nama,
                'jumlah'    => $cartItem->quantity,
                'harga'     => $barang->harga,
            ];

            $subtotalEligible += $hargaSatuan * $cartItem->quantity;
        }

        if ($subtotalEligible <= 0 || empty($eligibleItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak berlaku untuk barang dalam keranjang.'
            ], 400);
        }

        // ===============================
        // 🔥 DISKON SEKALI PER TRANSAKSI
        // ===============================
        $tipe = $voucher->tipe ?? 'nominal';   // 'percent' atau 'nominal'
        $diskonTotal = 0;

        if ($tipe === 'percent') {
            // misal diskon = 10 => 10% dari subtotal barang eligible
            $diskonTotal = $subtotalEligible * ((float) $voucher->diskon / 100);
        } else {
            // nominal (Rp) sekali per transaksi
            $diskonTotal = (float) $voucher->diskon;
        }

        // Diskon tidak boleh lebih besar dari subtotal
        $diskonTotal = min($diskonTotal, $subtotalEligible);
        $totalAkhir = $subtotalEligible - $diskonTotal;

        return response()->json([
            'success' => true,
            'message' => 'Voucher valid.',
            'data' => [
                'kode'              => $voucher->kode,
                'tipe'              => $tipe,
                'diskon_raw'        => $voucher->diskon,
                'diskon_total'      => $diskonTotal,      // ✅ sekali per transaksi
                'subtotal_eligible' => $subtotalEligible,
                'total_akhir'       => $totalAkhir,
                'batas_penggunaan'  => $voucher->batas_penggunaan ?? null,
                'jumlah_digunakan'  => $voucher->jumlah_digunakan ?? 0,
                'barang_ids'        => $barangIds,
                'eligible_items'    => $eligibleItems,
            ]
        ], 200);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($request->produk) || empty($cart)) {
            return redirect()->back()->with('error', 'Tidak ada item terpilih untuk checkout!');
        }

        // Validasi stok untuk item terpilih
        foreach ($request->produk as $id) {
            if (!isset($cart[$id])) continue;
            $barang = Barang::find($id);
            $item = $cart[$id];
            if (!$barang || $barang->stok < $item['jumlah']) {
                return redirect()->back()->with(
                    'error',
                    "Stok barang '{$item['nama']}' tidak mencukupi (stok: {$barang->stok}, dibutuhkan: {$item['jumlah']})."
                );
            }
        }

        // Hitung total hanya untuk item terpilih
        // Diskon voucher: 1x per baris item (bukan dikali qty)
        $totalJumlah      = 0;
        $totalHargaAsli   = 0;
        $totalHargaDiskon = 0;
        $selectedCart     = [];

        foreach ($request->produk as $id) {
            if (!isset($cart[$id])) {
                continue;
            }

            $item   = $cart[$id];
            $barang = Barang::with('vouchers')->find($id);

            if (!$barang) {
                continue;
            }

            $jumlah    = $item['jumlah'];
            $hargaAsli = $barang->harga;

            // subtotal tanpa diskon
            $subtotalAsli = $hargaAsli * $jumlah;

            // cari voucher aktif (sama seperti di Blade)
            $voucherAktif = $barang->vouchers
                ->filter(function ($v) {
                    $now = \Carbon\Carbon::now();

                    if (!$v->aktif) return false;
                    if ($v->tanggal_mulai && $now->lt(\Carbon\Carbon::parse($v->tanggal_mulai))) return false;
                    if ($v->tanggal_berakhir && $now->gt(\Carbon\Carbon::parse($v->tanggal_berakhir))) return false;
                    if ($v->batas_penggunaan && $v->jumlah_digunakan >= $v->batas_penggunaan) return false;

                    return true;
                })
                ->first();

            $diskonNominal = 0;
            if ($voucherAktif) {
                // diskon hanya 1x per baris item
                $diskonNominal = $hargaAsli * ($voucherAktif->diskon / 100);
            }

            $subtotalDiskon = max(0, $subtotalAsli - $diskonNominal);

            // simpan ke selectedCart untuk tampilan di halaman pembayaran
            $selectedCart[$id] = [
                'barang_id'       => $id,
                'nama'            => $barang->nama,
                'gambar'          => $item['gambar'],
                'jumlah'          => $jumlah,
                'harga'           => $hargaAsli,
                'harga_diskon'    => $jumlah > 0 ? $subtotalDiskon / $jumlah : $hargaAsli,
                'diskon_persen'   => $voucherAktif->diskon ?? 0,
                'subtotal_asli'   => $subtotalAsli,
                'subtotal_diskon' => $subtotalDiskon,
            ];

            $totalJumlah      += $jumlah;
            $totalHargaAsli   += $subtotalAsli;
            $totalHargaDiskon += $subtotalDiskon;
        }

        // Terapkan promo jika ada (diskon sekali per transaksi)
        $promoData  = session('promo');
        $diskonPromo = 0;
        $totalAkhir  = $totalHargaDiskon;

        if ($promoData) {
            if (isset($promoData['percent']) && $promoData['percent'] > 0) {
                $diskonPromo = $totalHargaDiskon * ($promoData['percent'] / 100);
            } elseif (isset($promoData['amount']) && $promoData['amount'] > 0) {
                $diskonPromo = min($promoData['amount'], $totalHargaDiskon);
            }
            $totalAkhir = $totalHargaDiskon - $diskonPromo;
        }

        // Hapus hanya item terpilih dari cart
        foreach ($request->produk as $id) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
            }
        }
        session()->put('cart', $cart);

        return view('pembayaran', compact(
            'selectedCart',
            'totalJumlah',
            'totalHargaAsli',
            'totalHargaDiskon',
            'diskonPromo',
            'totalAkhir'
        ));
    }

    public function beliSekarang(Request $request)
    {
        $barang = Barang::with('vouchers')->findOrFail($request->product_id);

        if ($barang->stok <= 0) {
            return redirect()->back()->with('error', 'Stok barang habis, tidak bisa dibeli.');
        }

        $harga_asli = $barang->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;

        if ($barang->vouchers->count() > 0) {
            $voucher = $barang->vouchers->first();
            $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
            $diskon_persen = $voucher->diskon;
        }

        $cart = [];
        $cart[$barang->id] = [
            'barang_id'      => $barang->id,
            'nama'           => $barang->nama,
            'harga'          => $harga_asli,
            'harga_diskon'   => $harga_diskon,
            'diskon_persen'  => $diskon_persen,
            'gambar'         => $barang->gambar,
            'jumlah'         => 1,
            'stok'           => $barang->stok,
        ];

        session(['cart' => $cart]);

        return redirect()->route('pembayaran');
    }

    public function pembayaranSukses()
    {
        session()->forget('cart');
        session()->forget('promo');
        session()->forget('promo_code');
    }

    public function tambahKeranjang(Request $request, $id)
    {
        $produk = Barang::with('vouchers')->findOrFail($id);

        if ($produk->stok <= 0) {
            return redirect()->back()->with('error', 'Stok produk habis.');
        }

        $cart = session()->get('cart', []);

        $harga_asli = $produk->harga;
        $harga_diskon = $harga_asli;
        $diskon_persen = 0;

        if ($produk->vouchers->count() > 0) {
            $voucher = $produk->vouchers->first();
            $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
            $diskon_persen = $voucher->diskon;
        }

        if (isset($cart[$id])) {
            if ($cart[$id]['jumlah'] + 1 > $produk->stok) {
                return redirect()->back()->with('error', 'Jumlah barang di keranjang melebihi stok tersedia.');
            }
            $cart[$id]['jumlah'] += 1;
        } else {
            $cart[$id] = [
                'barang_id'      => $id,
                'nama'           => $produk->nama,
                'harga'          => $harga_asli,
                'harga_diskon'   => $harga_diskon,
                'diskon_persen'  => $diskon_persen,
                'gambar'         => $produk->gambar,
                'jumlah'         => 1,
                'stok'           => $produk->stok,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id   = $request->barang_id;

        if (isset($cart[$id])) {
            $barang = Barang::with('vouchers')->find($id);

            if ($barang) {
                $harga_asli = $barang->harga;
                $harga_diskon = $harga_asli;
                $diskon_persen = 0;

                if ($barang->vouchers->count() > 0) {
                    $voucher = $barang->vouchers
                        ->filter(fn($v) =>
                            $v->aktif &&
                            (!$v->masa_berlaku || \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($v->masa_berlaku))) &&
                            (!$v->batas_penggunaan || $v->jumlah_digunakan < $v->batas_penggunaan)
                        )
                        ->first();

                    if ($voucher) {
                        $harga_diskon = $harga_asli * (1 - $voucher->diskon / 100);
                        $diskon_persen = $voucher->diskon;
                    }
                }

                if ($barang->stok <= 0) {
                    $cart[$id]['jumlah'] = 0;
                } else {
                    if ($request->action === 'increase') {
                        $cart[$id]['jumlah'] = min($cart[$id]['jumlah'] + 1, $barang->stok);
                    } elseif ($request->action === 'decrease') {
                        $cart[$id]['jumlah'] = max(1, $cart[$id]['jumlah'] - 1);
                    }
                }
                $cart[$id]['harga_diskon'] = $harga_diskon;
                $cart[$id]['diskon_persen'] = $diskon_persen;
                session()->put('cart', $cart);
            }
        }

        // Validasi ulang promo jika ada
       if (session('promo')) {
    $promo = Promo::find(session('promo')['promo_id']);
    $isValid = true;

    if (
        !$promo ||
        !$promo->aktif ||
        ($promo->tanggal_mulai && $promo->tanggal_mulai->isFuture()) ||
        ($promo->tanggal_berakhir && $promo->tanggal_berakhir->isPast()) ||
        ($promo->batas_penggunaan !== null && $promo->jumlah_digunakan >= $promo->batas_penggunaan)
    ) {
        $isValid = false;
    }

    if (!$isValid) {
        session()->forget('promo');
        session()->forget('promo_code');
    }
}

        return back();
    }

    public function applyPromo(Request $request)
{
    $request->validate([
        'promo_code' => 'required|string',
        'produk' => 'required|array|min:1'
    ]);

    $promo = Promo::where('kode', $request->promo_code)->first();

    // ❌ PROMO TIDAK ADA
    if (!$promo) {
        return back()->with('promo_error', 'Promo tidak ditemukan.');
    }

    // ❌ PROMO TIDAK AKTIF
    if (!$promo->aktif) {
        return back()->with('promo_error', 'Promo tidak aktif.');
    }

    $now = now();

    // ❌ BELUM MULAI
    if ($promo->tanggal_mulai && $now->lt($promo->tanggal_mulai)) {
        return back()->with('promo_error', 'Promo belum berlaku.');
    }

    // ❌ KADALUARSA
    if ($promo->tanggal_berakhir && $now->gt($promo->tanggal_berakhir)) {
        return back()->with('promo_error', 'Promo sudah kadaluarsa.');
    }

    // ❌ HABIS KUOTA
    if (
        $promo->batas_penggunaan !== null &&
        $promo->jumlah_digunakan >= $promo->batas_penggunaan
    ) {
        return back()->with('promo_error', 'Promo sudah habis digunakan.');
    }

    // ==========================
    // ✅ PROMO VALID → LANJUT
    // ==========================
    $cart = session()->get('cart', []);
    $selectedTotal = 0;

    foreach ($request->produk as $id) {
        if (!isset($cart[$id])) continue;
        $selectedTotal += $cart[$id]['harga_diskon'] * $cart[$id]['jumlah'];
    }

    $diskon = 0;
    if ($promo->percent) {
        $diskon = $selectedTotal * ($promo->percent / 100);
    } elseif ($promo->amount) {
        $diskon = min($promo->amount, $selectedTotal);
    }

    $totalAkhir = max(0, $selectedTotal - $diskon);

    session([
        'promo' => [
            'promo_id'     => $promo->id,
            'kode'         => $promo->kode,
            'percent'      => $promo->percent ?? 0,
            'amount'       => $promo->amount ?? 0,
            'diskon'       => $diskon,
            'total_akhir'  => $totalAkhir,
            'produk'       => $request->produk,
            'min_pembelian'=> $promo->min_pembelian ?? 0,
        ],
        'promo_code' => $promo->kode,
    ]);

    return back()->with('promo_success', 'Promo berhasil diterapkan!');
}


    public function removePromo()
    {
        session()->forget(['promo', 'promo_code']);
        return back()->with('promo_success', 'Promo berhasil dihapus.');
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'kode' => 'required'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
        }

        $voucher = Voucher::with('barangs')->where('kode', $request->kode)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak ditemukan'], 404);
        }

        if (strtolower($voucher->status) !== 'aktif') {
            return response()->json(['success' => false, 'message' => 'Voucher tidak aktif'], 400);
        }

        $today = now()->toDateString();
        if ($voucher->tanggal_mulai && $voucher->tanggal_mulai > $today) {
            return response()->json(['success' => false, 'message' => 'Voucher belum aktif'], 400);
        }
        if ($voucher->tanggal_berakhir && $voucher->tanggal_berakhir < $today) {
            return response()->json(['success' => false, 'message' => 'Voucher sudah kadaluarsa'], 400);
        }

        if ($voucher->batas_penggunaan && $voucher->jumlah_digunakan >= $voucher->batas_penggunaan) {
            return response()->json(['success' => false, 'message' => 'Voucher sudah mencapai batas penggunaan'], 400);
        }

        // 🔹 Barang yang boleh kena voucher ini
        $barangIds = $voucher->barangs->pluck('id')->toArray();

        $eligibleItems     = [];
        $subtotalEligible  = 0;
        $totalQtyEligible  = 0;

        foreach ($cart as $item) {
            // Pastikan barang_id ada dan termasuk dalam daftar yang eligible
            if (!empty($item['barang_id']) && in_array($item['barang_id'], $barangIds)) {

                $eligibleItems[] = [
                    'barang_id'    => $item['barang_id'],
                    'nama'         => $item['nama'],
                    'jumlah'       => $item['jumlah'],
                    'harga'        => $item['harga'],
                    'harga_diskon' => $item['harga_diskon'] ?? $item['harga'],
                ];

                $hargaSatuan = $item['harga']; // basis diskon
                $subtotalEligible += $hargaSatuan * $item['jumlah'];
                $totalQtyEligible += $item['jumlah'];
            }
        }

        if ($subtotalEligible <= 0 || empty($eligibleItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak berlaku untuk barang dalam keranjang'
            ], 400);
        }

        // ===============================
        // 🔥 DISKON SEKALI PER TRANSAKSI
        // ===============================
        $tipe        = $voucher->tipe ?? 'nominal';  // 'percent' / 'nominal'
        $diskonTotal = 0;

        if ($tipe === 'percent') {
            $diskonTotal = $subtotalEligible * ((float) $voucher->diskon / 100);
        } else {
            $diskonTotal = (float) $voucher->diskon;
        }

        // ❗ Tidak boleh lebih besar dari subtotal
        $diskonTotal = min($diskonTotal, $subtotalEligible);
        $totalAkhir  = $subtotalEligible - $diskonTotal;

        return response()->json([
            'success' => true,
            'message' => 'Voucher valid',
            'data' => [
                'kode'              => $voucher->kode,
                'tipe'              => $tipe,
                'diskon_raw'        => $voucher->diskon,
                'diskon_total'      => $diskonTotal,          // ✅ 1x per transaksi
                'subtotal_eligible' => $subtotalEligible,
                'total_akhir'       => $totalAkhir,
                'batas_penggunaan'  => $voucher->batas_penggunaan ?? null,
                'jumlah_digunakan'  => $voucher->jumlah_digunakan ?? 0,
                'barang_ids'        => $barangIds,
                'eligible_items'    => $eligibleItems,
            ]
        ], 200);
    }

    // API: Check Promo untuk Mobile App
public function checkPromoApi(Request $request)
{
    $request->validate([
        'kode' => 'required|string'
    ]);

    $promo = Promo::where('kode', $request->kode)->first();
    $now = now();

    // ❌ Promo tidak ditemukan
    if (!$promo) {
        return response()->json([
            'success' => false,
            'message' => 'Promo tidak ditemukan'
        ], 404);
    }

    // ❌ Tidak aktif
    if (!$promo->aktif) {
        return response()->json([
            'success' => false,
            'message' => 'Promo tidak aktif'
        ], 400);
    }

    // ❌ Belum mulai
    if ($promo->tanggal_mulai && $now->lt($promo->tanggal_mulai)) {
        return response()->json([
            'success' => false,
            'message' => 'Promo belum berlaku'
        ], 400);
    }

    // ❌ Kadaluarsa
    if ($promo->tanggal_berakhir && $now->gt($promo->tanggal_berakhir)) {
        return response()->json([
            'success' => false,
            'message' => 'Promo sudah kadaluarsa'
        ], 400);
    }

    // ❌ Stok / batas penggunaan habis
    if (
        $promo->batas_penggunaan !== null &&
        $promo->jumlah_digunakan >= $promo->batas_penggunaan
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Kuota promo sudah habis'
        ], 400);
    }

    // ✅ PROMO VALID
    return response()->json([
        'success' => true,
        'data' => [
            'kode' => $promo->kode,
            'percent' => (int) ($promo->percent ?? 0),
            'amount' => (int) ($promo->amount ?? 0),
            'min_pembelian' => (int) ($promo->min_pembelian ?? 0),
            'batas_penggunaan' => $promo->batas_penggunaan,
            'jumlah_digunakan' => $promo->jumlah_digunakan,
            'tanggal_berakhir' => $promo->tanggal_berakhir,
        ]
    ], 200);
}


   public function checkoutApi(Request $request)
{
    DB::beginTransaction();

    try {
        $user = auth()->user();

        $promoData = $request->promo; // dari mobile
        $total     = $request->total;

        // =====================
        // SIMPAN ORDER
        // =====================
        $order = Order::create([
            'user_id' => $user->id,
            'total'   => $total,
            'promo_kode' => $promoData['kode'] ?? null,
            'promo_diskon' => $promoData['diskon'] ?? 0,
        ]);

        // =====================
        // UPDATE PROMO (🔥 PENTING)
        // =====================
        if (!empty($promoData['kode'])) {
            $promo = Promo::aktif()
                ->where('kode', $promoData['kode'])
                ->lockForUpdate()
                ->first();

            if (!$promo) {
                throw new \Exception('Promo sudah tidak valid');
            }

            $promo->increment('jumlah_digunakan');
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Checkout berhasil'
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Checkout gagal',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * API: Get daftar promo aktif
     */
    public function getActivePromosApi()
{
    try {
        $promos = Promo::aktif()->get();

        return response()->json([
            'success' => true,
            'data' => $promos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}