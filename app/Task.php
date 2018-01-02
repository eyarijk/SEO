<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function report()
    {
        return $this->hasMany('App\Report');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function comments()
    {
        return $this->morphMany('App\Comment','commentable');
    }
    public function limit()
    {
        return $this->hasMany('App\Limit');
    }

}
