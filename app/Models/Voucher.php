<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'diskon',
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

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'voucher_barang');
    }

    /**
     * Sisa penggunaan (NUMERIC ONLY)
     */
    public function getSisaPenggunaanAttribute(): ?int
    {
        if ($this->batas_penggunaan === null) return null;

        return max($this->batas_penggunaan - ($this->jumlah_digunakan ?? 0), 0);
    }

    /**
     * VALIDASI TERPUSAT (WEB / MOBILE / ADMIN)
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

        // 🔥 SEMUA BARANG HABIS → VOUCHER HABIS
        if ($this->barangs->count() > 0 &&
            $this->barangs->every(fn ($b) => $b->stok <= 0)
        ) {
            return false;
        }

        return true;
    }

    /**
     * LABEL STATUS UNTUK UI (ADMIN / WEB / MOBILE)
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

        if (
            $this->barangs->count() > 0 &&
            $this->barangs->every(fn ($b) => $b->stok <= 0)
        ) {
            return ['Habis Terpakai', 'bg-red-100 text-red-700'];
        }

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) {
            return ['Belum Mulai', 'bg-yellow-100 text-yellow-700'];
        }

        return ['Aktif', 'bg-green-100 text-green-700'];
    }
}
