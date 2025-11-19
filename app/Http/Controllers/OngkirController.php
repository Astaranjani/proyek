<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OngkirController extends Controller
{
    // Konfigurasi API
    protected function apiBase(): string
    {
        // Ganti default ke URL yang Anda berikan
        return env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
    }

    protected function apiKey(): string
    {
        // Pastikan Anda telah mengatur RAJAONGKIR_API_KEY di file .env Anda
        return env('RAJAONGKIR_API_KEY');
    }

    protected function originCityId(): string
    {
        // Menggunakan ID Kota Asal (Bandung) sesuai permintaan.
        // ID 160 adalah untuk Bandung.
        return env('RAJAONGKIR_ORIGIN_CITY_ID', '160');
    }

    // --- 1. Mengambil Provinsi ---

    public function getProvinces()
    {
        // URL endpoint: .../api/v1/province
        $resp = Http::withHeaders(['key' => $this->apiKey()])
                    ->get($this->apiBase() . '/province');

        if ($resp->successful()) {
            $data = $resp->json();
            // Kembalikan hanya array hasil provinsi
            return response()->json($data['rajaongkir']['results'] ?? []);
        }

        Log::error('RajaOngkir getProvinces error', ['resp' => $resp->body(), 'url' => $this->apiBase() . '/province']);
        return response()->json(['message' => 'Gagal mengambil data provinsi.'], 500);
    }

    // --- 2. Mengambil Kota berdasarkan Provinsi ---

    public function getCities(Request $request, $province_id)
    {
        // URL endpoint: .../api/v1/city?province=ID
        $resp = Http::withHeaders(['key' => $this->apiKey()])
                    ->get($this->apiBase() . '/city', [
                        'province' => $province_id
                    ]);

        if ($resp->successful()) {
            $data = $resp->json();
            // Kembalikan hanya array hasil kota
            return response()->json($data['rajaongkir']['results'] ?? []);
        }

        Log::error('RajaOngkir getCities error', ['resp' => $resp->body(), 'province_id' => $province_id]);
        return response()->json(['message' => 'Gagal mengambil data kota.'], 500);
    }

    // --- 3. Perhitungan Ongkos Kirim ---

    public function getCost(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            // Kita tidak meminta 'origin' dari user, tapi dari config
            'destination' => 'required|integer', // city_id tujuan
            'weight' => 'required|integer|min:1', // berat dalam gram, minimal 1 gram
            'courier' => 'required|string', // jne, pos, tiki, dll.
        ]);

        $payload = [
            // **PENTING: Selalu gunakan origin dari config (ID 160)**
            'origin' => $this->originCityId(), 
            
            // Input dari pengguna
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ];

        // API RajaOngkir untuk cost menggunakan method POST dan application/x-www-form-urlencoded
        // Laravel Http Facade secara default mengirimkan sebagai JSON,
        // namun biasanya API RajaOngkir mengizinkan format ini. 
        // Jika API Komerce rewel, kita perlu menambahkan ->asForm() atau mengubah payload ke form-urlencoded.
        
        $resp = Http::withHeaders(['key' => $this->apiKey()])
                    ->post($this->apiBase() . '/cost', $payload);

        if ($resp->successful()) {
            $data = $resp->json();
            
            // Cek apakah ada results, dan ambil costs
            if (isset($data['rajaongkir']['results'][0]['costs'])) {
                // Kembalikan hanya array costs saja (berisi detail layanan dan harga)
                return response()->json($data['rajaongkir']['results'][0]['costs']);
            }
            
            // Jika sukses tapi tidak ada results (mungkin error dari Komerce/RajaOngkir itu sendiri)
            return response()->json([], 200);

        }

        Log::error('RajaOngkir getCost error', ['resp' => $resp->body(), 'payload' => $payload]);
        return response()->json(['message' => 'Gagal menghitung ongkos kirim. Silakan coba lagi.'], 500);
    }
}