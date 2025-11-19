<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            
            // TAMBAHAN BARU DISINI (Edit file lama)
            $table->string('profile_image')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jangan lupa tambahkan disini juga untuk rollback
            $table->dropColumn(['phone', 'gender', 'address', 'profile_image']);
        });
    }
};