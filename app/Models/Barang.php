<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // Nama tabel (opsional jika mengikuti konvensi Laravel)
    protected $table = 'barangs';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'order_id',
        'nama',
        'deskripsi',
        'stok',
        'harga',
        'gambar',
        'kategori',
    ];

    
    public function vouchers()
{
    return $this->belongsToMany(Voucher::class, 'voucher_barang', 'barang_id', 'voucher_id')
                ->where('aktif', true);
}

}
