<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPackage extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'website_id',
        'title',
        'slug',
        'description',
        'price_start_from',
        'thumbnail_url',
        'duration_text',
        'itinerary',
        'includes',
        'excludes',
        'notes',
        'is_featured',
        'translations',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_start_from' => 'decimal:2',
            'itinerary' => 'array',
            'includes' => 'array',
            'excludes' => 'array',
            'is_featured' => 'boolean',
            'translations' => 'array',
        ];
    }

    /**
     * Get a translated attribute for a given locale.
     */
    public function getTranslation(string $field, string $locale): mixed
    {
        return $this->translations[$locale][$field] ?? null;
    }

    /**
     * Get the website that owns the tour package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Website, TourPackage>
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get all images for the tour package (polymorphic).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<ProductImage>
     */
    public function images()
    {
        return $this->morphMany(ProductImage::class, 'imageable', 'image_type', 'parent_id');
    }
}
