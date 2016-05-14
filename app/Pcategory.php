<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pcategory extends Model
{
    //
    public function posts()
    {
        return $this->belongsToMany('App\Post', 'post_categories');
    }
}
