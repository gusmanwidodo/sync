<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    public function member()
    {
        return $this->belongsTo('App\Member');
    }
    
    public function companies()
    {
        return $this->morphedByMany('App\Company', 'reviewable');
    }

    public function products()
    {
        return $this->morphedByMany('App\Product', 'reviewable');
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
