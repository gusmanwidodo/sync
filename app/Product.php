<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Product extends Model implements SluggableInterface
{

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug'
    ];

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_product');
    }

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
    }

}
