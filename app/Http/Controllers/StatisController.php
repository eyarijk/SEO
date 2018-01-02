<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Context;

class StatisController extends Controller
{
    public function rules(){
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('rules')->withUser($user)->withContexts($contexts);
    }
}
