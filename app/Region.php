<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Company;
use App\Product;
use App\Investment;

class Region extends Model implements SluggableInterface
{
    //
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];

    public function managers()
    {
    	return $this->belongsToMany('App\Member', 'member_region');
    }

    public function members(){
        return $this->hasMany('App\Member');
    }

    public function regent(){
        return $this->belongsTo('App\Member', 'regent_id');
    }

    public function viceRegent(){
        return $this->belongsTo('App\Member', 'viceregent_id');
    }

    public function posts()
    {
    	return $this->belongsToMany('App\Post', 'post_region')->withTimestamps();
    }

    public function province()
    {
        return $this->belongsTo('App\Province', 'province_id');
    }

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

    public function countCompany(){
        return Company::where('region_id', $this->id)->count();
    }

    public function products()
    {
        return $this->hasManyThrough('App\Product', 'App\Company');
    }

    public function countProduct(){
        return Product::whereRaw("company_id IN (SELECT id FROM companies WHERE region_id='".$this->id."')")->count();
    }
    
    public function investments()
    {
        return $this->belongsToMany('App\Investment', 'investment_region')->withPivot('content', 'data');
    }
    
    public function investment($invest_slug)
    {
        $investment = Investment::where('slug', $invest_slug)->first();
        if(!$investment) return false;


        return $this->belongsToMany('App\Investment', 'investment_region')->withPivot('content', 'data')->wherePivot('investment_id', $investment->id)->first();
    }

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
    }

}
