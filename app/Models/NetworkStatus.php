<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkStatus extends Model
{
    protected $fillable = ['service', 'status', 'message', 'sort_order'];

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order');
    }

    public function isOperational(): bool
    {
        return $this->status === 'operational';
    }
}
