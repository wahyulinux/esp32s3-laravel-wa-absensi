<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = ['rfid_uid', 'nis', 'nama', 'kelas', 'no_hp_ortu', 'aktif'];

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
}
