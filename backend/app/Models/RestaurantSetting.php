<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'slug',
        'logo',
        'phone',
        'email',
        'address',
        'location',
        'currency',
        'language',
        'is_active',
        'business_hours',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'tiktok_url',
        'telegram_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'business_hours' => 'array',
    ];
}
