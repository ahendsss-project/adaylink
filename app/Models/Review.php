<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'website_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'comment',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'rating' => 'integer',
        ];
    }

    /**
     * Get the website this review belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Website, Review>
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Generate Schema.org JSON-LD for approved reviews.
     */
    public static function generateSchema(Website $website): array
    {
        $reviews = static::where('website_id', $website->id)
            ->where('is_approved', true)
            ->get();

        if ($reviews->isEmpty()) {
            return [];
        }

        $avgRating = round($reviews->avg('rating'), 1);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'AggregateRating',
            'itemReviewed' => [
                '@type' => 'LocalBusiness',
                'name' => $website->websiteSetting?->site_title ?? $website->subdomain,
            ],
            'ratingValue' => (string) $avgRating,
            'bestRating' => '5',
            'worstRating' => '1',
            'ratingCount' => (string) $reviews->count(),
            'review' => $reviews->map(fn ($r) => [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => $r->reviewer_name],
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => (string) $r->rating,
                    'bestRating' => '5',
                ],
                'reviewBody' => $r->comment,
            ])->toArray(),
        ];
    }
}
