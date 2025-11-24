<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soals')->cascadeOnDelete();

            // Jawaban PG
            $table->foreignId('pilihan_jawaban_id')->nullable()->constrained('pilihan_jawabans')->nullOnDelete();

            // Jawaban Esai
            $table->text('jawaban_esai')->nullable();

            // Penilaian dosen
            $table->integer('skor_dosen')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_mahasiswas');
    }
};
