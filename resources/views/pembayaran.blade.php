<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Halaman Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        body { background-color: #f1f3f5; }
        .card-custom { 
            background: #fff; border-radius: 15px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
            padding: 30px; margin-top: 40px; margin-bottom: 40px; 
        }
        .btn-primary { 
            background-color: #0bc455; border: none; 
            font-weight: 600; padding: 12px 24px; border-radius: 10px; 
            transition: background-color 0.3s ease; 
        }
        .btn-primary:hover { background-color: #0aa64b; }
        .ongkir-option { 
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .ongkir-option:hover { 
            background-color: #d1f2dd !important; 
            transform: translateY(-2px);
        }
        .ongkir-option.selected {
            background-color: #c3f0d4 !important;
            border: 2px solid #0bc455 !important;
        }
        .debug-panel {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            max-height: 300px;
            overflow-y: auto;
            background: #2d3748;
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            font-size: 11px;
            font-family: monospace;
            z-index: 9999;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .debug-panel .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
        }
        .debug-panel .log-entry {
            padding: 5px 0;
            border-bottom: 1px solid #4a5568;
        }
        .debug-panel .log-error { color: #fc8181; }
        .debug-panel .log-success { color: #68d391; }
        .debug-panel .log-info { color: #63b3ed; }

        .detail-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px 20px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card card-custom">

                <h2 class="mb-4 text-center">
                    {{ $transaksi ? 'Pembayaran Produk' : 'Pembayaran' }}
                </h2>

                <form id="payment-form" method="POST" action="{{ route('pembayaran.proses') }}">
                    @csrf
                    <input type="hidden" name="payment_result" id="payment-result">

                    {{-- Mode checkout --}}
                    @if($transaksi)
                        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                        <input type="hidden" id="checkout-mode" value="beli_sekarang">
                    @else
                        <input type="hidden" id="checkout-mode" value="cart">
                        {{-- total_barang = total akhir barang (setelah voucher + promo, sebelum ongkir) --}}
                        <input type="hidden" name="total_barang" id="total_barang" value="{{ $finalTotal ?? 0 }}">
                    @endif

                    {{-- Hidden Ongkir --}}
                    <input type="hidden" name="ongkir" id="ongkir_cost" value="0">
                    <input type="hidden" name="kurir" id="kurir_input" value="">
                    <input type="hidden" name="service" id="service_input" value="">
                    <input type="hidden" name="kecamatan" id="kecamatan_input" value="">

                    {{-- Data penerima --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Data Penerima</label>
                        <input type="text" class="form-control mb-2" value="{{ auth()->user()->name }}" readonly />
                        <input type="text" class="form-control mb-2" 
                               value="{{ auth()->user()->phone ?? 'No. HP Belum Diisi' }}" readonly />
                        <textarea id="user-address" name="alamat_pengiriman" class="form-control mb-2"
                                  rows="3" placeholder="Detail Alamat (Jalan, RT/RW, Patokan)" required>{{ auth()->user()->address ?? '' }}</textarea>
                    </div>

                    @php
                        if($transaksi){
                            $totalBarang = (int) $transaksi->total_harga;
                        } else {
                            // total akhir barang (setelah voucher + promo)
                            $totalBarang = (int) $finalTotal;
                        }
                    @endphp

                    {{-- Ringkasan Produk --}}
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th><th>Harga</th><th>Jml</th><th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Jika beli sekarang --}}
                                @if($transaksi && $barang)
                                    <tr>
                                        <td>{{ $barang->nama }}</td>
                                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                        <td>1</td>
                                        <td>Rp {{ number_format($totalBarang, 0, ',', '.') }}</td>
                                    </tr>
                                {{-- Jika dari keranjang --}}
                                @else
                                    @foreach($selectedItems ?? [] as $item)
                                        <tr>
                                            <td>{{ $item['nama'] }}</td>
                                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td>{{ $item['jumlah'] }}</td>
                                            <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- DETAIL PERHITUNGAN (seperti di keranjang) --}}
                    <div class="detail-card mb-4">
                        <h6 class="fw-bold mb-3">Rincian Perhitungan Barang</h6>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Belanja (Sebelum Diskon)</span>
                            <span class="fw-bold">
                                Rp {{ number_format($subtotalAsli ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Diskon Voucher</span>
                            <span class="fw-bold text-success">
                                - Rp {{ number_format($voucherDiscount ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal Setelah Voucher</span>
                            <span class="fw-bold">
                                Rp {{ number_format($subtotalDiskon ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        @if(($promoDiscount ?? 0) > 0)
                            <div class="d-flex justify-content-between mb-1 text-success">
                                <span>Diskon Promo</span>
                                <span class="fw-bold">
                                    - Rp {{ number_format($promoDiscount ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total Akhir Barang</span>
                            <span class="fw-bold text-success">
                                Rp {{ number_format($finalTotal ?? $totalBarang, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Ongkir + Grand Total --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr class="fw-bold text-end text-danger">
                                    <td class="text-start">Ongkos Kirim</td>
                                    <td id="display-ongkir">Rp 0</td>
                                </tr>

                                <tr class="fw-bold text-end table-success">
                                    <td class="text-start">Grand Total</td>
                                    <td id="display-grand-total" data-total="{{ $totalBarang }}">
                                        Rp {{ number_format($totalBarang, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Pilih Kecamatan --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill"></i> Pilih Lokasi Pengiriman</h5>

                        <label class="form-label fw-bold">Kecamatan Tujuan</label>
                        <select id="district" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatanList as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>

                        <div class="mt-3" id="ongkir-results"></div>
                    </div>

                    {{-- Tombol bayar --}}
                    <button type="button" id="pay-button" class="btn btn-secondary w-100 py-3" disabled>
                        <span id="btn-text">Pilih Kecamatan & Ongkir Terlebih Dahulu</span>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Debug Logger
    function debugLog(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const logClass = type === 'error' ? 'log-error' : (type === 'success' ? 'log-success' : 'log-info');
        const icon = type === 'error' ? '❌' : (type === 'success' ? '✅' : 'ℹ️');
        
        $('#debug-logs').prepend(
            `<div class="log-entry ${logClass}">[${timestamp}] ${icon} ${message}</div>`
        );
        
        console.log(`[${timestamp}] ${message}`);
    }

    const dataOngkir = {
        'Indramayu': 10000,
        'Balongan': 12000,
        'Sindang': 10000,
        'Jatibarang': 15000,
        'Lohbener': 15000,
        'Juntinyuat': 18000,
        'Karangampel': 20000,
        'Kertasemaya': 22000,
        'Kandanghaur': 25000,
        'Losarang': 25000,
        'Haurgeulis': 30000,
        'Gantar': 35000,
        'Patrol': 30000,
        'Sukra': 35000,
        'Anjatan': 32000,
        'Bongas': 32000,
        'Gabuswetan': 30000,
        'Cikedung': 28000,
        'Lelea': 20000,
        'Widasari': 18000,
        'Tukdana': 22000,
        'Bangodua': 22000,
        'Krangkeng': 25000,
        'Cantigi': 15000,
        'Arahan': 18000,
        'Pasekan': 15000,
        'Sliyeg': 18000,
        'Kedokan Bunder': 20000,
        'Kroya': 30000,
        'Terisi': 28000
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function formatRupiah(angka){ 
        return 'Rp ' + Number(angka).toLocaleString('id-ID'); 
    }

    function updateGrandTotal(ongkir, kurir, service){
        const base = parseInt($('#display-grand-total').data('total'));
        const grand = base + parseInt(ongkir);

        $('#ongkir_cost').val(ongkir);
        $('#kurir_input').val(kurir);
        $('#service_input').val(service);

        $('#display-ongkir').text(formatRupiah(ongkir));
        $('#display-grand-total').text(formatRupiah(grand));

        $('#pay-button').prop('disabled', false)
            .removeClass('btn-secondary').addClass('btn-primary');
        $('#btn-text').text('Bayar Sekarang');
        
        debugLog(`Grand Total updated: ${formatRupiah(grand)}`, 'success');
    }

    function loadOngkirFromLocal(district) {
        debugLog(`Loading ongkir for: ${district}`);
        
        if (!dataOngkir[district]) {
            $('#ongkir-results').html(
                '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Ongkir tidak ditemukan untuk kecamatan ini.</div>'
            );
            debugLog(`Ongkir not found for: ${district}`, 'error');
            return;
        }

        const hargaOngkir = dataOngkir[district];
        
        let html = '<div class="mb-2"><strong>Pilih Opsi Pengiriman:</strong></div>';
        html += `
            <div class="alert alert-success ongkir-option mb-2 p-3" 
                 data-cost="${hargaOngkir}" 
                 data-kurir="KURIR TOKO" 
                 data-service="1-2 hari"
                 onclick="selectOngkir(this)">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <input type="radio" 
                               name="ongkir-radio" 
                               id="ongkir-local"
                               value="${hargaOngkir}"
                               class="me-3">
                        <div>
                            <strong>KURIR TOKO</strong><br>
                            <small class="text-muted">Estimasi: 1-2 hari</small><br>
                            <small class="text-muted">Pengiriman ke ${district}</small>
                        </div>
                    </div>
                    <span class="fw-bold text-success">${formatRupiah(hargaOngkir)}</span>
                </div>
            </div>
        `;

        $('#ongkir-results').html(html);

        const firstOption = $('.ongkir-option').first();
        if(firstOption.length){
            firstOption.find('input[type="radio"]').prop('checked', true);
            firstOption.addClass('selected');
            updateGrandTotal(
                firstOption.data('cost'),
                firstOption.data('kurir'),
                firstOption.data('service')
            );
        }
    }

    $('#district').on('change', function(){
        const district = $(this).val();
        $('#kecamatan_input').val(district);
        
        if(!district){ 
            $('#ongkir-results').html('');
            $('#pay-button').prop('disabled', true)
                .removeClass('btn-primary').addClass('btn-secondary');
            $('#btn-text').text('Pilih Kecamatan & Ongkir Terlebih Dahulu');
            return; 
        }

        $('#ongkir-results').html(
            '<div class="alert alert-info"><span class="spinner-border spinner-border-sm me-2"></span>Memuat ongkir...</div>'
        );

        setTimeout(function() {
            loadOngkirFromLocal(district);
        }, 300);
    });

    window.selectOngkir = function(element){
        $('.ongkir-option').removeClass('selected');
        $(element).addClass('selected');
        $(element).find('input[type="radio"]').prop('checked', true);
        
        updateGrandTotal(
            $(element).data('cost'),
            $(element).data('kurir'),
            $(element).data('service')
        );
    };

    // BAYAR
    $('#pay-button').on('click', function(){
        const mode   = $('#checkout-mode').val();
        const alamat = $('#user-address').val().trim();
        const kec    = $('#district').val();

        debugLog(`Payment button clicked. Mode: ${mode}`);

        if(!alamat || !kec){ 
            alert("Mohon lengkapi alamat dan pilih kecamatan.");
            debugLog('Validation failed: alamat or kecamatan empty', 'error');
            return; 
        }

        const ongkir = $('#ongkir_cost').val();
        const kurir  = $('#kurir_input').val();
        const serv   = $('#service_input').val();

        if(!ongkir || ongkir == '0' || !kurir){
            alert("Mohon pilih metode pengiriman.");
            debugLog('Validation failed: ongkir not selected', 'error');
            return;
        }

        let url, data;

        if (mode === 'beli_sekarang') {
            url = "{{ route('pembayaran.getToken', [], false) }}";
            data = {
                transaksi_id : $('input[name="transaksi_id"]').val(),
                alamat       : alamat,
                ongkir       : parseInt(ongkir),
                kecamatan    : kec,
                kurir        : kurir,
                service      : serv
            };
        } else {
            url = "{{ route('pembayaran.snapToken', [], false) }}";
            data = {
                alamat_pengiriman : alamat + ', Kec. ' + kec,
                ongkir            : parseInt(ongkir),
                kurir             : kurir,
                service           : serv,
                total_barang      : $('#total_barang').val()
            };
        }

        debugLog(`Request URL: ${url}`);
        debugLog(`Request Data: ${JSON.stringify(data)}`);

        $('#pay-button').prop('disabled', true);
        $('#btn-text').html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            timeout: 30000,
            beforeSend: function(xhr) {
                debugLog('Sending AJAX request...');
            },
            success: function(res){
                debugLog(`Response received: ${JSON.stringify(res)}`, 'success');
                
                const token = res.token || res.snap_token || res.snapToken || (res.data && res.data.token);
                
                if(!token){
                    debugLog('ERROR: Token not found in response', 'error');
                    alert("Token pembayaran tidak ditemukan.\n\nResponse: " + JSON.stringify(res));
                    $('#pay-button').prop('disabled', false);
                    $('#btn-text').text('Bayar Sekarang');
                    return;
                }

                debugLog(`Snap token received: ${token}`, 'success');
                debugLog('Opening Midtrans popup...');

                snap.pay(token, {
                    onSuccess: function(result){
                        debugLog('Payment SUCCESS', 'success');
                        debugLog(`Result: ${JSON.stringify(result)}`);
                        $('#payment-result').val(JSON.stringify(result));
                        $('#payment-form').submit();
                    },
                    onPending: function(result){
                        debugLog('Payment PENDING', 'info');
                        debugLog(`Result: ${JSON.stringify(result)}`);
                        $('#payment-result').val(JSON.stringify(result));
                        $('#payment-form').submit();
                    },
                    onError: function(result){
                        debugLog('Payment ERROR from Midtrans', 'error');
                        debugLog(`Error: ${JSON.stringify(result)}`, 'error');
                        alert("Pembayaran gagal: " + (result.status_message || 'Terjadi kesalahan'));
                        $('#pay-button').prop('disabled', false);
                        $('#btn-text').text('Bayar Sekarang');
                    },
                    onClose: function(){
                        debugLog('Payment popup CLOSED by user');
                        $('#pay-button').prop('disabled', false);
                        $('#btn-text').text('Bayar Sekarang');
                    }
                });
            },
            error: function(xhr, status, error){
                debugLog(`AJAX ERROR - Status: ${status}`, 'error');
                debugLog(`Error message: ${error}`, 'error');
                debugLog(`HTTP Status: ${xhr.status}`, 'error');
                debugLog(`Response Text: ${xhr.responseText}`, 'error');

                let errorMsg = 'Gagal memproses pembayaran.\n\n';
                
                if(xhr.status === 419){
                    errorMsg += 'Session expired (419). Refresh halaman dan coba lagi.';
                } else if(xhr.status === 404){
                    errorMsg += `Route tidak ditemukan (404).\nURL: ${url}`;
                } else if(xhr.status === 500){
                    errorMsg += 'Server error (500). Cek Laravel log.';
                    if(xhr.responseText) {
                        errorMsg += '\n\nResponse: ' + xhr.responseText.substring(0, 200);
                    }
                } else if(status === 'timeout'){
                    errorMsg += 'Request timeout (30s).';
                } else if(status === 'parsererror'){
                    errorMsg += 'Parse error - Response bukan JSON valid.';
                } else {
                    errorMsg += `Error: ${error}\nStatus: ${xhr.status}`;
                }
                
                alert(errorMsg);
                $('#pay-button').prop('disabled', false);
                $('#btn-text').text('Bayar Sekarang');
            }
        });
    });

    $(document).ready(function() {
        debugLog('✅ Payment page loaded successfully');
        debugLog(`Mode: ${$('#checkout-mode').val()}`);
        debugLog(`Total Barang: Rp ${$('#display-grand-total').data('total').toLocaleString()}`);
    });
</script>
</body>
</html>
