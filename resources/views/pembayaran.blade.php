<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Halaman Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    {{-- Tambahkan CSRF Token untuk POST AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- PASTIKAN config('midtrans.client_key') MENGAMBIL DARI config/midtrans.php --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        body { background-color: #f1f3f5; }
        .card-custom { background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 30px; margin-top: 40px; margin-bottom: 40px; }
        .btn-primary { background-color: #0bc455; border: none; font-weight: 600; padding: 12px 24px; border-radius: 10px; transition: background-color 0.3s ease; }
        .btn-primary:hover { background-color: #0aa64b; }
        .text-muted.strikethrough { text-decoration: line-through; }
        /* Style untuk radio button hasil ongkir */
        .list-group-item label { width: 100%; cursor: pointer; }
        .list-group-item input[type="radio"] { margin-right: 10px; }
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
                    <input type="hidden" name="ongkir" id="ongkir_cost" value="0">
                    <input type="hidden" name="kurir" id="kurir_input">
                    <input type="hidden" name="service" id="service_input">
                    {{-- Nilai alamat pengiriman akan di-set sebelum submit, tidak perlu hidden input --}}
                    
                    {{-- ID Transaksi di-embed agar bisa di-proses di proses pembayaran --}}
                    <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}"> 

                    <div class="mb-3">
                        <input type="text" class="form-control mb-2" value="{{ auth()->user()->name }}" readonly />
                        <input type="email" class="form-control mb-2" value="{{ auth()->user()->email }}" readonly />
                        <input type="text" id="user-phone" class="form-control mb-2" value="{{ auth()->user()->phone ?? 'Belum ada No. HP' }}" readonly />
                        <textarea id="user-address" name="alamat_pengiriman" class="form-control mb-2" placeholder="Masukkan alamat lengkap pengiriman (contoh: Jalan ...)" >{{ auth()->user()->address ?? '' }}</textarea>
                    </div>

                    {{-- Produk / Ringkasan --}}
                    @php
                        // Total Barang diambil dari transaksi yang sudah dibuat (sudah termasuk diskon)
                        $total = (int) $transaksi->total_harga; 
                    @endphp

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr><th>Produk</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
                            </thead>
                            <tbody>
                                {{-- Menampilkan detail 1 item yang dibeli (Beli Sekarang) --}}
                                <tr>
                                    <td>{{ $barang->nama }}</td>
                                    {{-- Tampilkan harga awal untuk referensi --}}
                                    <td>
                                        @if ($barang->harga != $transaksi->total_harga)
                                            <span class="text-muted strikethrough me-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                        @endif
                                        <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td>1</td> {{-- Jumlah: 1 unit --}}
                                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="3" class="text-end">Total Barang</td>
                                    {{-- Simpan total barang di data attribute untuk memudahkan JS --}}
                                    <td id="total-barang" data-total="{{ $total }}">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="3" class="text-end">Ongkir</td>
                                    <td id="total-ongkir">Rp 0</td>
                                </tr>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="3" class="text-end">Grand Total</td>
                                    <td id="grand-total">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Pilihan alamat & ongkir --}}
                    <h5 class="fw-bold mb-2">Alamat & Ongkir</h5>
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
                            <label>Provinsi</label>
                            <select id="province" class="form-control">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label>Kota / Kabupaten</label>
                            <select id="city" class="form-control" disabled>
                                <option value="">Pilih Kota / Kabupaten</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label>Kurir</label>
                            <select id="courier" class="form-control">
                                <option value="jne">JNE</option>
                                <option value="tiki">TIKI</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 d-flex align-items-end">
                            <button type="button" id="cek-ongkir" class="btn btn-outline-primary w-100">Cek Ongkir</button>
                        </div>

                        <div class="col-12 mt-3" id="ongkir-results"></div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
                            <button type="button" class="btn btn-outline-dark w-100 py-2" id="cod-button">COD</button>
                        </div>
                        <div class="col-12 col-md-6">
                            <button type="button" class="btn btn-primary w-100 py-2" id="pay-button">Bayar Sekarang</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Ambil CSRF Token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Pastikan ORIGIN_CITY_ID sudah ada di .env
const ORIGIN_CITY = '{{ env("RAJAONGKIR_ORIGIN_CITY_ID") }}'; 
const totalBarang = parseInt($('#total-barang').data('total'), 10); // Ambil total barang sebagai integer

$(document).ready(function() {
    
    // --- PERBAIKAN URL API ---
    const API_BASE = '/api/ongkir';
    const provincesUrl = API_BASE + '/provinces';
    const citiesUrl = API_BASE + '/cities/';
    const ongkirCostUrl = API_BASE + '/cost';
    
    // --- Helper function untuk format rupiah ---
    function formatRupiah(angka) {
        if (typeof angka === 'undefined' || isNaN(angka)) return 'Rp 0';
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    // --- Inisialisasi Alamat ---
    // Load provinsi
    $.get(provincesUrl)
        .done(function(data) {
            $('#province').html('<option value="">Pilih Provinsi</option>');
            // Data sudah bersih dari wrapper 'rajaongkir' atau 'data' karena sudah difilter di Controller
            data.forEach(function(p) {
                $('#province').append('<option value="'+p.province_id+'">'+p.province+'</option>');
            });
        })
        .fail(function(err) {
            $('#province').html('<option value="">Gagal memuat provinsi</option>');
            console.error("Gagal memuat Provinsi:", err);
        });

    // Load kota saat province berubah
    $('#province').on('change', function() {
        const provinceId = $(this).val();
        $('#city').html('<option value="">Loading...</option>').prop('disabled', true);
        $('#ongkir-results').html(''); // Hapus hasil ongkir lama
        updateGrandTotal(0); // Reset total ongkir
        
        if (!provinceId) {
             $('#city').html('<option value="">Pilih Kota / Kabupaten</option>').prop('disabled', true);
             return;
        }

        $.get(citiesUrl + provinceId)
            .done(function(data) {
                $('#city').html('<option value="">Pilih Kota / Kabupaten</option>');
                data.forEach(function(c) {
                    $('#city').append('<option value="'+c.city_id+'">'+c.type + ' ' + c.city_name+'</option>');
                });
                $('#city').prop('disabled', false);
            })
            .fail(function(err) {
                $('#city').html('<option value="">Gagal memuat kota</option>').prop('disabled', false);
                console.error("Gagal memuat Kota:", err);
            });
    });

    // --- Logika Update Total ---
    function updateGrandTotal(ongkirValue) {
        const ongkir = parseInt(ongkirValue || 0, 10);
        const grand = totalBarang + ongkir;

        $('#ongkir_cost').val(ongkir);
        $('#total-ongkir').text(formatRupiah(ongkir));
        $('#grand-total').text(formatRupiah(grand));
    }

    // --- Logika Ongkir ---
    $('#cek-ongkir').on('click', function() {
        const destination = $('#city').val();
        const courier = $('#courier').val();
        
        if (!destination) { alert('Pilih kota tujuan dulu'); return; }

        // Ganti 1000 dengan berat barang (misalnya dari $barang->weight atau input lain)
        const weight = {{ $barang->weight ?? 1000 }}; // Ambil berat dari $barang->weight (dalam gram)
        
        // Nonaktifkan tombol saat loading
        $('#cek-ongkir').prop('disabled', true).text('Cek Ongkir...');
        $('#ongkir-results').html('<p class="text-center">Sedang mencari layanan...</p>');
        
        $.ajax({
            url: ongkirCostUrl,
            method: 'POST',
            data: {
                // Controller sudah menggunakan ORIGIN_CITY_ID dari .env, tapi kita kirim untuk jaga-jaga
                origin: ORIGIN_CITY, 
                destination: destination,
                weight: weight,
                courier: courier,
            },
            success: function(response) {
                $('#cek-ongkir').prop('disabled', false).text('Cek Ongkir');
                
                // Response adalah array of costs
                const costs = response || []; 
                let html = '';
                
                if (costs.length === 0) {
                    html = '<div class="alert alert-warning">Tidak ada layanan ongkir ditemukan.</div>';
                } else {
                    html = '<div class="list-group">';
                    costs.forEach(function(svc) {
                        // Di RajaOngkir Starter, cost[0] selalu ada
                        const costItem = svc.cost[0];
                        const value = costItem.value;
                        const etd = costItem.etd || '-';
                        
                        // Gunakan data-cost, data-service, dan data-kurir untuk update total
                        html += `
                            <label class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <input type="radio" name="ongkir_radio" 
                                        value="${value}" 
                                        data-service="${svc.service}" 
                                        data-kurir="${courier}"
                                        data-cost="${value}">
                                    <strong>${svc.service}</strong> 
                                    <small class="text-muted">(Est: ${etd} hari)</small>
                                </div>
                                <div>${formatRupiah(value)}</div>
                            </label>
                        `;
                    });
                    html += '</div>';
                }
                $('#ongkir-results').html(html);
                updateGrandTotal(0); // Reset total setelah hasil baru dimuat
            },
            error: function(err) {
                $('#cek-ongkir').prop('disabled', false).text('Cek Ongkir');
                console.error(err.responseJSON || err.responseText);
                $('#ongkir-results').html('<div class="alert alert-danger">Gagal mengambil data ongkir. Cek console untuk detail.</div>');
                updateGrandTotal(0); // Reset total jika gagal
            }
        });
    });

    // Pilih layanan ongkir -> update total
    $(document).on('change', 'input[name="ongkir_radio"]', function() {
        const ongkir = parseInt($(this).data('cost') || 0, 10);
        const kurir = $(this).data('kurir');
        const service = $(this).data('service');
        
        // Update hidden fields
        $('#kurir_input').val(kurir);
        $('#service_input').val(service);
        
        // Update total di tabel
        updateGrandTotal(ongkir);
    });
    
    // --- Logika Pembayaran ---
    
    // Pastikan tombol pembayaran hanya bisa ditekan jika ongkir sudah dipilih
    $('#pay-button').on('click', function(e) {
        e.preventDefault();

        const ongkir = parseInt($('#ongkir_cost').val() || 0, 10);
        const kurir = $('#kurir_input').val();
        const service = $('#service_input').val();
        const alamat = $('#user-address').val();
        
        // Validasi
        if (!alamat) {
            alert('Isi alamat pengiriman terlebih dahulu.');
            return;
        }
        if (ongkir === 0 || !kurir || !service) {
            alert('Pilih layanan ongkir terlebih dahulu.');
            return;
        }
        
        // Lanjutkan ke Midtrans Snap
        const snapToken = '{{ $snapToken ?? null }}'; // Ambil snap token
        
        if (snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    $('#payment-result').val(JSON.stringify(result));
                    $('#payment-form').submit(); 
                },
                onPending: function(result) {
                    $('#payment-result').val(JSON.stringify(result));
                    $('#payment-form').submit();
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Coba lagi.');
                    console.error(result);
                }
            });
        } else {
             alert('Error: Snap Token Midtrans tidak tersedia. Pastikan Controller sudah mengirimkan $snapToken.');
        }
    });
});


</script>
</body>
</html>