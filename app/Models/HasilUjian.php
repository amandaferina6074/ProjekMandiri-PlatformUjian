<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilUjian extends Model
{
    use HasFactory;

    // Otomatis ubah kolom waktu menjadi objek datetime
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'user_id',
        'ujian_id',
        'skor',
        'started_at',
        'finished_at',
    ];

    // Relasi ke tabel users (mahasiswa)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel ujians
    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }

    // Relasi ke tabel jawaban_mahasiswas
    public function jawabanMahasiswas(): HasMany
    {
        return $this->hasMany(JawabanMahasiswa::class);
    }
}
