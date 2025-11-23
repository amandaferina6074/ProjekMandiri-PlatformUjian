<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'durasi_menit',
        'available_from',
        'available_to',
        'token', // Tambahkan ini
    ];

    // Cast tanggal
    protected $casts = [
        'available_from' => 'datetime',
        'available_to' => 'datetime',
    ];

    // Relasi ke pembuat ujian
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke soal
    public function soals(): HasMany
    {
        return $this->hasMany(Soal::class);
    }

    // Relasi ke hasil ujian
    public function hasilUjians(): HasMany
    {
        return $this->hasMany(HasilUjian::class);
    }
}
