<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function checkedmessage()
    {
        return $this->hasMany('App\Checkedmessage');
    }
    public function comments()
    {
        return $this->morphMany('App\Comment','commentable');
    }
}
