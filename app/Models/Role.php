<?php

namespace App\Models;

use App\Traits\HasRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, SoftDeletes, HasRules;
    protected $guarded = [];

    // validation rules
    protected static $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'display_name' => 'required|string|max:500',
    ];

    // relations
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
