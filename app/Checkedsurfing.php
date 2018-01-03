<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkedsurfing extends Model
{
    public function surfing()
    {
        return $this->belongsTo('App\Surfing');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
