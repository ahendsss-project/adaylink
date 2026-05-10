<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'max_tours',
        'max_vehicles',
        'max_pages',
        'allowed_template_tier',
        'is_active',
        'features',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'features' => 'array',
        ];
    }

    /**
     * Get the users subscribed to this plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<User>
     */
    public function users()
    {
        return $this->hasMany(User::class, 'plan_id');
    }

    /**
     * Get the transactions for this plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Transaction>
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'plan_id');
    }

    /**
     * Available feature keys with their labels.
     */
    public static function availableFeatures(): array
    {
        return [
            'floating_whatsapp' => 'Floating WhatsApp',
            'social_share' => 'Tombol Share Social Media',
            'gallery_lightbox' => 'Gallery (Lightbox)',
            'reviews' => 'Review & Rating',
            'multilanguage' => 'Multi-Bahasa (Translate)',
            'custom_domain' => 'Custom Domain',
        ];
    }

    /**
     * Check if a specific feature is enabled for this plan.
     */
    public function hasFeature(string $feature): bool
    {
        return (bool) ($this->features[$feature] ?? false);
    }

    /**
     * Get all enabled features for this plan.
     */
    public function enabledFeatures(): array
    {
        return array_keys(array_filter($this->features ?? []));
    }
}
