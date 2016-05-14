<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function posts()
    {
        return $this->morphToMany('App\Post');
    }

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
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
