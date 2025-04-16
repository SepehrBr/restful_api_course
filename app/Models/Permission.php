<?php

namespace App\Models;

use App\Traits\HasRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory, SoftDeletes, HasRules;
    
    protected $guarded = [];

    // validation rules
    protected static $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'display_name' => 'required|string|max:500',
    ];

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
