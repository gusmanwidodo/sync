<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public function admins()
    {
        return $this->hasMany('App\Admin');
    }

    public function members()
    {
        return $this->hasMany('App\Member');
    }

    public function resources()
    {
        return $this->belongsToMany('App\Resource', 'permissions')->withPivot('permission');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {

        });

        static::updating(function ($customer) {

        });

        static::deleting(function ($customer) {

        });

        static::created(function ($customer) {

        });

        static::updated(function ($customer) {

        });

        static::deleted(function ($customer) {

        });

    }
}
