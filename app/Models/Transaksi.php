<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // kolom untuk relasi ke user
        'user_name',
        'barang_id', // kolom untuk relasi ke barang
        'total_harga',
        'status_pembayaran',
        'created_at'
    ];

    // Relasi ke pengguna (user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relasi ke User
    }

    // Relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id'); // Relasi ke Barang
    }
}
