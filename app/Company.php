<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model implements SluggableInterface
{
    //
    use SluggableTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'owner_id',
        'region_id',
        'name',
        'tagline',
        'description',
        'slug',
        'email',
        'address',
        'city',
        'province',
        'zipcode',
        'phone',
        'bank_name',
        'bank_number',
        'bank_address',
        'bank_city',
        'bank_owner',
        'shippings',
        'facebook',
        'twitter',
        'instagram',
        'website',
        'logo',
        'banner',
        'active',
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
        'separator' => '',
        'on_update' => false
    ];

    public function countProduct(){
        return Product::where("company_id", $this->id)->count();
    }
    
    public function members()
    {
    	return $this->belongsToMany('App\Member', 'company_member');
    }

    public function favMembers()
    {
    	return $this->belongsToMany('App\Member', 'fav_companies');
    }

    public function reviews()
    {
        return $this->morphToMany('App\Review', 'reviewable');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    public function owner(){
        return $this->belongsTo('App\Member');
    }

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
    }

    public function isOwner(){
        return auth('member')->user()->id == $this->owner_id;
    }
    
}
