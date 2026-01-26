<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Promo;

class PembayaranController extends Controller
{
    protected $dataOngkir = [
        'Indramayu'      => 10000,
        'Balongan'       => 12000,
        'Sindang'        => 10000,
        'Jatibarang'     => 15000,
        'Lohbener'       => 15000,
        'Juntinyuat'     => 18000,
        'Karangampel'    => 20000,
        'Kertasemaya'    => 22000,
        'Kandanghaur'    => 25000,
        'Losarang'       => 25000,
        'Haurgeulis'     => 30000,
        'Gantar'         => 35000,
        'Patrol'         => 30000,
        'Sukra'          => 35000,
        'Anjatan'        => 32000,
        'Bongas'         => 32000,
        'Gabuswetan'     => 30000,
        'Cikedung'       => 28000,
        'Lelea'          => 20000,
        'Widasari'       => 18000,
        'Tukdana'        => 22000,
        'Bangodua'       => 22000,
        'Krangkeng'      => 25000,
        'Cantigi'        => 15000,
        'Arahan'         => 18000,
        'Pasekan'        => 15000,
        'Sliyeg'         => 18000,
        'Kedokan Bunder' => 20000,
        'Kroya'          => 30000,
        'Terisi'         => 28000,
    ];

    private function initMidtrans()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Halaman pembayaran
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $transaksi       = null;
        $barang          = null;
        $selectedItems   = [];
        $totalBarang     = 0;
        $subtotalAsli    = 0;
        $voucherDiscount = 0;
        $subtotalDiskon  = 0;
        $promoDiscount   = 0;
        $finalTotal      = 0;
        $totalBerat      = 0;

        // =========================
        // 1. MODE BELI SEKARANG
        // =========================
        if ($request->has('transaksi_id')) {
            $transaksi = Transaksi::find($request->transaksi_id);

            if ($transaksi) {
                $barang = Barang::find($transaksi->barang_id);

                $totalBarang     = (int) $transaksi->total_harga;
                $subtotalAsli    = $totalBarang;
                $subtotalDiskon  = $totalBarang;
                $finalTotal      = $totalBarang;
                $voucherDiscount = 0;
                $promoDiscount   = 0;
                $totalBerat      = $barang->berat ?? 1000;

                $selectedItems = [
                    [
                        'nama'     => $barang->nama,
                        'harga'    => $barang->harga,
                        'jumlah'   => 1,
                        'subtotal' => $totalBarang,
                        'berat'    => $barang->berat ?? 1000,
                    ]
                ];
            }
        }

        // =========================
        // 2. MODE CHECKOUT KERANJANG
        // =========================
        else {
            $produkIds = (array) $request->input('produk', []);
            $cart      = session('cart', []);
            $promoData = session('promo', null);

            foreach ($produkIds as $id) {
                if (!isset($cart[$id])) {
                    continue;
                }

                $item       = $cart[$id];
                $barangItem = Barang::with('vouchers')->find($id);
                if (!$barangItem) {
                    continue;
                }

                // 🔒 CEK STOK
                $jumlah = $item['jumlah'] ?? 1;
                if ($barangItem->stok < $jumlah) {
                    return redirect()->back()->with(
                    'error',
                    "Stok produk {$barangItem->nama} sudah habis."
                );
                }

                $hargaAsli = $barangItem->harga;
                $jumlah    = $item['jumlah'] ?? 1;
                $beratItem = ($barangItem->berat ?? 1000) * $jumlah;

                // subtotal sebelum voucher
                $subAsli       = $hargaAsli * $jumlah;
                $subtotalAsli += $subAsli;
                $totalBerat   += $beratItem;

                // voucher AKTIF (sama persis seperti di keranjang.blade)
                $voucherAktif = $barangItem->vouchers
                    ->filter(function ($v) {
                        $now = Carbon::now();

                        if (!$v->aktif) return false;
                        if ($v->tanggal_mulai && $now->lt(Carbon::parse($v->tanggal_mulai))) return false;
                        if ($v->tanggal_berakhir && $now->gt(Carbon::parse($v->tanggal_berakhir))) return false;
                        if ($v->batas_penggunaan && $v->jumlah_digunakan >= $v->batas_penggunaan) return false;

                        return true;
                    })
                    ->first();

                // ✅ Diskon voucher 1x per BARIS item (bukan per qty) – sama seperti keranjang
                $diskonNominal = 0;
                if ($voucherAktif) {
                    $diskonNominal = $hargaAsli * ($voucherAktif->diskon / 100);
                }

                $subDiskon       = max(0, $subAsli - $diskonNominal);
                $subtotalDiskon += $subDiskon;
                $voucherDiscount += $diskonNominal;

                $selectedItems[] = [
                    'nama'     => $item['nama'],
                    'harga'    => $hargaAsli,
                    'jumlah'   => $jumlah,
                    'subtotal' => $subDiskon,
                    'berat'    => $beratItem,
                    'barang_id' => $id,
                ];
            }

            // === HITUNG PROMO (SAMA DENGAN KERANJANG) ===
            if ($promoData) {
                $percent = (float) ($promoData['percent'] ?? 0);
                $amount  = (float) ($promoData['amount']  ?? 0);

                if ($percent > 0) {
                    // diskon = persen * subtotalDiskon
                    $promoDiscount = $subtotalDiskon * ($percent / 100);
                } elseif ($amount > 0) {
                    // diskon nominal satu kali per transaksi
                    $promoDiscount = min($amount, $subtotalDiskon);
                }

                // safety
                $promoDiscount = (int) round(min($promoDiscount, $subtotalDiskon));
                $finalTotal    = (int) max(0, $subtotalDiskon - $promoDiscount);
            } else {
                $finalTotal = (int) $subtotalDiskon;
            }

            $subtotalAsli    = (int) $subtotalAsli;
            $voucherDiscount = (int) $voucherDiscount;
            $subtotalDiskon  = (int) $subtotalDiskon;
            $totalBarang     = (int) $finalTotal;
        }

