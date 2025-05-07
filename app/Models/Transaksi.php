<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'total_pembayaran',
        'status',
        'created_at',
        'updated_at'
    ];

    // Relasi ke pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(User::class);
    }
}
