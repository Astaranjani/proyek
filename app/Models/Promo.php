<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    protected $fillable = [
        'kode',
        'percent',
        'tanggal_mulai',
        'tanggal_berakhir',
        'batas_penggunaan',
        'jumlah_digunakan',
        'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
    ];

    /**
     * 🔥 VALIDASI PROMO TERPUSAT
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        if (!$this->aktif) return false;

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) return false;

        if ($this->tanggal_berakhir && $now->gt($this->tanggal_berakhir)) return false;

        if (
            $this->batas_penggunaan !== null &&
            $this->jumlah_digunakan >= $this->batas_penggunaan
        ) {
            return false;
        }

        return true;
    }
public function scopeAktif($query)
{
    $now = now();

    return $query
        ->where('aktif', true)
        ->where(function ($q) use ($now) {
            $q->whereNull('tanggal_mulai')
              ->orWhere('tanggal_mulai', '<=', $now);
        })
        ->where(function ($q) use ($now) {
            $q->whereNull('tanggal_berakhir')
              ->orWhere('tanggal_berakhir', '>=', $now);
        })
        ->where(function ($q) {
            $q->whereNull('batas_penggunaan')
              ->orWhereColumn('jumlah_digunakan', '<', 'batas_penggunaan');
        });
}
    /**
     * 🔥 STATUS PROMO KONSISTEN (UNTUK ADMIN, MOBILE, WEB)
     */
    public function statusLabel(): array
    {
        $now = Carbon::now();

        if (!$this->aktif) {
            return ['Nonaktif', 'bg-gray-100 text-gray-700'];
        }

        if ($this->tanggal_berakhir && $now->gt($this->tanggal_berakhir)) {
            return ['Kadaluarsa', 'bg-red-100 text-red-700'];
        }

        if (
            $this->batas_penggunaan !== null &&
            $this->jumlah_digunakan >= $this->batas_penggunaan
        ) {
            return ['Habis Terpakai', 'bg-red-100 text-red-700'];
        }

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) {
            return ['Belum Mulai', 'bg-yellow-100 text-yellow-700'];
        }

        return ['Aktif', 'bg-green-100 text-green-700'];
    }
    
}
