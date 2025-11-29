<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // === 1. Perbaiki tabel pilihan_jawabans ===
        DB::statement("ALTER TABLE pilihan_jawabans RENAME TO pilihan_jawabans_old");

        Schema::create('pilihan_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soals')->cascadeOnDelete();
            $table->text('teks_pilihan');
            $table->boolean('apakah_benar')->default(false);
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO pilihan_jawabans (id, soal_id, teks_pilihan, apakah_benar, created_at, updated_at)
            SELECT id, soal_id, teks_pilihan, apakah_benar, created_at, updated_at
            FROM pilihan_jawabans_old
        ");

        DB::statement("DROP TABLE pilihan_jawabans_old");


        // === 2. Perbaiki tabel jawaban_mahasiswas ===
        DB::statement("ALTER TABLE jawaban_mahasiswas RENAME TO jawaban_mahasiswas_old");

        Schema::create('jawaban_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soals')->cascadeOnDelete();
            $table->foreignId('pilihan_jawaban_id')->nullable()->constrained('pilihan_jawabans')->nullOnDelete();
            $table->text('jawaban_esai')->nullable();
            $table->integer('skor_dosen')->nullable();
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO jawaban_mahasiswas (id, hasil_ujian_id, soal_id, pilihan_jawaban_id, jawaban_esai, skor_dosen, created_at, updated_at)
            SELECT id, hasil_ujian_id, soal_id, pilihan_jawaban_id, jawaban_esai, skor_dosen, created_at, updated_at
            FROM jawaban_mahasiswas_old
        ");

        DB::statement("DROP TABLE jawaban_mahasiswas_old");
    }

    public function down()
    {
        // optional — bisa saya buatkan jika perlu
    }
};
