<?php

namespace App\Http\Controllers;

use App\Checkedmessage;
use App\Message;
use Illuminate\Http\Request;
use App\User;
use App\Context;
use App\Notification;
use App\Comment;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $checked = $user->checkedmessage->all();
        if (count($checked) > 0)
            foreach ($checked as $val)
                $not_show[] = $val->message_id;
        else
            $not_show[] = 0;

        $messages = Message::where('is_show', true)
            ->whereNotIn('id', $not_show)
            ->orderBy('success','asc')
            ->paginate(10);

        return view('message.index')
            ->withUser($user)
            ->withMessages($messages)
            ->withTitle('Письма')
            ->withContexts($contexts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('message.create')->withUser($user)->withContexts($contexts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|max:100|min:5',
            'description' => 'required|min:10|max:3000',
            'question' => 'required|max:500',
            'answer' => 'required|max:300',
            's_false_answer' => 'required|max:300',
            'f_false_answer' => 'required|max:300',
            'delivery' => 'required|numeric',
        ));

        $message = new Message;
        $message->user_id = auth()->id();
        $message->name = $request->name;
        $message->slug = str_slug($request->name).'-'.date('U');
        $message->description = $request->description;
        $message->answer = $request->answer;
        $message->delivery = $request->delivery;
        $message->f_false_answer = $request->f_false_answer;
        $message->s_false_answer = $request->s_false_answer;
        $message->url = $request->url;
        $message->question = $request->question;
        $message->save();

        return redirect('/manage/message');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $user = User::find(auth()->id());
        $message = Message::where('slug',$slug)->where('is_show',true)->where('available','>','0')->first();
        if (!isset($message))
            return redirect('/message');
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $checked = $user->checkedmessage()->where('message_id',$message->id)->first();
        if (isset($checked)){
            $answer = $checked->status;
        } else {
            $answer = array($message->answer,$message->f_false_answer,$message->s_false_answer);
            shuffle($answer);
        }
        return view('message.show')
            ->withUser($user)
            ->withContexts($contexts)
            ->withMessage($message)
            ->withAnswers($answer);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(auth()->id());
        $message = $user->message()->find($id);
        if (!isset($message))
            return redirect('/manage/message');
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.message.edit')
            ->withUser($user)
            ->withContexts($contexts)
            ->withMessage($message);
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
            'name' => 'required|max:100|min:5',
            'description' => 'required|min:10|max:3000',
            'question' => 'required|max:500',
            'answer' => 'required|max:300',
            's_false_answer' => 'required|max:300',
            'f_false_answer' => 'required|max:300',
            'delivery' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $message = $user->message()->find($id);
        if (!isset($message))
            return redirect('/manage/message');

        if ($message->name  != $request->name){
            $message->name = $request->name;
            $message->slug = str_slug($request->name).'-'.date('U');
        }
        $message->description = $request->description;
        $message->answer = $request->answer;
        $message->delivery = $request->delivery;
        $message->f_false_answer = $request->f_false_answer;
        $message->s_false_answer = $request->s_false_answer;
        $message->url = $request->url;
        $message->question = $request->question;
        $message->save();

        return redirect('/manage/message');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find(auth()->id());
        $message = $user->message()->find($id);
        if(!isset($message))
            return redirect('/manage/message');
        $user->balance += $message->available * 0.2;
        $message->delete();
        $user->save();
        return redirect('/manage/message');

    }
    public function manage(){
        $user = User::find(auth()->id());
        $message = $user->message()->paginate(10);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.message.index')->withUser($user)->withMessage($message)->withContexts($contexts);
    }
    public function status(Request $request)
    {
        $message = User::find(auth()->id())->message()->find($request->id);

        if ($message->is_show == false)
            if ($message->available > 0)
                $message->is_show = true;
            else
                return redirect()->back()->withErrors('Пополните баланс');
        else
            $message->is_show = false;

        $message->save();

        return redirect()->back();
    }
    public function pay($id)
    {
        $user = User::find(auth()->id());
        $message = $user->message()->find($id);
        if(!isset($message))
            return redirect('/manage/message/');
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();

        return view('manage.message.pay')->withUser($user)->withMessage($message)->withContexts($contexts);
    }
    public function buy(Request $request)
    {
        $this->validate($request, array(
            'count' => 'required|numeric',
            'id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $message = $user->message()->find($request->id);
        if(!isset($message))
            return redirect('/manage/message/');
        $request->count = floor($request->count);
        $price = $request->count * 0.2;

        if ($user->balance < $price)
            return redirect()->back();

        $user->balance -= $price;
        $message->available += $request->count;

        $user->save();
        $message->save();

        $notification = new Notification;
        $notification->user_id = auth()->id();
        $notification->description = 'Пополнено письмо: <a href="/manage/message/">'.$message->name.'</a> на : '.intval($request->count).' шт. :) .';
        $notification->status = 'is-primary';
        $notification->save();

        return redirect('/manage/message');

    }
    public function work(Request $request){
        $message = Message::find($request->id);
        $user = User::find(auth()->id());
        if (!isset($message) or $message->available < 0)
            return redirect('/message');

        $checked = new Checkedmessage;
        $checked->user_id = auth()->id();
        $checked->finish_at = now()->addHours($message->delivery);
        $checked->message_id = $message->id;
        $notification = new Notification;
        $notification->user_id = auth()->id();
        if ($message->answer == $request->answer){
            $message->available--;
            if ($message->available < 1)
                $message->is_show = false;
            $message->success++;
            $user->balance += 0.18;
            $user->save();
            $checked->status = 'success';

            $notification->description = 'Оплачено письмо: <a href="/message/'.$message->slug.'">'.$message->name.'</a> + 0.18 ₽ :) .';
            $notification->status = 'is-primary';
        } else {
            $message->danger++;
            $checked->status = 'danger';

            $notification->description = 'Отклонено письмо: <a href="/message/'.$message->slug.'">'.$message->name.'</a> :( .';
            $notification->status = 'is-warning';
        }
        $notification->save();
        $message->save();
        $checked->save();
        return redirect()->back();
    }
    public function comments($slug){
        $user = User::find(auth()->id());
        $message = Message::where('slug',$slug)->first();
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $form_show = count(Comment::where('user_id',auth()->id())
            ->where('commentable_id',$message->id)
            ->where('commentable_type','App\Message')
            ->first());
        return view('message.comment')
            ->withUser($user)
            ->withMessage($message)
            ->withContexts($contexts)
            ->withForm($form_show);

    }
    public function createcomment(Request $request){

        $this->validate($request,[
            'description' => 'required|min:3|max:250',
            'id' => 'required|numeric'
        ]);

        //dd($request);
        $message = Message::find($request->id);
        $valid = $message->comments()->where('user_id',auth()->id())->first();
        if($message && count($valid) < 1){
            $comment = New Comment;
            $comment->description = $request->description;
            $comment->user_id = auth()->id();
            $comment->commentable_id = $request->id;
            $comment->commentable_type = "App\Message";
            $comment->save();

            $notification = new Notification;
            $notification->user_id = $message->user_id;
            $notification->description = 'У Вас новый отзыв на письме <a href="/message/comments/'.$message->slug.'">«'.$message->name.'»</a> .';
            $notification->status = 'is-info';
            $notification->save();

            return redirect()->back();
        }else{
            return redirect()->back();
        }


    }
}
