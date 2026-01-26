<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'tanggal_transaksi')) {
                $table->timestamp('tanggal_transaksi')->nullable()->after('status_pembayaran');
            }
            
            if (!Schema::hasColumn('transaksis', 'kode_transaksi')) {
                $table->string('kode_transaksi')->nullable()->after('tanggal_transaksi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'tanggal_transaksi')) {
                $table->dropColumn('tanggal_transaksi');
            }
            if (Schema::hasColumn('transaksis', 'kode_transaksi')) {
                $table->dropColumn('kode_transaksi');
            }
        });
    }
};