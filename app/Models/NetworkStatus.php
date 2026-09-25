<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class NetworkStatus extends Model
{
    use Auditable;

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
