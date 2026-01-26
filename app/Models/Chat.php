<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    // --- INI YANG KETINGGALAN ---
    // Kita harus mengizinkan kolom ini untuk diisi data
    protected $fillable = [
        'user_id',
        'message',
        'reply'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}