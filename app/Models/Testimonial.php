<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'customer_name', 'organization', 'quote', 'rating', 'avatar', 'is_published', 'sort_order',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderBy('sort_order');
    }
}
