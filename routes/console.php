<?php

use Illuminate\Foundation\Inspiring;
use App\Limit;
use App\Checkedmessage;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('limit', function () {
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
    $this->info('Limit finished');
})->describe('Validation limit on task and message');
