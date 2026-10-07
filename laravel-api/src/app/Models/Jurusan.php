<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = ['kode', 'nama', 'aktif'];

    protected $attributes = [
        'aktif' => true,
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }
}
