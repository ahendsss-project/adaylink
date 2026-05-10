<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasUuids;

    protected $fillable = [
        'website_id',
        'title',
        'slug',
        'content',
        'is_published',
        'sort_order',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'translations' => 'array',
        ];
    }

    /**
     * Get a translated attribute for a given locale.
     */
    public function getTranslation(string $field, string $locale): ?string
    {
        return $this->translations[$locale][$field] ?? null;
    }

    /**
     * Get the website that owns the page.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
