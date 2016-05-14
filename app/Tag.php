<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Tag extends Model implements SluggableInterface
{
    //
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];

    public function products()
    {
    	return $this->belongsToMany('App\Product', 'category_product');
    }
    
}
