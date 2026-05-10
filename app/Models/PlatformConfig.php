<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformConfig extends Model
{
    protected $fillable = [
        'app_name',
        'tagline',
        'main_logo_url',
        'favicon_url',
        'admin_whatsapp',
        'admin_email',
        'social_links',
        'maintenance_mode',
        'google_analytics_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'maintenance_mode' => 'boolean',
        ];
    }
}
