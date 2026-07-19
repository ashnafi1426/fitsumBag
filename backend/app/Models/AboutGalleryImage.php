<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutGalleryImage extends Model
{
    protected $fillable = [
        'image_url',
        'title',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Default scope to always order by sort_order
    protected static function booted()
    {
        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('sort_order', 'asc');
        });
    }
}
