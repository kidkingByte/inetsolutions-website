<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'body', 'cover_image',
        'author', 'meta_title', 'meta_description', 'is_published', 'published_at', 'views',
    ];

    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function scopePublished($q)
    {
        return $q->where('is_published', true)
            ->where(fn ($x) => $x->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
