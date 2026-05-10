<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'website_id',
        'template_id',
        'site_title',
        'primary_color',
        'secondary_color',
        'font_family',
        'font_heading',
        'font_body',
        'hero_title',
        'hero_subtitle',
        'hero_image_url',
        'seo_meta_title',
        'seo_meta_description',
        'gallery_images',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
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
     * Get the website that owns the setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Website, WebsiteSetting>
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get the template used by the website setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Template, WebsiteSetting>
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
