<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name',
        'description',
    ];

    /**
     * Get the admins for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Admin>
     */
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * The permissions that belong to the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Permission>
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }
}
