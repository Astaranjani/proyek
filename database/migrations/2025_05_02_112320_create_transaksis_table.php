<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('nama_user')->nullable();
            $table->string('nama_barang')->nullable(false); 
            $table->string('status_pembayaran')->nullable();
            $table->string('kode_transaksi')->nullable();
            $table->string('status')->default('pending'); // diperlukan untuk metode riwayat Anda di mana 'status' di-query
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
