<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasUuids;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'category',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    /**
     * Get the admin who authored the blog post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Admin, BlogPost>
     */
    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }
}
