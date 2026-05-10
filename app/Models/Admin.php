<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $fillable = [
        'role_id',
        'email',
        'password',
        'full_name',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role of the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Role, Admin>
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the blog posts authored by the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BlogPost>
     */
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    /**
     * Get the transactions approved by the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Transaction>
     */
    public function approvedTransactions()
    {
        return $this->hasMany(Transaction::class, 'approved_by');
    }
}
