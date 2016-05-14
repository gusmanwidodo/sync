<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviewable extends Model
{
    //
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function companies()
    {
        return $this->morphedByMany('App\Company');
    }

    public function products()
    {
        return $this->morphedByMany('App\Product');
    }

}
