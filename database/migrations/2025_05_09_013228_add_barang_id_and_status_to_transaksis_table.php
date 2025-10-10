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
        if (!Schema::hasColumn('transaksis', 'barang_id')) {
            $table->unsignedBigInteger('barang_id');
        }
        if (!Schema::hasColumn('transaksis', 'status')) {
            $table->string('status')->default('pending');
        }
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
