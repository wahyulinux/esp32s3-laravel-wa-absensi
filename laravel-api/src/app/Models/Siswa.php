<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = ['rfid_uid', 'nis', 'nama', 'kelas', 'kelas_id', 'jurusan_id', 'foto', 'no_hp_ortu', 'aktif'];

    protected $attributes = [
        'aktif' => true,
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kelasMaster(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto
            ? Storage::disk('public')->url($this->foto)
            : null;
    }
}
