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
        'allowed_plan_ids',
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
            'allowed_plan_ids' => 'array',
        ];
    }

    /**
     * Check if this template is accessible by a given plan.
     * If allowed_plan_ids is empty/null, the template is accessible by all plans.
     */
    public function isAccessibleByPlan(?int $planId): bool
    {
        if (empty($this->allowed_plan_ids)) {
            return true;
        }

        if ($planId === null) {
            return false;
        }

        return in_array($planId, $this->allowed_plan_ids);
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
