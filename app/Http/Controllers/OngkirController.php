<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <--- INI WAJIB ADA
use Illuminate\Support\Facades\Log;

class OngkirController extends Controller
{
    // --- KONFIGURASI ---
    protected function apiBase(): string {
        return env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');
    }

    protected function apiKey(): string {
        return (string) env('RAJAONGKIR_API_KEY', '');
    }

    // Helper agar tidak kena Error SSL
    private function httpClient() {
        return Http::withoutVerifying()
            ->timeout(15)
            ->withHeaders(['key' => $this->apiKey()]);
    }

    // --- 1. AMBIL PROVINSI ---
    public function getProvinces()
    {
        try {
            $response = $this->httpClient()->get($this->apiBase() . '/province');
            
            if ($response->successful()) {
                return response()->json($response->json()['rajaongkir']['results']);
            }
            
            // Jika Key Salah / Expired
            return response()->json([
                'message' => 'Gagal dari RajaOngkir. Cek API Key Anda.',
                'error_rajaongkir' => $response->json()
            ], 500);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error Server', 'error' => $e->getMessage()], 500);
        }
    }

    // --- 2. AMBIL KOTA ---
    public function getCities($province_id)
    {
        try {
            $response = $this->httpClient()->get($this->apiBase() . '/city', ['province' => $province_id]);
            
            if ($response->successful()) {
                return response()->json($response->json()['rajaongkir']['results']);
            }
            return response()->json(['message' => 'Gagal ambil kota'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error Server'], 500);
        }
    }

    // --- 3. HITUNG ONGKIR ---
    public function getCost(Request $request)
    {
        try {
            $payload = [
                'origin' => env('RAJAONGKIR_ORIGIN_CITY_ID', '160'), // Default Bandung
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier,
            ];

            $response = $this->httpClient()->asForm()->post($this->apiBase() . '/cost', $payload);

            if ($response->successful()) {
                $hasil = $response->json();
                if (isset($hasil['rajaongkir']['results'][0]['costs'])) {
                    return response()->json($hasil['rajaongkir']['results'][0]['costs']);
                }
            }
            return response()->json(['message' => 'Gagal hitung ongkir'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error Server'], 500);
        }
    }
}