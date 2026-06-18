<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CamImage extends Model
{
    protected $fillable = ['device_id', 'filename', 'path', 'size'];

    protected $casts = [
        'size'       => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
