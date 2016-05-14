<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'permissions')->withPivot('permission');
    }
}
