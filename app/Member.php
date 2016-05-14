<?php

namespace App;

use App\Relations\HasManyThroughBelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    //
    protected $fillable = ['firstname', 'lastname', 'username', 'email', 'password', 'remember_token', 'confirmation_code', 'address', 'region_id', 'phone', 'zipcode', 'image', 'active'];

    public function fullName(){
        return $this->firstname.' '.$this->lastname;
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public function hasCompanies()
    {
        return $this->hasMany('App\Company', 'owner_id');
    }

    public function companies()
    {
        return $this->belongsToMany('App\Company', 'company_member')->withTimestamps();
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    public function regions()
    {
        return $this->belongsToMany('App\Region', 'member_region')->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function favCompanies()
    {
        return $this->belongsToMany('App\Company', 'fav_companies')->withTimestamps();
    }
    
    public function favProducts()
    {
        return $this->belongsToMany('App\Product', 'fav_products')->withTimestamps();
    }

    public function addresses()
    {
        return $this->hasMany('App\MemberAddress');
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
