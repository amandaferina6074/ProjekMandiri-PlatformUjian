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
        Schema::table('hasil_ujians', function (Blueprint $table) {
            $table->renameColumn('skor', 'skor_pg');
            
            // 2. Tambahkan kolom baru
            
            $table->integer('skor_esai')->nullable()->after('skor_pg'); 
            
            // Kolom untuk skor final (misal: (skor_pg + skor_esai) / 2)
            $table->integer('total_skor')->nullable()->after('skor_esai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            $table->dropColumn(['skor_esai', 'total_skor']);
            $table->renameColumn('skor_pg', 'skor');
        });
    }
};