<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Limit;
use App\Notification;
use App\Referral;
use App\Report;
use App\Task;
use App\User;
use Illuminate\Http\Request;

class WorktaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = Task::find($request->id);
        $task->available--;
        $report = new Report;
        $report->user_id = auth()->id();
        $report->task_id = $request->id;
        $report->save();
        $task->save();
        return redirect()->back()->withToaststatus('success')->withToast('Работа начата!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'answer' => 'required|max:3000',
        ));

        $report = Report::find($id);
        $now_time = now();
        $finish_time = $report->created_at->addHours($report->task->time);
        $task = $report->task()->first();
        $slug = $task->slug;
        if ($finish_time->gt($now_time)){
            if($task->type == true){
                if($task->question == trim(strtolower($request->answer))){
                    $report->task->success++;
                    $report->user->balance += $report->task->salary;
                    $report->user->pay()->attach($report->task_id);
                    $available = $report->task->available;

                    $limit = new Limit;
                    $limit->task_id = $report->task->id;
                    $limit->user_id = $report->user->id;
                    $limit->finish_at = now()->addHours($report->task->period);
                    $limit->save();

                    if($available <= 0){
                        $report->task->is_show = false;
                        $notification = new Notification;
                        $notification->user_id = $report->task->user_id;
                        $notification->description = 'Закончились деньги на задании: <a href="/tasks/'.$report->task->slug.'">Пополнить задание: «'.$report->task->name.'»</a> .';
                        $notification->status = 'is-info';
                        $notification->save();
                    }

                    $notification = new Notification;
                    $notification->user_id = $report->user_id;
                    $notification->description = 'Оплачено задание: <a href="/tasks/'.$report->task->slug.'">«'.$report->task->name.'»</a> +'.$report->task->salary.' ₽.';
                    $notification->status = 'is-success';
                    $notification->save();
                    #referral system
                    $referral = Referral::where('referral_id',$report->user->id)->first();
                    if (count($referral) > 0){
                        $bonus = $report->task->salary / 100 * $referral->user->percent_referrals;
                        $referral->profit += $bonus;
                        $referral->user->balance += $bonus;
                        $referral->save();
                        $referral->user->save();
                    }

                    $report->task->save();
                    $report->user->save();
                    $report->delete();
                } else {
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
                }
            } elseif ($task->technology == true) {
               $limit = new Limit;
               $limit->task_id = $report->task->id;
               $limit->user_id = $report->user->id;
               $limit->finish_at = now()->addHours($report->task->period);
               $limit->save();

                if($report->task->available <= 0) {
                    $report->task->is_show = false;
                }

               $report->finished = true;
               $report->answer = $request->answer;
               $report->task->save();
               $report->save();

               $notification = new Notification;
               $notification->user_id = $report->task->user_id;
               $notification->description = 'У Вас новый отчёт! Задание: <a href="/manage/tasks/">'.$report->task->name.'</a> от : '.$report->user->name.' .';
               $notification->status = 'is-warning';
               $notification->save();

               return redirect()->back()->withToaststatus('success')->withToast('Отчёт отправлен!');

            } else {
                $report->finished = true;
                $report->answer = $request->answer;
                if($report->task->available <= 0) {
                    $report->task->is_show = false;
                }
                $report->task->save();
                $report->save();



                $notification = new Notification;
                $notification->user_id = $report->task->user_id;
                $notification->description = 'У Вас новый отчёт! Задание: <a href="/manage/tasks/">'.$report->task->name.'</a> от : '.$report->user->name.' .';
                $notification->status = 'is-warning';
                $notification->save();
            }

        }else{
            $report->delete();

        }

        return redirect()->to('/tasks/'.$slug);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Report::find($id)->delete();
        return redirect()->back()->withToaststatus('success')->withToast('Отчёт удален!');
    }
}
