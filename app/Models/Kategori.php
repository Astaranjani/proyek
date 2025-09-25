<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Kategori extends Model
{
    // Hapus baris ini untuk memutus koneksi dengan tabel 'kategoris'
    // protected $table = 'kategoris';
    // protected $fillable = ['nama_kategori'];

    /**
     * Mengambil daftar kategori statis.
     * @return Collection
     */
    public static function all($columns = ['*'])
    {
        // Mendefinisikan data kategori secara langsung dalam array
        $kategoris = [
            ['id' => 'Kursi', 'nama_kategori' => 'Kursi'],
            ['id' => 'Meja', 'nama_kategori' => 'Meja'],
            ['id' => 'Lemari', 'nama_kategori' => 'Lemari'],
            ['id' => 'Sofa', 'nama_kategori' => 'Sofa'],
            ['id' => 'Kasur', 'nama_kategori' => 'Kasur'],
        ];

        // Mengembalikan data sebagai Collection untuk konsistensi dengan model Eloquent
        return new Collection($kategoris);
    }
    
    // Anda bisa menambahkan method 'find' untuk kompatibilitas jika diperlukan
    // public static function find($id, $columns = ['*'])
    // {
    //     return static::all()->firstWhere('id', $id);
    // }
}