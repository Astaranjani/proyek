<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'barang_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * Relasi ke tabel order.
     * Setiap order item milik satu order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke tabel barang.
     * Setiap order item berisi satu barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
