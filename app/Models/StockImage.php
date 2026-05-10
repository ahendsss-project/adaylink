<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImage extends Model
{
    protected $fillable = [
        'category',
        'title',
        'image_url',
        'alt_text',
    ];
}
