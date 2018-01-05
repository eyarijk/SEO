<?php

namespace App\Http\Controllers;

use App\Context;
use Illuminate\Http\Request;
use App\Category;
use App\Task;
use App\User;
use App\Comment;
use App\Notification;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Задания';
        $user = User::find(auth()->id());
        $notIn = $user->trash()->get();

        if (count($notIn) > 0 )
            foreach ($notIn as $val)
                $not_show[] = $val->id;

        $nottrash = $user->pay()->get();

        if (count($nottrash) > 0 )
            foreach ($nottrash as $val)
                $not_show[] = $val->id;

        $notdanger = $user->rejected()->get();

        if (count($notdanger) > 0 )
            foreach ($notdanger as $val)
                $not_show[] = $val->id;
        else
            $not_show[] = 0;



        $tasks = Task::where('is_show', true)
            ->whereNotIn('id', $not_show)
            ->orderBy('success','desc')
            ->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        $category = Category::where('is_show', true)->get();
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('task.create')->withCategory($category)->withUser($user)->withContexts($contexts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, array(
            'name' => 'required|max:100|min:5',
            'description' => 'required|min:10|max:3000',
            'answer' => 'required|min:10|max:500',
            'salary' => 'required|numeric',
            'technology' => 'required|boolean',
            'time' => 'required|numeric',
            'type' => 'required|numeric',
            'category_id' => 'required|numeric',
        ));

        if ($request->technology == 1) {
            $this->validate($request, array('period' => 'required'));
            $period = $request->period;
        } else {
            $period = null;
        }

        if ($request->type == 1) {
            $this->validate($request, array('question' => 'required|min:10|max:500'));
            $question = $request->question;
        } else {
            $question = null;
        }

        if ((float)$request->salary < 0.2)
            return redirect()->back()->withSalary('Цена должна быть больше чем 0.2 ₽');

        $task = new Task;
        $task->user_id = auth()->id();
        $task->name = $request->name;
        $task->slug = str_slug($request->name).'-'.date('U');
        $task->category_id = $request->category_id;
        $task->description = $request->description;
        $task->answer = $request->answer;
        $task->salary = $request->salary;
        $task->url = $request->url;
        $task->technology = $request->technology;
        $task->period = $period;
        $task->time = $request->time;
        $task->type = $request->type;
        $task->question = strtolower($question);
        $task->save();

        return redirect()->route('managetask')->withToaststatus('success')->withToast('Задание создано!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $task = Task::where('slug',$slug)->first();
        $user = User::find(auth()->id());
        $report = $user->report()->where('task_id',$task->id)->first();
        $success = $user->pay()->where('id',$task->id)->first();
        $rejected = $user->rejected()->where('id',$task->id)->first();
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        //Limit
        $limit_time = 0;
        $limit = $task->limit->where('user_id',auth()->id())->first();

        if(isset($report->finished))
            if ($report->finished == true)
                $status = 'finished';
            else
                $status = 'working';
        elseif(isset($success))
            $status = 'success';
        elseif(isset($rejected))
            $status = 'rejected';
        else
            $status = 'new';

        if (isset($limit->finish_at) && !isset($rejected)){
            $now = strtotime('now');
            $finish_limit = strtotime($limit->finish_at);
            if($now < $finish_limit) {
                $limit_time = ceil(((intval($finish_limit) - intval($now)) / 3600));
                $status = 'limit';
            }
        }

        return view('task.show')
            ->withTask($task)
            ->withUser($user)
            ->withReport($report)
            ->withStatus($status)
            ->withContexts($contexts)
            ->withLimittime($limit_time);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(auth()->id());
        $category = Category::where('is_show', true)->get();
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $task = Task::find($id);
        if (!isset($task) or $task->user_id != auth()->id())
            return redirect()-route('managetask')->withToaststatus('error')->withToast('Задание не найдено!');
        return view('task.edit')
            ->withCategory($category)
            ->withUser($user)
            ->withContexts($contexts)
            ->withTask($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        dd($request);
        $this->validate($request, array(
            'name' => 'required|max:100|min:5',
            'description' => 'required|min:10|max:3000',
            'answer' => 'required|min:10|max:500',
            'salary' => 'required|numeric',
            'technology' => 'required|boolean',
            'time' => 'required|numeric',
            'type' => 'required|numeric',
            'category_id' => 'required|numeric',
        ));

        if ($request->technology == 1) {
            $this->validate($request, array('period' => 'required'));
            $period = $request->period;
        } else {
            $period = null;
        }

        if ($request->type == 1) {
            $this->validate($request, array('question' => 'required|min:10|max:500'));
            $question = $request->question;
        } else {
            $question = null;
        }

        if ((float)$request->salary < 0.2)
            return redirect()->back()->withSalary('Цена должна быть больше чем 0.2 ₽');

        $task = Task::find($id);
        if (!isset($task) or $task->user_id != auth()->id())
            return redirect()->route('managetask')->withToaststatus('success')->withToast('Задание не найдено!');
        $task->name = $request->name;
        $task->slug = str_slug($request->name).'-'.date('U');
        $task->category_id = $request->category_id;

        if ($task->salary != $request->salary){
            $task->user->balance += $task->available * $task->salary * 1.2;
            $task->available = 0;
            $task->is_show = false;
        }

        $task->description = $request->description;
        $task->answer = $request->answer;
        $task->salary = $request->salary;
        $task->url = $request->url;
        $task->technology = $request->technology;
        $task->period = $period;
        $task->time = $request->time;
        $task->type = $request->type;
        $task->question = strtolower($question);
        $task->user->save();
        $task->save();

        return redirect()->route('managetask')->withToaststatus('success')->withToast('Задание отредактировано!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!isset($task) or $task->user_id != auth()->id())
            return redirect('/manage/tasks');
        $task->user->balance += $task->available * $task->salary * 1.2;
        $task->user->save();
        $task->delete();
        return redirect()->back()->withToaststatus('success')->withToast('Задание удалено!');
    }
    // Setting menu
    public function new_tasks()
    {
        $title = 'Новые';
        $user = User::find(auth()->id());
        $tasks = Task::where('is_show', true)->orderBy('updated_at', 'desc')->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }

    public function reusable()
    {
        $title = 'Многоразовые';
        $user = User::find(auth()->id());
        $tasks = Task::where('is_show', true)->where('technology','1')->orderBy('updated_at', 'desc')->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }

    public function paid()
    {
        $title = 'Оплачение';
        $user = User::find(auth()->id());
        $tasks = $user->pay()->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.setting.paid')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function rejected()
    {
        $title = 'Отклонены';
        $user = User::find(auth()->id());
        $tasks = $user->rejected()->where('is_show',true)->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.setting.rejected')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function deleting()
    {
        $title = 'Удаление';
        $user = User::find(auth()->id());
        $tasks = $user->deleting()->where('is_show',true)->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.setting.deleting')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function searchid(Request $request){
        $this->validate($request, array(
            'search_id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $tasks = Task::where('id',$request->search_id)->where('is_show',true)->paginate(1);
        $category = Category::where('is_show', true)->get();
        $title = "ID: ".$request->search_id;
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function userid(Request $request){
        $this->validate($request, array(
            'user_id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $tasks = Task::where('user_id',$request->user_id)->where('is_show',true)->paginate(10);
        $category = Category::where('is_show', true)->get();
        $title = "ID рекламодателя: ".$request->user_id;
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function url(Request $request){
        $this->validate($request, array(
            'url' => 'required',
        ));

        $user = User::find(auth()->id());
        $tasks = Task::where('url','like','%'.$request->url.'%')->where('is_show',true)->paginate(10);
        $category = Category::where('is_show', true)->get();
        $title = "URL: ".$request->url;
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function category($slug)
    {
        $user = User::find(auth()->id());

        $notIn = $user->trash()->get();

        if (count($notIn) > 0 )
            foreach ($notIn as $val)
                $not_show[] = $val->id;

        $nottrash = $user->pay()->get();

        if (count($nottrash) > 0 )
            foreach ($nottrash as $val)
                $not_show[] = $val->id;

        $notdanger = $user->rejected()->get();

        if (count($notdanger) > 0 )
            foreach ($notdanger as $val)
                $not_show[] = $val->id;
        else
            $not_show[] = 0;

        $cat = Category::where('slug', $slug)->first();
        $tasks = $cat->task()->whereNotIn('id', $not_show)->where('is_show',true)->paginate(10);
        $category = Category::where('is_show',true)->get();
        $title = $cat->name;
        return view('task.index')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user)
            ->withTitle($title);
    }
    public function comments($slug){

        $user = User::find(auth()->id());
        $task = Task::where('slug',$slug)->first();
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $form_show = count(Comment::where('user_id',auth()->id())
            ->where('commentable_id',$task->id)
            ->where('commentable_type','App\Task')
            ->first());
        return view('task.comment')
            ->withUser($user)
            ->withTask($task)
            ->withContexts($contexts)
            ->withForm($form_show);

    }

    public function createcomment(Request $request){

        $this->validate($request,[
            'description' => 'required|min:3|max:250',
            'id' => 'required|numeric'
        ]);

        //dd($request);
        $task = Task::find($request->id);
        $valid = $task->comments()->where('user_id',auth()->id())->first();
        if($task && count($valid) < 1){
            $comment = New Comment;
            $comment->description = $request->description;
            $comment->user_id = auth()->id();
            $comment->commentable_id = $request->id;
            $comment->commentable_type = "App\Task";
            $comment->save();

            $notification = new Notification;
            $notification->user_id = $task->user_id;
            $notification->description = 'У Вас новый отзыв на задании <a href="/comments/'.$task->slug.'">«'.$task->name.'»</a> .';
            $notification->status = 'is-info';
            $notification->save();

            return redirect()->back()->withToaststatus('success')->withToast('Комментарий создан!');
        }else{
            return redirect()->back();
        }


    }
}
