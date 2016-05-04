<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Company extends Model implements SluggableInterface
{

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
        'separator' => '',
        'on_update' => true
    ];

    protected $fillable = [
    	'id',
        'owner_id',
        'region_id',
        'name',
        'tagline',
        'description',
        'slug',
        'email',
        'address',
        'city',
        'zipcode',
        'phone',
        'bank_name',
        'bank_number',
        'bank_address',
        'bank_city',
        'bank_owner',
        'shippings',
        'facebook',
        'twitter',
        'instagram',
        'website',
        'logo',
        'banner',
        'active',
    ];

    public function products()
    {
    	$this->hasMany('App\Product');
    }

}