        $kecamatanList = array_keys($this->dataOngkir);
        sort($kecamatanList);

        return view('pembayaran', [
            'user'            => $user,
            'transaksi'       => $transaksi,
            'barang'          => $barang,
            'selectedItems'   => $selectedItems,
            'totalBarang'     => $totalBarang,     // ini yang dipakai data-total & tampil grand total
            'subtotalAsli'    => $subtotalAsli,
            'voucherDiscount' => $voucherDiscount,
            'subtotalDiskon'  => $subtotalDiskon,
            'promoDiscount'   => $promoDiscount,
            'finalTotal'      => $finalTotal,      // total akhir barang (sebelum ongkir) = sama dengan keranjang
            'totalBerat'      => $totalBerat,
            'kecamatanList'   => $kecamatanList,
        ]);
    }

    // ================================

    public function beliSekarang(Request $request)
{
    $barang_id = $request->input('product_id');
    $user = Auth::user();

    if (!$barang_id) {
        return redirect()->back()->with('error', 'Produk tidak ditemukan.');
    }

    if (!$user) {
        return redirect()->route('login')->with('error', 'Anda harus login.');
    }

    $barang = Barang::find($barang_id);
    if (!$barang) {
        return redirect()->back()->with('error', 'Barang tidak ditemukan.');
    }
    if ($barang->stok < 1) {
    return redirect()->back()->with('error', 'Stok produk sudah habis.');
}

    // 🔥 RESET cart lama agar beli sekarang tidak tercampur
    session()->forget(['cart', 'selected_items']);

    // 🔥 SIMPAN cart sementara (1 item)
    session([
        'cart' => [
            (string)$barang->id => [
                'barang_id' => $barang->id,
                'nama'      => $barang->nama,
                'jumlah'    => 1,
            ]
        ],
        'selected_items' => [(string)$barang->id],
    ]);

    // 🔥 KIRIM produk[] ke halaman pembayaran
    return redirect()->route('pembayaran', [
        'produk' => [(string)$barang->id]
    ]);
}

    public function createSnapToken(Request $request)
    {
        try {
            Log::info('=== createSnapToken START ===');
            Log::info('Request ALL:', $request->all());

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'error' => 'User tidak terautentikasi'
                ], 401);
            }

            $user = Auth::user();
            $totalBarang = (int) $request->input('total_barang', 0);
            $ongkir      = (int) $request->input('ongkir', 0);
            $alamatPengiriman = $request->input('alamat_pengiriman');
            $kurir  = $request->input('kurir', 'KURIR TOKO');
            $service = $request->input('service', '1-2 hari');

            if ($totalBarang <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Total barang tidak valid'
                ], 422);
            }

            $grandTotal = $totalBarang + $ongkir;

            if ($grandTotal <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Total pembayaran tidak valid'
                ], 400);
            }
            $cart = session('cart', []);
            foreach ($cart as $item) {
                $barangId = $item['barang_id'] ?? null;
                $qty      = $item['jumlah'] ?? 1;

                if (!$barangId) continue;

                $barang = Barang::find($barangId);
                if (!$barang || $barang->stok < $qty) {
                    return response()->json([
                        'success' => false,
                        'error'   => "Stok {$barang->nama} tidak mencukupi."
                    ], 422);
                }
            }
            $this->initMidtrans();

            $item_details = [
                [
                    'id'       => 'PRODUK-' . time(),
                    'price'    => (int) $totalBarang,
                    'quantity' => 1,
                    'name'     => 'Produk Belanja',
                ]
            ];

            if ($ongkir > 0) {
                $item_details[] = [
                    'id'       => 'ONGKIR',
                    'price'    => (int) $ongkir,
                    'quantity' => 1,
                    'name'     => 'Ongkos Kirim',
                ];
            }

            $orderId = 'ORDER-' . time() . '-' . strtoupper(Str::random(6));

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $grandTotal,
                ],
                'item_details' => $item_details,
                'customer_details' => [
                    'first_name' => $user->name ?? 'Customer',
                    'email'      => $user->email ?? 'customer@example.com',
                    'phone'      => $user->phone ?? '08123456789',
                ],
            ];

            Log::info('Calling Midtrans API...');
            $snapToken = Snap::getSnapToken($params);
            Log::info('Token created: ' . substr($snapToken, 0, 20));

            session([
                'pending_payment' => [
                    'order_id'         => $orderId,
                    'total_barang'     => $totalBarang,
                    'ongkir'           => $ongkir,
                    'grand_total'      => $grandTotal,
                    'alamat_pengiriman'=> $alamatPengiriman,
                    'kurir'            => $kurir,
                    'service'          => $service,
                ]
            ]);

            return response()->json([
                'success'    => true,
                'token'      => $snapToken,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'grand_total'=> $grandTotal,
            ]);

        } catch (\Exception $e) {
            Log::error('createSnapToken ERROR: ' . $e->getMessage());
            Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'error'   => 'Gagal membuat token',
                'message' => $e->getMessage(),
                'line'    => $e->getLine()
            ], 500);
        }
    }

    public function getSnapToken(Request $request)
    {
        try {
            Log::info('=== getSnapToken START ===', $request->all());

            $this->initMidtrans();

            $transaksiId = $request->input('transaksi_id');
            if (!$transaksiId) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Transaksi ID tidak ditemukan'
                ], 400);
            }

            $transaksi = Transaksi::with('barang', 'user')->find($transaksiId);
            if (!$transaksi || !$transaksi->barang) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Transaksi / barang tidak ditemukan'
                ], 404);
            }

            $ongkir    = (int) $request->input('ongkir', 0);
            $kecamatan = $request->input('kecamatan', 'Unknown');
            $alamat    = $request->input('alamat', '');
            $kurir     = $request->input('kurir', 'KURIR TOKO');
            $service   = $request->input('service', 'Lokal');

            $hargaBarang = (int) $transaksi->total_harga;
            $grandTotal  = $hargaBarang + $ongkir;

            if ($grandTotal <= 0) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Total pembayaran tidak valid'
                ], 400);
            }

            $transaksi->update([
                'ongkir'            => $ongkir,
                'total_harga'       => $grandTotal,
                'alamat_pengiriman' => trim($alamat) . ', Kec. ' . $kecamatan,
                'kurir'             => $kurir,
                'service'           => $service
            ]);

            $orderId = 'TRX-' . $transaksi->id . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->nama_user ?? 'Customer',
                    'email'      => $transaksi->user->email ?? Auth::user()->email ?? 'customer@example.com',
                    'phone'      => $transaksi->user->phone ?? Auth::user()->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id'       => 'PROD-' . $transaksi->barang_id,
                        'price'    => $hargaBarang,
                        'quantity' => 1,
                        'name'     => substr($transaksi->barang->nama ?? 'Produk', 0, 50),
                    ],
                    [
                        'id'       => 'ONGKIR',
                        'price'    => $ongkir,
                        'quantity' => 1,
                        'name'     => 'Ongkir - ' . substr($kecamatan, 0, 40),
                    ],
                ],
            ];

            Log::info('Midtrans params', $params);

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success'    => true,
                'token'      => $snapToken,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('getSnapToken ERROR: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error'   => 'Gagal membuat token pembayaran',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proses hasil pembayaran: DISINI kita alokasikan promo + ongkir per baris agar riwayat sesuai.
     */
    public function proses(Request $request)
    {
        Log::info('=== proses payment START ===');
        Log::info('Request ALL:', $request->all());

        try {
            $paymentResult = json_decode($request->input('payment_result'), true);

            if (!$paymentResult) {
                Log::warning('payment_result empty — trying to continue with session/pending data');
            } else {
                Log::info('Payment result decoded', $paymentResult);
            }

            // Ambil status dari pembayaran (beberapa payload Midtrans berbeda-beda)
            $transactionStatus = $paymentResult['transaction_status'] ?? $paymentResult['status_code'] ?? null;
            $transactionId     = $paymentResult['transaction_id'] ?? $paymentResult['order_id'] ?? null;
            Log::info('Detected transaction status: ' . json_encode($transactionStatus) . ' | id: ' . json_encode($transactionId));

            $successStates = ['settlement', 'capture', 'success', '200'];
            $isSuccess = $transactionStatus ? in_array(strval($transactionStatus), $successStates, true) : false;

            $alamat_pengiriman = $request->input('alamat_pengiriman', null);
            $ongkir = (int) $request->input('ongkir', 0);
            $kurir = $request->input('kurir', null);
            $service = $request->input('service', null);
            $user_id = Auth::id();

            // Ambil cart & selected items; jika selected_items kosong -> fallback seluruh cart
            $cart = session()->get('cart', []);
            $selectedItems = session()->get('selected_items', []);
            if (!$selectedItems || empty($selectedItems)) {
                Log::warning('selected_items empty in session — will fallback to full cart');
                $selectedItems = array_keys($cart);
            }
            $selectedItems = array_map('strval', (array)$selectedItems);
            $selectedCartItems = array_intersect_key($cart, array_flip($selectedItems));

            // fallback: jika selectedCartItems masih kosong tapi cart ada, pesan seluruh cart
            if (empty($selectedCartItems) && !empty($cart)) {
                Log::warning('selectedCartItems empty after intersect — using full cart items');
                $selectedCartItems = $cart;
            }

            Log::info('Cart processing', [
                'cart_count' => count($cart),
                'selected_count' => count($selectedItems),
                'selected_cart_count' => count($selectedCartItems)
            ]);

            // ====== PRE-CALC: hitung subtotalAfterVoucher PER ITEM untuk alokasi promo ======
            $itemsForProcessing = []; // will hold per-item computed values
            $totalItemsSubtotal = 0;  // subtotal after voucher (sum)
            foreach ($selectedCartItems as $key => $item) {
                $barangId = $item['barang_id'] ?? $item['id'] ?? $item['produk_id'] ?? $key;
                $barang = Barang::with('vouchers')->find($barangId);
                $jumlahBeli = (int) ($item['jumlah'] ?? $item['qty'] ?? $item['quantity'] ?? 1);
                $namaItem = $item['nama'] ?? $item['title'] ?? ($barang->nama ?? 'Produk');

                if (!$barang) {
                    // skip (will be handled later)
                    continue;
                }

                // cari voucher aktif (sama aturan)
                $now = Carbon::now();
                $voucherAktif = $barang->vouchers
                    ? $barang->vouchers->filter(function ($v) use ($now) {
                        if (!$v->aktif) return false;
                        if (isset($v->tanggal_mulai) && $v->tanggal_mulai && $now->lt(Carbon::parse($v->tanggal_mulai))) return false;
                        if (isset($v->tanggal_berakhir) && $v->tanggal_berakhir && $now->gt(Carbon::parse($v->tanggal_berakhir))) return false;
                        if (isset($v->batas_penggunaan) && $v->batas_penggunaan && $v->jumlah_digunakan >= $v->batas_penggunaan) return false;
                        return true;
                    })->first()
                    : null;

                $hargaAsliPerUnit = $barang->harga;
                $subAsli = $hargaAsliPerUnit * $jumlahBeli;
                $diskonNominalPerLine = $voucherAktif ? round($hargaAsliPerUnit * ($voucherAktif->diskon / 100)) : 0;
                $subtotalAfterVoucher = max(0, $subAsli - $diskonNominalPerLine);

                $itemsForProcessing[$key] = [
                    'key' => $key,
                    'barang' => $barang,
                    'barang_id' => $barangId,
                    'nama' => $namaItem,
                    'jumlah' => $jumlahBeli,
                    'voucher' => $voucherAktif,
                    'harga_asli_per_unit' => $hargaAsliPerUnit,
                    'sub_asli' => $subAsli,
                    'diskon_nominal_line' => $diskonNominalPerLine,
                    'subtotal_after_voucher' => $subtotalAfterVoucher,
                ];

                $totalItemsSubtotal += $subtotalAfterVoucher;
            }

            // Hitung promo total dari session (sama seperti di index())
            // ==============================
// VALIDASI ULANG PROMO DARI DB
// ==============================
$promoData = null;

if (session()->has('promo.promo_id')) {
    $promoDb = \App\Models\Promo::find(session('promo.promo_id'));

    if ($promoDb && $promoDb->isValid()) {
        $promoData = [
            'promo_id' => $promoDb->id,
            'percent'  => $promoDb->percent,
            'amount'   => $promoDb->amount,
        ];
    } else {
        session()->forget('promo');
        Log::warning('Promo invalid at payment finalization, removed from session');
    }
}

// ==============================
// HITUNG TOTAL PROMO
// ==============================
            $promoTotal = 0;
            if ($promoData && $totalItemsSubtotal > 0) {
                $percent = (float) ($promoData['percent'] ?? 0);
                $amount  = (float) ($promoData['amount']  ?? 0);

                if ($percent > 0) {
                    $promoTotal = $totalItemsSubtotal * ($percent / 100);
                } elseif ($amount > 0) {
                    $promoTotal = min($amount, $totalItemsSubtotal);
                }

                $promoTotal = (int) round(min($promoTotal, $totalItemsSubtotal));
            }
            if ($promoData && $totalItemsSubtotal > 0) {
                $percent = (float) ($promoData['percent'] ?? 0);
                $amount  = (float) ($promoData['amount'] ?? 0);

                if ($percent > 0) {
                    $promoTotal = $totalItemsSubtotal * ($percent / 100);
                } elseif ($amount > 0) {
                    $promoTotal = min($amount, $totalItemsSubtotal);
                }

                $promoTotal = (int) round(min($promoTotal, $totalItemsSubtotal));
            }

            Log::info('Promo calculation', [
                'promo_session' => $promoData,
                'total_items_subtotal' => $totalItemsSubtotal,
                'promo_total' => $promoTotal
            ]);

            // Alokasi promo proporsional per item (hindari pembulatan issues)
            $allocatedPromos = [];
            $sumAllocatedPromo = 0;
            if ($totalItemsSubtotal > 0 && $promoTotal > 0) {
                foreach ($itemsForProcessing as $idx => $it) {
                    // proporsi = item_sub / totalItemsSubtotal
                    $share = ($it['subtotal_after_voucher'] / $totalItemsSubtotal) * $promoTotal;
                    $allocatedPromos[$idx] = (int) floor($share);
                    $sumAllocatedPromo += $allocatedPromos[$idx];
                }
                // sisa karena floor -> sebarkan +1 ke beberapa item pertama sampai habis
                $remainderPromo = $promoTotal - $sumAllocatedPromo;
                if ($remainderPromo > 0) {
                    foreach ($itemsForProcessing as $idx => $it) {
                        if ($remainderPromo <= 0) break;
                        $allocatedPromos[$idx] = ($allocatedPromos[$idx] ?? 0) + 1;
                        $remainderPromo--;
                    }
                }
            } else {
                // no promo or no subtotal -> zeros
                foreach ($itemsForProcessing as $idx => $it) {
                    $allocatedPromos[$idx] = 0;
                }
            }

            Log::info('Allocated promos per item', ['allocatedPromos' => $allocatedPromos]);

            // ====== Sekarang proses transaksi: alokasikan ongkir per item + promo yang sudah di-allocate ======
            DB::beginTransaction();

            // Alokasi ongkir PER ITEM (bagi rata)
            $selectedCartItemsValues = array_values($selectedCartItems);
            $itemsCount = count($selectedCartItemsValues);
            $ongkirTotal = max(0, $ongkir);
            $ongkirPerItem = $itemsCount > 0 ? intdiv($ongkirTotal, $itemsCount) : 0;
            $remainderOngkir = $itemsCount > 0 ? ($ongkirTotal % $itemsCount) : 0;

            Log::info('Ongkir allocation', [
                'ongkir_total' => $ongkirTotal,
                'items_count'  => $itemsCount,
                'ongkir_per_item' => $ongkirPerItem,
                'remainder'    => $remainderOngkir
            ]);

            $index = 0;

            if (!empty($selectedCartItems)) {
                foreach ($selectedCartItems as $key => $item) {
                    // dukung struktur item: boleh berisi barang_id, nama, jumlah
                    $barangId = $item['barang_id'] ?? $item['id'] ?? $item['produk_id'] ?? $key;

                    // jika barang tidak ada di itemsForProcessing (mis. barang hilang), abort
                    if (!isset($itemsForProcessing[$key])) {
                        Log::error('Barang not found during processing (pre-calc missing)', ['barangId' => $barangId, 'key' => $key]);
                        DB::rollBack();
                        return redirect()->route('dashboard')->with('error', 'Produk tidak ditemukan saat proses pembayaran.');
                    }

                    $proc = $itemsForProcessing[$key];
                    $barang = $proc['barang'];
                    $jumlahBeli = $proc['jumlah'];
                    $namaItem = $proc['nama'];
                    $subtotalAfterVoucher = $proc['subtotal_after_voucher'];

                    // Cek stok hanya jika pembayaran final (jika success)
                    if ($isSuccess && $barang->stok < $jumlahBeli) {
                        Log::error('Stock insufficient at finalization', ['barang' => $barang->id, 'requested' => $jumlahBeli, 'available' => $barang->stok]);
                        DB::rollBack();
                        return redirect()->route('dashboard')->with('error', 'Stok untuk ' . $barang->nama . ' tidak mencukupi saat finalisasi pembayaran.');
                    }

                    // Ambil allocated promo untuk item ini
                    $allocatedPromoForThis = $allocatedPromos[$key] ?? 0;

                    // ALLOKASI ONGKIR: bagi rata, tambahkan 1 untuk beberapa item pertama sesuai remainder
                    $allocatedOngkir = $ongkirPerItem + ($index < $remainderOngkir ? 1 : 0);
                    $index++;

                    // Total harga per item = subtotalAfterVoucher - allocatedPromo + allocatedOngkir
                    $totalHargaItem = $subtotalAfterVoucher - $allocatedPromoForThis + $allocatedOngkir;
                    $totalHargaItem = max(0, (int) round($totalHargaItem));

                    // buat transaksi
                    $transaksi = Transaksi::create([
                        'user_id' => $user_id,
                        'nama_user' => Auth::user()->name ?? null,
                        'nama_barang' => $namaItem,
                        'barang_id' => $barang->id,
                        'jumlah' => $jumlahBeli,
                        'total_harga' => $totalHargaItem,
                        'ongkir' => $allocatedOngkir,
                        'kurir' => $kurir,
                        'service' => $service,
                        'alamat_pengiriman' => $alamat_pengiriman,
                        'status_pembayaran' => $isSuccess ? 'Lunas' : 'Pending',
                        'kode_transaksi' => $transactionId ?? ('MANUAL-' . time()),
                    ]);

                    Log::info('Transaksi created', [
                        'id' => $transaksi->id,
                        'status' => $transaksi->status_pembayaran,
                        'ongkir_allocated' => $allocatedOngkir,
                        'promo_allocated' => $allocatedPromoForThis,
                        'subtotal_after_voucher' => $subtotalAfterVoucher,
                        'total_harga_saved' => $totalHargaItem
                    ]);

                    // kalau sukses -> kurangi stok & konsumsi voucher
                    if ($isSuccess) {
                        $barang->decrement('stok', $jumlahBeli);
                        Log::info('Stock decremented', ['barang_id' => $barang->id, 'qty' => $jumlahBeli]);

                        $voucherAktif = $proc['voucher'];
                        if ($voucherAktif) {
                            // inkremen sesuai qty (saya pilih inkremen per unit agar jumlah_digunakan konsisten)
                            $voucherAktif->increment('jumlah_digunakan', $jumlahBeli);
                            $voucherAktif->refresh();
                            Log::info('Voucher incremented', ['voucher_id' => $voucherAktif->id, 'new_used' => $voucherAktif->jumlah_digunakan]);

                            if ($voucherAktif->batas_penggunaan && $voucherAktif->jumlah_digunakan >= $voucherAktif->batas_penggunaan) {
                                $voucherAktif->aktif = false;
                                $voucherAktif->save();
                                Log::info('Voucher deactivated because batas_penggunaan reached', ['voucher_id' => $voucherAktif->id]);
                            }
                        }
                    }
                } // end foreach

                // Setelah memproses semua item: jika sukses -> bersihkan cart/session related
                if ($isSuccess) {
                    // ==============================
                    if ($isSuccess && session()->has('promo')) {

                        $promoSession = session('promo');

                        if (isset($promoSession['promo_id'])) {
                            $promo = \App\Models\Promo::find($promoSession['promo_id']);

                            if ($promo) {

                                // Naikkan jumlah pemakaian (1x per transaksi)
                                $promo->increment('jumlah_digunakan');

                                // Refresh data
                                $promo->refresh();

                                // Jika sudah mencapai batas → nonaktifkan
                                if (
                                    $promo->batas_penggunaan !== null &&
                                    $promo->jumlah_digunakan >= $promo->batas_penggunaan
                                ) {
                                    $promo->update(['aktif' => false]);
                                }

                                \Log::info('Promo consumed', [
                                    'promo_id' => $promo->id,
                                    'used' => $promo->jumlah_digunakan,
                                    'limit' => $promo->batas_penggunaan
                                ]);
                            }
                        }

                        // HAPUS PROMO DARI SESSION
                        session()->forget('promo');
                    }
                    // hapus semua selected items dari cart (jika ada)
                    foreach ($selectedItems as $itemId) {
                        if (isset($cart[$itemId])) unset($cart[$itemId]);
                    }
                    session(['cart' => $cart]);
                    session()->forget('selected_items');
                    session()->forget('promo');
                    Log::info('Cart cleared and promo removed after successful payment');
                }

                DB::commit();

                // Redirect ke riwayat jika sukses, atau beri pesan pending
               if ($isSuccess) {
                    return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil! Terima kasih telah berbelanja.');
                } else {
                    return redirect()->route('dashboard')->with('success', 'Pembayaran tercatat (pending). Mohon tunggu konfirmasi.');
                }
            }

            // kalau tidak ada item sama sekali di cart -> rollback & beri tahu
            DB::rollBack();
            Log::error('No items to process');
            return redirect()->route('dashboard')->with('error', 'Tidak ada item yang diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== proses payment ERROR ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

  
    public function storeMobileOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = $request->user();
            $now  = Carbon::now();

            $data = $request->validate([
                'items'             => 'required|array|min:1',
                'subtotal'          => 'required|numeric',
                'discount'          => 'nullable|numeric',
                'total'             => 'required|numeric',
                'alamat_pengiriman' => 'nullable|string',
                'ongkir'            => 'nullable|numeric',
                'promo'             => 'nullable|string', // KODE PROMO
                'promo_raw'         => 'nullable',        // HANYA UNTUK DISPLAY
            ]);

            $alamat = $data['alamat_pengiriman'] ?? ($user->address ?? null);
            $items  = $data['items'];

            // ==================================
            // PRE-CALC: HITUNG SUBTOTAL SETELAH VOUCHER
            // ==================================
            $itemsForProcessing = [];
            $totalAfterVoucher  = 0;

            foreach ($items as $idx => $item) {
                $barangId = $item['barang_id'] ?? $item['id'] ?? null;
                if (!$barangId) continue;

                $barang = Barang::with('vouchers')->lockForUpdate()->find($barangId);
                if (!$barang) continue;

                $qty = (int) ($item['quantity'] ?? $item['qty'] ?? 1);

                // Cari voucher aktif
                $voucherAktif = $barang->vouchers
                    ? $barang->vouchers->filter(function ($v) use ($now) {
                        return $v->aktif
                            && (!$v->tanggal_mulai || $now->gte($v->tanggal_mulai))
                            && (!$v->tanggal_berakhir || $now->lte($v->tanggal_berakhir))
                            && (!$v->batas_penggunaan || $v->jumlah_digunakan < $v->batas_penggunaan);
                    })->sortByDesc('diskon')->first()
                    : null;

                $hargaDasar = (int) ($item['harga'] ?? $item['harga_diskon'] ?? $item['price'] ?? $barang->harga);

                if ($voucherAktif) {
                    $hargaAfterVoucher = (int) round($hargaDasar * (1 - $voucherAktif->diskon / 100));
                } else {
                    $hargaAfterVoucher = $hargaDasar;
                }

                $lineSubtotal = $hargaAfterVoucher * $qty;

                $itemsForProcessing[$idx] = [
                    'barang'        => $barang,
                    'barang_id'     => $barangId,
                    'qty'           => $qty,
                    'voucher'       => $voucherAktif,
                    'unit_price'    => $hargaAfterVoucher,
                    'line_subtotal' => $lineSubtotal,
                ];

                $totalAfterVoucher += $lineSubtotal;
            }

            if ($totalAfterVoucher <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran tidak valid.'
                ], 422);
            }

            // ==================================
            // VALIDASI PROMO KE DATABASE (🔥 FIX)
            // ==================================
            $promo       = null;
            $promoTotal  = 0;
            $promoCode   = $data['promo'] ?? null;

            if ($promoCode) {
                $promo = Promo::lockForUpdate()->where('kode', $promoCode)->first();

                if ($promo && $promo->isValid()) {
                    if ($promo->percent > 0) {
                        $promoTotal = (int) round($totalAfterVoucher * ($promo->percent / 100));
                    } elseif ($promo->amount > 0) {
                        $promoTotal = (int) min($promo->amount, $totalAfterVoucher);
                    }
                } else {
                    $promo = null; // promo tidak valid
                }
            }

            // ==================================
            // ALOKASI PROMO PER ITEM (PROPORSIONAL)
            // ==================================
            $allocatedPromos = [];
            $sumAllocated    = 0;

            if ($promo && $promoTotal > 0) {
                foreach ($itemsForProcessing as $k => $it) {
                    $share = ($it['line_subtotal'] / $totalAfterVoucher) * $promoTotal;
                    $allocatedPromos[$k] = (int) floor($share);
                    $sumAllocated += $allocatedPromos[$k];
                }

                // sisa pembulatan
                $remainder = $promoTotal - $sumAllocated;
                if ($remainder > 0) {
                    foreach ($itemsForProcessing as $k => $it) {
                        if ($remainder <= 0) break;
                        $allocatedPromos[$k]++;
                        $remainder--;
                    }
                }
            } else {
                foreach ($itemsForProcessing as $k => $it) {
                    $allocatedPromos[$k] = 0;
                }
            }

            // ==================================
            // ALOKASI ONGKIR PER ITEM
            // ==================================
            $ongkirTotal    = (int) ($data['ongkir'] ?? 0);
            $countItems     = count($itemsForProcessing);
            $ongkirPerItem  = $countItems > 0 ? intdiv($ongkirTotal, $countItems) : 0;
            $sisaOngkir     = $countItems > 0 ? ($ongkirTotal % $countItems) : 0;

            // ==================================
            // SIMPAN TRANSAKSI PER ITEM
            // ==================================
            foreach ($itemsForProcessing as $index => $it) {
                $barang = $it['barang'];
                $qty    = $it['qty'];

                if ($barang->stok < $qty) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$barang->nama} tidak mencukupi"
                    ], 422);
                }

                $allocatedPromo  = $allocatedPromos[$index] ?? 0;
                $allocatedOngkir = $ongkirPerItem + ($index < $sisaOngkir ? 1 : 0);

                $totalHargaItem = max(
                    0,
                    ($it['unit_price'] * $qty) - $allocatedPromo + $allocatedOngkir
                );

                Transaksi::create([
                    'user_id'           => $user->id,
                    'nama_user'         => $user->name,
                    'nama_barang'       => $barang->nama,
                    'barang_id'         => $barang->id,
                    'jumlah'            => $qty,
                    'total_harga'       => $totalHargaItem,
                    'ongkir'            => $allocatedOngkir,
                    'alamat_pengiriman' => $alamat,
                    'status_pembayaran' => 'Lunas',
                    'promo'             => $promo ? $promo->kode : null,
                ]);

                // kurangi stok
                $barang->decrement('stok', $qty);

                // konsumsi voucher
                if ($it['voucher']) {
                    $it['voucher']->increment('jumlah_digunakan', $qty);
                }
            }

            // ==================================
            // KONSUMSI PROMO (🔥 FIX PENTING)
            // ==================================
            if ($promo) {
                $promo->increment('jumlah_digunakan');
                $promo->refresh();

                if (
                    $promo->batas_penggunaan !== null &&
                    $promo->jumlah_digunakan >= $promo->batas_penggunaan
                ) {
                    $promo->update(['aktif' => false]);
                }

                Log::info('Promo mobile consumed', [
                    'promo_id' => $promo->id,
                    'used'     => $promo->jumlah_digunakan,
                    'limit'    => $promo->batas_penggunaan,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan mobile berhasil disimpan.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('storeMobileOrder ERROR', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.'
            ], 500);
        }
    }

   public function getMobileOrders(Request $request)
    {
        $user = $request->user();

        $orders = Transaksi::with('barang')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                if (!$order->nama_barang && $order->barang) {
                    $order->nama_barang = $order->barang->nama;
                }

                $order->total_bayar = $order->total_harga;

                return $order;
            });

        return response()->json([
            'success' => true,
            'orders'  => $orders,
        ]);
    }
}
