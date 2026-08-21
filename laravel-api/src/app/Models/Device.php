<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id', 'nama', 'ip_address', 'last_seen', 'firmware_version'];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function getStatusAttribute(): string
    {
        if (! $this->last_seen) return 'offline';
        $minutes = now()->diffInMinutes($this->last_seen, absolute: true);
        if ($minutes < 2)  return 'online';
        if ($minutes < 10) return 'warning';
        return 'offline';
    }
}
