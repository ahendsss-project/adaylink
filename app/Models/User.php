<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['plan_id', 'email', 'password', 'full_name', 'phone', 'subscription_plan', 'subscription_status', 'subscription_expires_at', 'is_verified', 'is_blocked', 'admin_note'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasUuids, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subscription_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_blocked' => 'boolean',
        ];
    }

    /**
     * Get the subscription plan of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<SubscriptionPlan, User>
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Get the websites owned by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Website>
     */
    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    /**
     * Get the transactions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Transaction>
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
