<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model implements SluggableInterface
{
    //
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];

    public function regions()
    {
        return $this->belongsToMany('App\Region', 'investment_region')->withPivot('content', 'data');
    }
}
