<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    public function products()
    {
        return $this->morphedByMany('App\Product', 'imageable');
    }

    public function posts()
    {
        return $this->morphedByMany('App\Post', 'imageable');
    }

    public function companies()
    {
        return $this->morphedByMany('App\Company', 'imageable');
    }
    
    public function regions()
    {
        return $this->morphedByMany('App\Product', 'imageable');
    }

}
