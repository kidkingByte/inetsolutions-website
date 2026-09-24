<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverageArea extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'region', 'district', 'ward', 'street', 'service_type',
        'technology', 'status', 'installation_available', 'notes',
    ];

    protected $casts = ['installation_available' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->whereIn('status', ['available', 'coming_soon', 'under_expansion']);
    }
}
