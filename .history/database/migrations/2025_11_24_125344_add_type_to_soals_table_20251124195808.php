<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('soals', function (Blueprint $t) {
        $t->string('type')->default('pilihan_ganda'); // atau tanpa default
        $t->string('image_path')->nullable();
    });
}

public function down(): void
{
    Schema::table('soals', function (Blueprint $t) {
        $t->dropColumn(['type', 'image_path']);
    });
}

};
