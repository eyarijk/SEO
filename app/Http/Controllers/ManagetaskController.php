<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Notification;
use App\Context;

class ManagetaskController extends Controller
{

    public function task()
    {
        $user = User::find(auth()->id());
        $tasks = $user->tasks()->paginate(10);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();

        return view('manage.task.tasks')->withUser($user)->withTasks($tasks)->withContexts($contexts);
    }

    public function status(Request $request)
    {
        $task = User::find(auth()->id())->tasks()->find($request->id);

        if ($task->is_show == false)
            if ($task->available > 0)
                $task->is_show = true;
            else
                return redirect()->back()->withErrors('Пополните баланс');
        else
            $task->is_show = false;

        $task->save();

        return redirect()->back();
    }

    public function report($id)
    {
        $user = User::find(auth()->id());
        $task = Task::find($id);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();

        if($task->user_id != auth()->id())
            return redirect('/manage/tasks/');

        $tasks = $task->report()->where('finished',true)->paginate(10);

        return view('manage.task.report')->withUser($user)->withTasks($tasks)->withContexts($contexts);
    }

    public function success(Request $request)
    {
        $report = Report::find($request->id);
        $report->task->success++;
        $report->user->balance += $report->task->salary;

        if ($report->task->technology == false)
            $report->user->pay()->attach($report->task_id);

        $available = $report->task->available;
        $report->user->save();
        $report->task->save();
        $report->delete();

        if($available <= 0){
            $notification = new Notification;
            $notification->user_id = auth()->id();
            $notification->description = 'Закончились деньги на задании: <a href="/tasks/'.$report->task->slug.'">Пополнить задание: «'.$report->task->name.'»</a> .';
            $notification->status = 'is-info';
            $notification->save();
        }

        $notification = new Notification;
        $notification->user_id = $report->user_id;
        $notification->description = 'Оплачено задание: <a href="/tasks/'.$report->task->slug.'">«'.$report->task->name.'»</a> +'.$report->task->salary.' ₽.';
        $notification->status = 'is-success';
        $notification->save();

        return redirect('/manage/tasks/');
    }
    public function danger(Request $request)
    {
        $report = Report::find($request->id);
        $report->task->danger++;
        $report->task->available++;
        if($report->task->is_show == false && $report->task->available == 1)
            $report->task->is_show = true;
        $report->user->rejected()->attach($report->task_id);
        $report->user->save();
        $report->task->save();
        $report->delete();

        $notification = new Notification;
        $notification->user_id = $report->user_id;
        $notification->description = 'Отклонено задание: <a href="/tasks/'.$report->task->slug.'">«'.$report->task->name.'»</a> :(';
        $notification->status = 'is-danger';
        $notification->save();

        return redirect('/manage/tasks/');
    }

    public function pay($id)
    {
        $user = User::find(auth()->id());
        $task = $user->tasks()->find($id);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        if(!isset($task))
            return redirect('/manage/tasks/');

        return view('manage.task.pay')->withUser($user)->withTasks($task)->withContexts($contexts);
    }
    public function buy(Request $request)
    {
        $this->validate($request, array(
            'count' => 'required|numeric',
            'id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $task = $user->tasks()->where('id',$request->id)->first();
        $request->count = floor($request->count);
        $price = $request->count * $task->salary * 1.2;

        if ($user->balance < $price)
            return redirect()->back();

        $user->balance -= $price;
        $task->available += $request->count;

        $user->save();
        $task->save();

        $notification = new Notification;
        $notification->user_id = auth()->id();
        $notification->description = 'Пополнение задания: <a href="/tasks/">'.$task->slug.'</a> на : '.intval($request->count).' шт. :) .';
        $notification->status = 'is-primary';
        $notification->save();

        return redirect('/manage/tasks');

    }

}
