<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'category', 'speed', 'download_speed', 'upload_speed', 'price',
        'installation_fee', 'validity', 'recommended_users', 'router_info',
        'fair_usage_policy', 'installation_time', 'description', 'features',
        'is_featured', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'price' => 'decimal:2',
        'installation_fee' => 'decimal:2',
    ];

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function scopeCategory($q, $category)
    {
        return $category ? $q->where('category', $category) : $q;
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('price');
    }

    public function getFeatureListAttribute(): array
    {
        return $this->features ?? [];
    }
}
