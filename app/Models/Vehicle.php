<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'website_id',
        'model_name',
        'capacity_people',
        'price_per_day',
        'image_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
        ];
    }

    /**
     * Get the website that owns the vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Website, Vehicle>
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get all images for the vehicle (polymorphic).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<ProductImage>
     */
    public function images()
    {
        return $this->morphMany(ProductImage::class, 'imageable', 'image_type', 'parent_id');
    }
}
