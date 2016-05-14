<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    public function role()
    {
        $this->belongsTo('App\Role');
    }
}
