<?php

namespace App\Http\Controllers;

use App\Checkedmessage;
use App\Limit;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function limittask(){
        $now_time = now();
        $limits = Limit::where('finish_at','<',$now_time)->get();
        foreach ($limits as $limit){
           $limit->user->pay()->detach($limit->task_id);
           $limit->delete();
        }
        $checked = Checkedmessage::where('finish_at','<',$now_time)->get();
        foreach ($checked as $ch) {
            $ch->delete();
        }
        return "GooD!";
    }
}
