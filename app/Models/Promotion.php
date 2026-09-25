<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'image', 'link', 'placement', 'is_active', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean', 'starts_at' => 'datetime', 'ends_at' => 'datetime',
    ];

    public function scopeLive($q)
    {
        return $q->where('is_active', true)
            ->where(fn ($x) => $x->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($x) => $x->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }
}
