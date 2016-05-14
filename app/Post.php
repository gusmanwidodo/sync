<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Post extends Model implements SluggableInterface
{
    //
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function regions()
    {
    	return $this->belongsToMany('App\Region', 'post_region');
    }

    public function images()
    {
        return $this->morphToMany('App\Image', 'imageable');
    }

    public function members()
    {
        return $this->morphedByMany('App\Member', 'postable');
    }

    public function admins()
    {
        return $this->morphedByMany('App\Admin', 'postable');
    }

}
