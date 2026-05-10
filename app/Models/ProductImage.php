<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'parent_id',
        'image_type',
        'url',
        'alt_text',
    ];

    /**
     * Get the parent imageable model (TourPackage or Vehicle).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable()
    {
        return $this->morphTo(__FUNCTION__, 'image_type', 'parent_id');
    }
}
