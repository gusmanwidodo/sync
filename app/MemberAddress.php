<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberAddress extends Model
{
    public function member(){
        return $this->belongsTo('App\Member');
    }
}
