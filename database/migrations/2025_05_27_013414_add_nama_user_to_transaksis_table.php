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
        if (!Schema::hasColumn('transaksis', 'nama_user')) {
            $table->string('nama_user')->nullable()->after('user_id');
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('nama_user'); // Hapus kolom nama_user jika di-rollback
        });
    }
};
