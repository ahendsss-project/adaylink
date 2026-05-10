<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name',
        'tier',
        'folder_name',
        'thumbnail_url',
        'is_active',
        'config_schema',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'config_schema' => 'array',
        ];
    }

    /**
     * Get the website settings using this template.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<WebsiteSetting>
     */
    public function websiteSettings()
    {
        return $this->hasMany(WebsiteSetting::class);
    }
}
