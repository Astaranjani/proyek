<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_user',
        'barang_id',
        'nama_barang',
        'total_harga',
        'ongkir',
        'kurir',
        'service',
        'alamat_pengiriman',
        'status_pembayaran',
        'kode_transaksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
