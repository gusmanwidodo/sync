<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;

class Product extends Model implements SluggableInterface
{
    //
    use SluggableTrait;
    use AlgoliaEloquentTrait;

//    protected $sluggable = [
//        'build_from' => 'name',
//        'save_to' => 'slug',
//    ];
//
//    public $algoliaSettings = [
//        'attributesToIndex' => [
//            'id',
//            'name',
//            'price',
//            'slug',
//        ],
//        'customRanking' => [
//            'desc(id)',
//            'asc(name)',
//        ],
//    ];

    public function getAlgoliaRecord()
    {
        $data = array();

        if ($this->publish > 0 && $this->company_id > 0) {
            $data = [
                'product_name' => $this->name,
                'product_price' => $this->price,
                'company_name' => $this->company ? $this->company->name : NULL,
                'region_name' => $this->company ? ($this->company->region ? $this->company->region->name : NULL) : NULL,
                'product_url' => route('product.detail', ['slug' => product_term($this)]),
                'company_url' => $this->company ? route('company.profile', ['company' => $this->company->slug ]) : NULL,
                'region_url' => $this->company ? ($this->company->region ? route('region.profile', ['slug' => $this->company->region->slug]) : NULL) : NULL,
                'product_thumb' => get_product_thumb($this->images),
                'company_logo' => $this->company ? get_logo($this->company->logo) : NULL,
                'region_logo' => $this->company ? ($this->company->region ? get_logo($this->company->region->logo) : NULL) : NULL
            ];
        }

        return $data;
    }
//
//    public function autoIndex()
//    {
//        if (\App::environment() === 'local') {
//            return false;
//        }
//
//        return true;
//    }
//
//    public function autoDelete()
//    {
//        if (\App::environment() === 'local') {
//            return false;
//        }
//
//        return true;
//    }

    /**
     * END ALGOLIA
     */

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
    }

    public function reviews()
    {
        return $this->morphToMany('App\Review', 'reviewable');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_product');
    }

    public function favMembers()
    {
        return $this->belongsToMany('App\Member', 'fav_products');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'product_tag');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
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
