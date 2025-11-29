<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('soals', function (Blueprint $table) {
        $table->text('type')->change();
    });

    DB::statement("ALTER TABLE soals RENAME TO soals_old");

    DB::statement("
        CREATE TABLE soals (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pertanyaan TEXT NOT NULL,
            image_path TEXT NULL,
            type TEXT CHECK(type IN ('pg','esai')),
            ujian_id INTEGER NOT NULL,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (ujian_id) REFERENCES ujians(id) ON DELETE CASCADE
        )
    ");

    DB::statement("
        INSERT INTO soals (id, pertanyaan, image_path, type, ujian_id, created_at, updated_at)
        SELECT id, pertanyaan, image_path, type, ujian_id, created_at, updated_at
        FROM soals
    ");

    DB::statement("DROP TABLE soals_old");
}

};
