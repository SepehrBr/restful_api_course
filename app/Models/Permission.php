<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory, SoftDeletes;

    // relation
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // set every permission to Admin
    protected static function boot()
    {
        parent::boot();
        static::created(function ($permission) {
            Role::where('name', 'admin')->first()->permissions()->attach([ $permission->id ]);
        });
    }
}
