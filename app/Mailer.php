<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mailer extends Model
{
    //
    protected $fillable = ['from', 'to', 'subject', 'message', 'sent'];
}
