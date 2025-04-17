<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // Nama tabel (opsional jika mengikuti konvensi Laravel)
    protected $table = 'barangs';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'gambar',
    ];
}
