<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotifications extends Model
{
    protected $fillable = [
        'title',
        'body',
        'type',
        'data',
        'read_at',
        'source',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }
}
