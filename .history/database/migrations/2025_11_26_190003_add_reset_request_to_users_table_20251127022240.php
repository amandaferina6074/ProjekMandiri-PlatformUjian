<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Cek dulu: Kalau kolom belum ada, baru buat.
        if (!Schema::hasColumn('users', 'password_reset_requested_at')) {
            $table->timestamp('password_reset_requested_at')->nullable();
        }
    });
}
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('password_reset_requested_at');
    });
}
};
