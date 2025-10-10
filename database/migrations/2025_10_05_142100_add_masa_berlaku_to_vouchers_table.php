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
    Schema::table('vouchers', function (Blueprint $table) {
        $table->date('tanggal_mulai')->nullable()->after('diskon');
        $table->date('tanggal_berakhir')->nullable()->after('tanggal_mulai');
        $table->integer('batas_penggunaan')->nullable()->after('tanggal_berakhir');
        $table->integer('jumlah_digunakan')->default(0)->after('batas_penggunaan');
    });
}

public function down(): void
{
    Schema::table('vouchers', function (Blueprint $table) {
        $table->dropColumn(['tanggal_mulai', 'tanggal_berakhir', 'batas_penggunaan', 'jumlah_digunakan']);
    });
}

};
