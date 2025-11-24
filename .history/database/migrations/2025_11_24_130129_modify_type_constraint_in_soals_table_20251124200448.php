<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Rename old table
    Schema::rename('soals', 'soals_old');

    Schema::create('soals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ujian_id')->constrained()->cascadeOnDelete();
        $table->text('pertanyaan');
        $table->timestamps();
        $table->string('image_path')->nullable();
        // ubah CHECK constraint
        $table->string('type')->default('pilihan_ganda');
    });

    // copy data
    DB::statement("
        INSERT INTO soals (id, ujian_id, pertanyaan, created_at, updated_at, image_path, type)
        SELECT id, ujian_id, pertanyaan, created_at, updated_at, image_path,
            CASE
                WHEN type IN ('pg','pilihan_ganda') THEN 'pilihan_ganda'
                WHEN type = 'esai' THEN 'esai'
                ELSE 'pilihan_ganda'
            END
        FROM soals_old
    ");

    Schema::drop('soals_old');
}

public function down(): void
{
    // not required
}

};
