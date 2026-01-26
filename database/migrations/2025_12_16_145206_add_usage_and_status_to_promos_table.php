<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->boolean('aktif')->default(true)->after('amount');
            $table->integer('batas_penggunaan')->nullable()->after('aktif');
            $table->integer('jumlah_digunakan')->default(0)->after('batas_penggunaan');
            $table->date('tanggal_mulai')->nullable()->after('jumlah_digunakan');
            $table->date('tanggal_berakhir')->nullable()->after('tanggal_mulai');
        });
    }

    public function down()
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn([
                'aktif',
                'batas_penggunaan',
                'jumlah_digunakan',
                'tanggal_mulai',
                'tanggal_berakhir',
            ]);
        });
    }
};
