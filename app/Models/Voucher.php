<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'voucher_barang', 'voucher_id', 'barang_id');
    }

    // Hitung sisa pemakaian otomatis
    public function getSisaPenggunaanAttribute()
    {
        if (!$this->batas_penggunaan) return '∞';
        return max($this->batas_penggunaan - ($this->jumlah_digunakan ?? 0), 0);
    }

    // Cek apakah voucher masih bisa digunakan
    public function getStatusAttribute()
    {
        if ($this->batas_penggunaan && $this->jumlah_digunakan >= $this->batas_penggunaan) {
            return 'Habis';
        }

        $today = now()->toDateString();
        if ($this->tanggal_berakhir && $today > $this->tanggal_berakhir) {
            return 'Kadaluarsa';
        }

        return 'Aktif';
    }
}
