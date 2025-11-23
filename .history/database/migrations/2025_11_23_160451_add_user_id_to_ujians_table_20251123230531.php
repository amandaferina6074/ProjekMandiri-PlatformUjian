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
        Schema::table('ujians', function (Blueprint $table) {
            // Tambahkan kolom user_id jika belum ada
            if (!Schema::hasColumn('ujians', 'user_id')) {
                $table->foreignId('user_id')
                      ->nullable() // Bisa null (opsional)
                      ->after('id') // Letakkan setelah ID
                      ->constrained('users') // Relasi ke tabel users
                      ->onDelete('set null'); // Jika Dosen dihapus, set null
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            if (Schema::hasColumn('ujians', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};