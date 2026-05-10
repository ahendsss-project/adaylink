<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'site_name',
        'subdomain',
        'custom_domain',
        'custom_domain_dns_token',
        'custom_domain_verified_at',
        'logo_url',
        'contact_whatsapp',
        'is_active',
        'default_locale',
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
            'custom_domain_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user who owns the website.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Website>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the website setting for the website.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<WebsiteSetting>
     */
    public function websiteSetting()
    {
        return $this->hasOne(WebsiteSetting::class);
    }

    /**
     * Get the tour packages for the website.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TourPackage>
     */
    public function tourPackages()
    {
        return $this->hasMany(TourPackage::class);
    }

    /**
     * Get the vehicles for the website.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Vehicle>
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
