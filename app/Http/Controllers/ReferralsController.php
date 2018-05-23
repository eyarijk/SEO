<?php

namespace App\Http\Controllers;

use App\User;
use App\Context;
use App\Referral;
use Illuminate\Http\Request;

class ReferralsController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $referrals = Referral::where('user_id',$user->id)->orderBy('id','desc')->with('referral')->paginate(25);
        return view('referrals.index')
            ->withContexts($contexts)
            ->withUser($user)
            ->withReferrals($referrals);
    }
}
