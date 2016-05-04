<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = ['title', 'file', 'path']; 

    public function products()
    {
        return $this->morphedByMany('App\Product', 'imageable');
    }

}
