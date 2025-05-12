<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom barang_id dan status_pembayaran
            $table->foreignId('barang_id')->constrained()->onDelete('cascade')->after('user_id');
            $table->string('status_pembayaran')->default('Belum Lunas')->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus kolom barang_id dan status_pembayaran
            $table->dropForeign(['barang_id']);
            $table->dropColumn('barang_id');
            $table->dropColumn('status_pembayaran');
        });
    }
};
