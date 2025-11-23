<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soal extends Model
{
    use HasFactory;
    
    // Kolom yang boleh diisi massal
    protected $fillable = ['ujian_id', 'pertanyaan', 'image_path', 'type'];

    // Relasi ke tabel ujians
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    // Relasi ke tabel pilihan_jawabans
    public function pilihanJawabans()
    {
        return $this->hasMany(PilihanJawaban::class);
    }

    // Relasi ke tabel jawaban_mahasiswas
    public function jawabanMahasiswas(): HasMany
    {
        return $this->hasMany(JawabanMahasiswa::class);
    }
}
