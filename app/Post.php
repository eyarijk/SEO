<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','author_id');
    }
    public function likepost()
    {
        return $this->belongsToMany('App\User', 'post_user');
    }
}
