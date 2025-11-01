<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'barang_id',
        'quantity',
        'total',
    ];

    /**
     * Relasi ke tabel pengguna (user).
     * Satu cart dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel barang.
     * Setiap cart memiliki satu barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
