<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_masuk',
        'foto_masuk',
        'waktu_keluar',
        'foto_keluar',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function getFotoMasukUrlAttribute(): ?string
    {
        return $this->foto_masuk
            ? Storage::disk('public')->url($this->foto_masuk)
            : null;
    }

    public function getFotoKeluarUrlAttribute(): ?string
    {
        return $this->foto_keluar
            ? Storage::disk('public')->url($this->foto_keluar)
            : null;
    }
}
