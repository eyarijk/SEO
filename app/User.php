<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
    public function favorite_task()
    {
        return $this->belongsToMany('App\Task', 'favorite');
    }
    public function pay()
    {
        return $this->belongsToMany('App\Task', 'pay');
    }
    public function rejected()
    {
        return $this->belongsToMany('App\Task', 'rejected');
    }
    public function trash()
    {
        return $this->belongsToMany('App\Task', 'deleting');
    }
    public function report()
    {
        return $this->hasMany('App\Report');
    }
    public function notification()
    {
        return $this->hasMany('App\Notification');
    }
    public function context()
    {
        return $this->hasMany('App\Context');
    }
    public function purse()
    {
        return $this->hasMany('App\Purse');
    }
    public function message()
    {
        return $this->hasMany('App\Message');
    }
    public function checkedmessage()
    {
        return $this->hasMany('App\Checkedmessage');
    }
    public function surfing()
    {
        return $this->hasMany('App\Surfing');
    }
    public function checkedsurfing()
    {
        return $this->hasMany('App\Checkedsurfing');
    }
    public function banner()
    {
        return $this->hasMany('App\Banner');
    }
    public function likepost()
    {
        return $this->belongsToMany('App\Post', 'post_user');
    }
}
